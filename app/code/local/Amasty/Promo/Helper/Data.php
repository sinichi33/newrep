<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */
class Amasty_Promo_Helper_Data extends Mage_Core_Helper_Abstract 
{
    protected $_productsCache = null;
    protected $_rules = array();

    public function checkAvailableQty($product, $qtyRequested)
    {
        /**
         * @var Mage_Checkout_Model_Cart $cart
         */
        $cart = Mage::getModel('checkout/cart');

        $stockItem = Mage::getModel('cataloginventory/stock_item')
            ->assignProduct($product);

        if (!$stockItem->getManageStock())
            return $qtyRequested;

        $qtyAdded = 0;
        foreach ($cart->getItems() as $item) {
            if ($item->getProductId() == $product->getId()) {
                $qtyAdded += $item->getQty();
            }
        }

        $qty = $stockItem->getStockQty() - $qtyAdded;

        return min($qty, $qtyRequested);
    }

    public function addProduct($product, $super = false, $options = false, $bundleOptions = false, $ruleId = false, $amgiftcardValues = array(), $qty = 1, $downloadableLinks = array())
    {
        /**
         * @var Mage_Checkout_Model_Cart $cart
         */
        $cart = Mage::getModel('checkout/cart');

        $qty = $this->checkAvailableQty($product, $qty);

        if ($qty <= 0) {
            $this->showMessage(
                $this->__(
                    "We apologize, but your free gift is not available at the moment",
                    $product->getName()
                ), false, true
            );

            return;
        }

        $requestInfo = array(
            'qty' => $qty,
            'options' => array()
        );

        if ($super)
            $requestInfo['super_attribute'] = $super;

        if ($options)
            $requestInfo['options'] = $options;

        if ($bundleOptions)
            $requestInfo['bundle_option'] = $bundleOptions;

		/* To compatibility amgiftcard module */
		if($amgiftcardValues) {
			$requestInfo = array_merge($amgiftcardValues, $requestInfo);
		}

        if (count($downloadableLinks) > 0 && $product->getTypeId() == 'downloadable'){
            $requestInfo['links'] = $downloadableLinks;
        }

        $requestInfo['options']['ampromo_rule_id'] = $ruleId;

        try
        {
            $cart->addProduct(+$product->getId(), $requestInfo);

            $cart->getQuote()->setTotalsCollectedFlag(false);

            $cart->getQuote()->getShippingAddress()->unsetData('cached_items_nonnominal');

            $cart->getQuote()->collectTotals();

            $cart->getQuote()->save();

            Mage::getSingleton('ampromo/registry')->restore($product->getData('sku'));

            if (!Mage::app()->getRequest()->isXmlHttpRequest()) {
                $this->showMessage(
                    $this->__(
                        "Free gift <b>%s</b> was added to your shopping cart",
                        $product->getName()
                    ), false, true
                );
            }
        }
        catch (Exception $e)
        {
            $this->showMessage($e->getMessage(), true, true);
        }
    }

    public function getRule($ruleId){
        if (!isset($this->_rules[$ruleId]))
        {
            $this->_rules[$ruleId] = Mage::getModel('salesrule/rule');
            $this->_rules[$ruleId]->load($ruleId);
        }

        return $this->_rules[$ruleId];
    }

