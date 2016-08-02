<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */
class Amasty_Promo_Model_Observer
{
    protected $_isHandled = array();
    protected $_toAdd = array();

    protected $_itemsWithDiscount = array();
    protected $_calcHelper;

    protected $_rules = array();

    /**
     * Process sales rule form creation
     * @param   Varien_Event_Observer $observer
     */
    public function handleFormCreation($observer)
    {
        $actionsSelect = $observer->getForm()->getElement('simple_action');
        if ($actionsSelect){
            $vals = $actionsSelect->getValues();
            $vals[] = array(
                'value' => 'ampromo_items',
                'label' => Mage::helper('ampromo')->__('Auto add promo items with products'),
                
            );
            $vals[] = array(
                'value' => 'ampromo_cart',
                'label' => Mage::helper('ampromo')->__('Auto add promo items for the whole cart'),
                
            );
            $vals[] = array(
                'value' => 'ampromo_product',
                'label' => Mage::helper('ampromo')->__('Auto add the same product'),
                
            );
            $vals[] = array(
                'value' => 'ampromo_spent',
                'label' => Mage::helper('ampromo')->__('Auto add promo items for every $X spent'),

            );
            
            $actionsSelect->setValues($vals);
            $actionsSelect->setOnchange('ampromo_hide_all();');
            
            $fldSet = $observer->getForm()->getElement('action_fieldset');
            $fldSet->addField('ampromo_type', 'select', array(
                    'name'     => 'ampromo_type',
                    'label' => Mage::helper('ampromo')->__('Type'),
                    'values' => array(
                        0 => Mage::helper('ampromo')->__('All SKUs below'),
                        1 => Mage::helper('ampromo')->__('One of the SKUs below')
                    ),
                ),
                'discount_amount'
            );
            $fldSet->addField('promo_sku', 'text', array(
                'name'     => 'promo_sku',
                'label' => Mage::helper('ampromo')->__('Promo Items'),
                'note'  => Mage::helper('ampromo')->__('Comma separated list of the SKUs'),
                ),
                'ampromo_type'
            );

        }
        
        return $this; 
    }
    
    /**
     * Process quote item validation and discount calculation
     * @param   Varien_Event_Observer $observer
     */
    public function handleValidation($observer) 
    {
        $rule = $observer->getEvent()->getRule();

        if (isset($this->_isHandled[$rule->getId()])){
            return $this;
        }



        $address = $observer->getEvent()->getAddress();

        if ($rule->getSimpleAction() == 'ampromo_product') {
            try {
                $item = $observer->getEvent()->getItem();

                if ($this->_skip($item, $address)) {
                    return false;
                }

                $discountStep     = max(1, $rule->getDiscountStep());
                $maxDiscountQty = 100000;
                if ($rule->getDiscountQty()){
                    $maxDiscountQty   = intVal(max(1, $rule->getDiscountQty()));                    
                }
				
                $discountAmount   = max(1, $rule->getDiscountAmount());
                $qty = min(floor($item->getQty() / $discountStep) * $discountAmount, $maxDiscountQty);

                if ($item->getParentItemId())
                    return false;

                if ($qty < 1){
                    return false;    
                }

                Mage::getSingleton('ampromo/registry')->addPromoItem(
                    $item->getProduct()->getData('sku'),
                    $qty,
                    $rule->getId()
                );
            }
            catch (Exception $e){
                $hlp = Mage::helper('ampromo');
                $hlp->showMessage($hlp->__(
                    'We apologize, but there is an error while adding free items to the cart: %s',
                    $e->getMessage()
                ));            
                return false;
            }   
        }
        
        if (!in_array($rule->getSimpleAction(), array(
            'ampromo_items',
            'ampromo_cart',
            'ampromo_spent'
        ))){
            return $this;
        }
       
        $this->_isHandled[$rule->getId()] = true;

        $promoSku = $rule->getPromoSku();
        if (!$promoSku){
            return $this;     
        }  
        
        $quote = $observer->getEvent()->getQuote();
        
        $qty = $this->_getFreeItemsQty($rule, $quote, $address);
        if (!$qty){
            //@todo  - add new field for label table
            // and show message like "Add 2 more products to get free items"
            return $this;         
        }

        if ($rule->getAmpromoType() == 1)
        {
            Mage::getSingleton('ampromo/registry')->addPromoItem(
                preg_split('/\s*,\s*/', $promoSku, -1, PREG_SPLIT_NO_EMPTY),
                $qty,
                $rule->getId()
            );
        }
        else
        {
            $promoSku = explode(',', $promoSku);
            foreach ($promoSku as $sku){
                $sku = trim($sku);
                if (!$sku){
                    continue;
                }

                Mage::getSingleton('ampromo/registry')->addPromoItem($sku, $qty, $rule->getId());
            }
        }

        return $this;
    }

    /**
     * determines if we should skip the items with special price or other (in futeure) conditions
     *
     * @param Mage_Sales_Model_Quote_Item $item
     * @param Amasty_Promo_Model_Sales_Quote_Address $address
     *
     * @return bool
     */
    protected function _skip($item, $address)
    {
        if (!Mage::getStoreConfig('ampromo/limitations/skip_special_price')) {
            return false;
        }

        if ($item->getProductType() == 'bundle') {
            return false;
        }

        if (is_null($this->_itemsWithDiscount) || count($this->_itemsWithDiscount)==0 ) {
            $productIds = array();
            $this->_itemsWithDiscount = array();

            foreach ($this->_getAllItems($address) as $addressItem) {
                $productIds[] = $addressItem->getProductId();
            }

            if (!$productIds) {
                return false;
            }

            $productsCollection = Mage::getModel('catalog/product')->getCollection()
                ->addPriceData()
                ->addAttributeToFilter('entity_id', array('in' => $productIds))
                ->addAttributeToFilter('price', array('gt' => new Zend_Db_Expr('final_price')));

            foreach ($productsCollection as $product) {
                $this->_itemsWithDiscount[] = $product->getId();
            }
        }

        if (Mage::getStoreConfig('ampromo/limitations/skip_special_price_configurable')) {
            if ($item->getProductType() == "configurable") {
                foreach ($item->getChildren() as $child) {
                    if (in_array($child->getProductId(), $this->_itemsWithDiscount)) {
                        return true;
                    }
                }
            }
        }

        if (!in_array($item->getProductId(), $this->_itemsWithDiscount)) {
            return false;
        }

        return true;
    }

    protected function _getAllItems($address)
    {
        $items = $address->getAllNonNominalItems();
        if (!$items) { // CE 1.3 version
            $items = $address->getAllVisibleItems();
        }
        if (!$items) { // cart has virtual products
            $cart = Mage::getSingleton('checkout/cart');
            $items = $cart->getItems();
        }
        return $items;
    }

    public function onCollectTotalsBefore($observer)
    {
        Mage::getSingleton('ampromo/registry')->reset();
    }