    public function getNewItems()
    {
        if ($this->_productsCache === null)
        {
            $items = Mage::getSingleton('ampromo/registry')->getLimits();

            $groups = $items['_groups'];
            unset($items['_groups']);

            if (!$items && !$groups)
                return array();

            $allowedSku = array_keys($items);

            $sku2rules = array();

            foreach($items as $sku => $item){
                $sku2rules[$item['sku']] = $item['rule_id'];
            }

            foreach ($groups as $ruleId => $rule)
            {
                $allowedSku = array_merge($allowedSku, $rule['sku']);

                if (is_array($rule['sku'])){
                    foreach($rule['sku'] as $sku){
                        $sku2rules[$sku] = $rule['rule_id'];
                    }
                }

            }

			$addAttributes = array();
			if($this->isModuleEnabled('Amasty_GiftCard')) {
				$addAttributes = Mage::helper('amgiftcard')->getAmGiftCardOptionsInCart();
			}

            $products = Mage::getResourceModel('catalog/product_collection')
                ->addFieldToFilter('sku', array('in' => $allowedSku))
				->addAttributeToSelect(array_merge(array(
                    'name', 'small_image', 'status', 'visibility', 'price',
                    'links_purchased_separately', 'links_exist'
                ), $addAttributes))
            ;

            foreach ($products as $key => $product)
            {
                $ruleId = isset($sku2rules[$product->getSku()]) ? $sku2rules[$product->getSku()] : null;
                $rule = $this->getRule($ruleId);

                if (!in_array($product->getTypeId(), array('simple', 'configurable', 'virtual', 'bundle', 'amgiftcard', 'downloadable')))
                {
                    $this->showMessage($this->__("We apologize, but products of type <b>%s</b> are not supported", $product->getTypeId()));
                    $products->removeItemByKey($key);
                }

                if (($product->getStatus() != Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                    || !$product->isSalable()
                    || !$this->checkAvailableQty($product, 1)
                ) {
                    $this->showMessage($this->__("We apologize, but your free gift is not available at the moment"));
                    $products->removeItemByKey($key);
                }

                foreach ($product->getProductOptionsCollection() as $option)
                {
                    $option->setProduct($product);
                    $product->addOption($option);
                }

                if ($rule && $rule->getAmpromoShowOrigPrice()) {
                    $product->setAmpromoShowOrigPrice($rule->getAmpromoShowOrigPrice());
                    $product->setSpecialPrice($this->getDiscountPrice($rule, $product->getPrice()));
                    $product->setFinalPrice($product->getSpecialPrice());
                }

            }

            $this->_productsCache = $products;
        }

        return $this->_productsCache;
    }

    function getDiscountPrice($rule, $price){

        $discountValue = $rule->getAmpromoDiscountValue();
        $minPrice = $rule->getAmpromoMinPrice();

        if (!empty($discountValue)) {
            $delta = 0;

            preg_match("/[0-9]+(\.[0-9][0-9]?)?/", $discountValue, $matches);

            $operator = $discountValue[0];

            if ('%' == $discountValue[strlen($discountValue)-1] && $matches[0]) {
                $delta = $price*$matches[0]/100;
                $operator = "-";
            } else {
                $delta = $matches[0];
            }

            switch ($operator) {
                case '+':
                    $price = $price + $delta;
                    break;
                case '-':
                    $price = $price - $delta;
                    break;
                case '*':
                    $price = $price * $delta;
                    break;
                case '/':
                    $price = $price / $delta;
                    break;
                default:
                    $price = $delta;
                    break;
            }
        } else {
            $price = 0;
        }

        if (!empty($minPrice) && $price < $minPrice){
            $price = $minPrice;
        }

        return $price;
    }

    public function showMessage($message, $isError = true, $showEachTime=false)
    {
        if (!Mage::getStoreConfigFlag('ampromo/messages/errors') && $isError)
            return;

        if (!Mage::getStoreConfigFlag('ampromo/messages/success') && !$isError)
            return;

        // show on cart page only
        $all = Mage::getSingleton('checkout/session')->getMessages(false)->toString();
        if (false !== strpos($all, $message))
            return;

        if ($isError && isset($_GET['debug'])){
            Mage::getSingleton('checkout/session')->addError($message);
        }
        else {
            $arr = Mage::getSingleton('checkout/session')->getAmpromoMessages();
            if (!is_array($arr)){
                $arr = array();
            }
            if (!in_array($message, $arr) || $showEachTime){
                Mage::getSingleton('checkout/session')->addSuccess($message);
                $arr[] = $message;
                Mage::getSingleton('checkout/session')->setAmpromoMessages($arr);
            }
        }
    }

    public function processPattern($pattern)
    {
        $result = preg_replace_callback(
            '#{url\s+(?P<url>[\w/]+?)}#',
            array($this, 'replaceUrl'),
            $pattern
        );

        return $result;
    }

    public function replaceUrl($matches)
    {
        return $this->_getUrl($matches['url']);
    }

    public function updateNotificationCookie($value = null)
    {
        if ($value === null) {
            $newItems = $this->getNewItems();
            $value = empty($newItems) ? 0 : 1;
        }

        Mage::getModel('core/cookie')->set(
            'am_promo_notification',
            $value,
            null, null, null, null, false
        );
    }
}