    /**
     * Revert 'deleted' status and auto add all simple products without required options
     * @param $observer
     * @return $this
     */
    public function onAddressCollectTotalsAfter($observer)
    {
        $quote = $observer->getQuoteAddress()->getQuote();

        $items = $quote->getAllItems();

        foreach ($items as $item)
        {
            if ($item->getIsPromo())
            {
                $item->isDeleted(false);
                $this->resetWeee($item);
            }
        }

        if (Mage::getStoreConfigFlag('ampromo/general/auto_add'))
        {
            $toAdd  = Mage::getSingleton('ampromo/registry')->getPromoItems();

            if (is_array($toAdd)) {
                unset($toAdd['_groups']);

                foreach ($items as $item)
                {
                    $sku = $item->getProduct()->getData('sku');

                    if (!isset($toAdd[$sku]))
                        continue;

//                if ($item->getIsPromo())
//                    $toAdd[$sku]['qty'] -= $item->getQty();

                    $qtyIncreased = isset($toAdd[$sku]['qtyIncreased']) ? $toAdd[$sku]['qtyIncreased'] : false;

                    if ($item->getIsPromo()){
                        if (!$qtyIncreased) {
                            unset($toAdd[$sku]); // to allow to decrease qty
                        } else {
                            $toAdd[$sku]['qty'] -= $item->getQty();
                        }
                    }
                }

                $deleted = Mage::getSingleton('ampromo/registry')->getDeletedItems();

                $this->_toAdd = array();

                foreach ($toAdd as $sku => $item)
                {
                    if ($item['qty'] > 0 && $item['auto_add'] && !isset($deleted[$sku]))
                    {
                        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);

                        if (isset($this->_toAdd[$product->getId()])) {
                            $this->_toAdd[$product->getId()]['qty'] += $item['qty'];
                        }
                        else {
                            $this->_toAdd[$product->getId()] = array(
                                'product' => $product,
                                'qty'     => $item['qty']
                            );
                        }
                    }
                }
            }
        }
    }

    public function resetWeee(&$item)
    {
        Mage::helper('weee')->setApplied($item, array());

        $item->setBaseWeeeTaxDisposition(0);
        $item->setWeeeTaxDisposition(0);

        $item->setBaseWeeeTaxRowDisposition(0);
        $item->setWeeeTaxRowDisposition(0);

        $item->setBaseWeeeTaxAppliedAmount(0);
        $item->setBaseWeeeTaxAppliedRowAmount(0);

        $item->setWeeeTaxAppliedAmount(0);
        $item->setWeeeTaxAppliedRowAmount(0);
    }

    /**
     * Mark item as deleted to prevent it's auto-addition
     * @param $observer
     */
    public function onQuoteRemoveItem($observer)
    {
        $action = Mage::app()->getRequest()->getActionName();
        if (!in_array($action, array('delete', 'ajaxDelete')))
            return;

        $id = (int) Mage::app()->getRequest()->getParam('id');

        $item = $observer->getEvent()->getQuoteItem();

        if (($item->getId() == $id) && $item->getIsPromo() && !$item->getParentId())
            Mage::getSingleton('ampromo/registry')->deleteProduct($item->getProduct()->getData('sku'));
    }

    public function decrementUsageAfterPlace($observer)
    {
        $order = $observer->getEvent()->getOrder();

        if (!$order) {
            return $this;
        }

        // lookup rule ids
        $ruleIds = explode(',', $order->getAppliedRuleIds());
        $ruleIds = array_unique($ruleIds);

        $ruleCustomer = null;
        $customerId = $order->getCustomerId();

        // use each rule (and apply to customer, if applicable)
        if (($order->getDiscountAmount() == 0) && (count($ruleIds) >= 1)) {
            foreach ($ruleIds as $ruleId) {
                if (!$ruleId) {
                    continue;
                }
                $rule = Mage::getModel('salesrule/rule');
                $rule->load($ruleId);
                if ($rule->getId()) {
                    $rule->setTimesUsed($rule->getTimesUsed() + 1);
                    $rule->save();

                    if ($customerId) {
                        $ruleCustomer = Mage::getModel('salesrule/rule_customer');
                        $ruleCustomer->loadByCustomerRule($customerId, $ruleId);

                        if ($ruleCustomer->getId()) {
                            $ruleCustomer->setTimesUsed($ruleCustomer->getTimesUsed() + 1);
                        }
                        else {
                            $ruleCustomer
                            ->setCustomerId($customerId)
                            ->setRuleId($ruleId)
                            ->setTimesUsed(1);
                        }
                        $ruleCustomer->save();
                    }
                }
            }
            $coupon = Mage::getModel('salesrule/coupon');
            /** @var Mage_SalesRule_Model_Coupon */
            $coupon->load($order->getCouponCode(), 'code');
            if ($coupon->getId()) {
                $coupon->setTimesUsed($coupon->getTimesUsed() + 1);
                $coupon->save();
                if ($customerId) {
                    $couponUsage = Mage::getResourceModel('salesrule/coupon_usage');
                    $couponUsage->updateCustomerCouponTimesUsed($customerId, $coupon->getId());
                }
            }
        }        
    }
    
    protected function _getCalcHelper()
    {

        if (!$this->_calcHelper){
            $this->_calcHelper = Mage::helper("ampromo/calc");
        }

        return $this->_calcHelper;
    }
    
    // find qty
    // (for the whole cart it is $rule->getDiscountQty()
    // for items it is (qty * (number of matched non-free items) / step)
    protected function _getFreeItemsQty($rule, $quote, $address)
    {  
        $amount = max(1, $rule->getDiscountAmount());
        $qty    = 0;
        if ('ampromo_cart' == $rule->getSimpleAction()){
            $qty = $amount;
        }
        else if ('ampromo_spent' == $rule->getSimpleAction()) {
            $step = $rule->getDiscountStep();

            if (!$step)
                return 0;

            $qty = floor($this->_getCalcHelper()->getQuoteSubtotal($quote) / $step) * $amount;

            $max = $rule->getDiscountQty();
            if ($max){
                $qty = min($max, $qty);
            }

            return $qty;
        }
        else {
            $step = max(1, $rule->getDiscountStep());
            foreach ($quote->getItemsCollection() as $item) {
                if (!$item) 
                    continue;
                    
                if ($item->getIsPromo())
                    continue;

                if ($this->_skip($item, $address)) {
                    continue;
                }
                    
                if (!$rule->getActions()->validate($item)) {
                    continue;
                }
                if ($item->getParentItemId()) {
                    continue;
                }
                if ($item->getProduct()->getParentProductId()) {
                    continue;
                }

                $qty = $qty + $item->getQty();
            } 
            
            $qty = floor($qty / $step) * $amount; 
            $max = $rule->getDiscountQty();
            if ($max){
                $qty = min($max, $qty);
            }
        }
        return $qty;        
    }  

    /**
     * Don't apply any discounts to free items
     * @param $observer
     */
    public function onProductAddAfter($observer)
    {
        $items = $observer->getItems();

        $this->_setItemPrefix($items);

        foreach ($items as $item)
        {
            if ($item->getIsPromo())
                $item->setNoDiscount(true);
        }
    }

    public function onCheckoutCartUpdateItemsBefore($observer)
    {
        Mage::getSingleton('ampromo/registry')->backup();
    }

    /**
     * Remove all not allowed items
     * @param $observer
     */
    public function onCollectTotalsAfter($observer)
    {
        if (!Mage::getSingleton('checkout/session')->hasQuote())
            return;

        Mage::helper('ampromo')->updateNotificationCookie();

        $allowedItems = Mage::getSingleton('ampromo/registry')->getPromoItems();
        $cart = Mage::getSingleton('checkout/cart');

        $customMessage = Mage::getStoreConfig('ampromo/general/message');

        foreach ($this->_toAdd as $item) {
            $product = $item['product'];
            $ruleId = $allowedItems[$product->getSku()] ? $allowedItems[$product->getSku()]['rule_id'] : null;

            Mage::helper('ampromo')->addProduct(
                $product,
                false, false, false, $ruleId, array(),
                $item['qty']
            );
        }

        $this->_toAdd = array();

        foreach ($observer->getQuote()->getAllItems() as $item)
        {
            if ($item->getIsPromo())
            {
                $ruleLabel = $item->getRule()->getStoreLabel();
                $ruleMessage = !empty($ruleLabel) ? $ruleLabel : $customMessage;

                if ($item->getParentItemId())
                    continue;

                $sku = $item->getProduct()->getData('sku');

                if (isset($allowedItems['_groups'][$item->getRuleId()])) // Add one of
                {
                    if ($allowedItems['_groups'][$item->getRuleId()]['qty'] <= 0)
                    {
                        $cart->removeItem($item->getId());
                    }
                    else if ($item->getQty() > $allowedItems['_groups'][$item->getRuleId()]['qty'])
                    {
                        $item->setQty($allowedItems['_groups'][$item->getRuleId()]['qty']);
                    }
                    if ($ruleMessage)
                        $item->setMessage($ruleMessage);

                    $allowedItems['_groups'][$item->getRuleId()]['qty'] -= $item->getQty();
                }
                else if (isset($allowedItems[$sku])) // Add all of
                {
                    if ($allowedItems[$sku]['qty'] <= 0)
                    {
                        $cart->removeItem($item->getId());
                    }
                    else if ($item->getQty() > $allowedItems[$sku]['qty'])
                    {
                        $item->setQty($allowedItems[$sku]['qty']);
                    }
                    if ($ruleMessage)
                        $item->setMessage($ruleMessage);

                    $allowedItems[$sku]['qty'] -= $item->getQty();
                }
                else
                    $cart->removeItem($item->getId());
            }
        }

        $this->updateQuoteTotalQty($observer->getQuote());
    }

    public function updateQuoteTotalQty(Mage_Sales_Model_Quote $quote)
    {
        $quote->setItemsCount(0);
        $quote->setItemsQty(0);
        $quote->setVirtualItemsQty(0);

        foreach ($quote->getAllVisibleItems() as $item) {
            if ($item->getParentItem()) {
                continue;
            }

            $children = $item->getChildren();
            if ($children && $item->isShipSeparately()) {
                foreach ($children as $child) {
                    if ($child->getProduct()->getIsVirtual()) {
                        $quote->setVirtualItemsQty($quote->getVirtualItemsQty() + $child->getQty()*$item->getQty());
                    }
                }
            }

            if ($item->getProduct()->getIsVirtual()) {
                $quote->setVirtualItemsQty($quote->getVirtualItemsQty() + $item->getQty());
            }
            $quote->setItemsCount($quote->getItemsCount()+1);
            $quote->setItemsQty((float) $quote->getItemsQty()+$item->getQty());
        }
    }

    public function onOrderPlaceBefore($observer)
    {
        $order = $observer->getOrder();

        $this->_setItemPrefix($order->getAllItems());
    }

    protected function _loadRule($id)
    {
        if (!isset($this->_rules[$id])){
            $this->_rules[$id] = Mage::getModel('salesrule/rule')->load($id);
        }
        return $this->_rules[$id];
    }

    protected function _setItemPrefix($items)
    {
        if ($prefix = Mage::getStoreConfig('ampromo/general/prefix'))
        {
            foreach ($items as $item)
            {
                $buyRequest = $item->getBuyRequest();

                if (isset($buyRequest['options']['ampromo_rule_id']))
                {
                    $rule = $this->_loadRule($buyRequest['options']['ampromo_rule_id']);
                    $ruleLabel = $rule->getStoreLabel();
                    $rulePrefix = !empty($ruleLabel) ? $ruleLabel : $prefix;
                    $item->setName($rulePrefix . ' ' . $item->getName());
                }
            }
        }
    }

    public function onCartItemUpdateBefore($observer)
    {
        $request = Mage::app()->getRequest();

        $id = (int)$request->getParam('id');
        $item = Mage::getSingleton('checkout/cart')->getQuote()->getItemById($id);

        if ($item->getId() && $item->getIsPromo())
        {
            $options = $request->getParam('options');
            $options['ampromo_rule_id'] = $item->getRuleId();
            $request->setParam('options', $options);
        }
    }

    public function onCheckoutSubmitAllAfter($observer)
    {
        Mage::getSingleton('ampromo/registry')->reset();
        Mage::helper('ampromo')->updateNotificationCookie(0);
    }

    public function salesRulePrepareSave($observer)
    {
        $this->_savePromoRuleImage($observer->getRequest(), 'ampromo_top_banner_img');
        $this->_savePromoRuleImage($observer->getRequest(), 'ampromo_after_name_banner_img');
        $this->_savePromoRuleImage($observer->getRequest(), 'ampromo_label_img');
    }

    protected function  _savePromoRuleImage($request, $file){
        if ($data = $request->getPost()) {

            if (isset($data[$file]) && isset($data[$file]['delete'])){
                $data[$file] = null;
            } else {

                if (isset($_FILES[$file]['name']) && $_FILES[$file]['name'] != '') {

                    $fileName = Mage::helper("ampromo/image")->upload($file);

                    $data[$file] = $fileName;
                }
                else {
                    if (isset($data[$file]['value']))
                        $data[$file] = basename($data[$file]['value']);
                }
            }

            $request->setPost($data);
        }
    }
}
