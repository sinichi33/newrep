<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */
class Amasty_Promo_Model_Registry
{
    protected $_hasItems = false;
    protected $_locked = false;

    public function addPromoItem($sku, $qty, $ruleId)
    {
        if ($this->_locked)
            return;

        if (!$this->_hasItems)
            $this->reset();

        $this->_hasItems = true;

        $items = Mage::getSingleton('checkout/session')->getAmpromoItems();
        if ($items === null)
            $items = array('_groups' => array());

        $autoAdd = false;

        if (!is_array($sku)) {
            if (Mage::getStoreConfigFlag('ampromo/general/auto_add')) {
                $collection = Mage::getResourceModel('catalog/product_collection')
                    ->addAttributeToSelect(array('status', 'required_options'))
                    ->addAttributeToFilter('sku', $sku)
                    ->setPage(1, 1);

                $product = $collection->getFirstItem();

                $currentWebsiteId = Mage::app()->getStore()->getWebsiteId();
                if (!is_array($product->getWebsiteIds())
                    || !in_array($currentWebsiteId, $product->getWebsiteIds())){
                    // Ignore products from other websites
                    return;
                }

                if (!$product || ($product->getStatus() != Mage_Catalog_Model_Product_Status::STATUS_ENABLED) || !$product->isSalable()) {
                    $hlp = Mage::helper('ampromo');
                    $hlp->showMessage($hlp->__('We apologize, but your free gift is not available at the moment'));
                } else {
                    if (in_array($product->getTypeId(), array('simple', 'virtual')) && !$product->getTypeInstance(true)->hasRequiredOptions($product))
                        $autoAdd = true;
                }
            }

            if (isset($items[$sku])) {
                $items[$sku]['qty'] += $qty;
            } else {
                $items[$sku] = array(
                    'sku' => $sku,
                    'qty' => $qty,
                    'auto_add' => $autoAdd,
                    'rule_id' => $ruleId
                );
            }
        }
        else {
            $items['_groups'][$ruleId] = array(
                'sku' => $sku,
                'qty' => $qty,
                'rule_id' => $ruleId
            );
        }

        $this->_checkChanges($items);

        Mage::getSingleton('checkout/session')->setAmpromoItems($items);
    }

    protected function _checkChanges(&$items){
        $beforeItems = Mage::getSingleton('checkout/session')->getAmpromoItemsBefore();

        if (is_array($items) && is_array($beforeItems)){
            foreach($items as $sku => &$item){
                if (!empty($sku) && ($sku !== '_groups' )){
                    if (isset($beforeItems[$sku]) && $beforeItem = $beforeItems[$sku]){
                    $item['qtyIncreased'] = $beforeItem['qty'] < $item['qty'];
                    } else {
                        $item['qtyIncreased'] = true;
                    }
                }
            }
        }
    }

    public function getPromoItems()
    {
        return Mage::getSingleton('checkout/session')->getAmpromoItems();
    }

    public function backup()
    {
        Mage::getSingleton('checkout/session')->setAmpromoItemsBefore(Mage::getSingleton('checkout/session')->getAmpromoItems());
    }

    public function reset()
    {
        if ($this->_hasItems) {
            $this->_locked = true;
            return;
        }

        Mage::getSingleton('checkout/session')->setAmpromoItems(array('_groups' => array()));
    }

    public function getLimits()
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getSingleton('checkout/session')->getQuote();

        $allowed = $this->getPromoItems();

        foreach ($quote->getAllItems() as $item)
        {
            /** @var Mage_Sales_Model_Quote_Item $item */

            $sku = $item->getProduct()->getData('sku');

            if ($item->getIsPromo()) {
                if (isset($allowed['_groups'][$item->getRuleId()]))
                {
                    if ($item->getParentItem())
                        continue;

                    $allowed['_groups'][$item->getRuleId()]['qty'] -= $item->getQty();
                    if ($allowed['_groups'][$item->getRuleId()]['qty'] <= 0)
                        unset($allowed['_groups'][$item->getRuleId()]);
                }
                else if (isset($allowed[$sku])) {
                    $allowed[$sku]['qty'] -= $item->getQty();

                    if ($allowed[$sku]['qty'] <= 0)
                        unset($allowed[$sku]);
                }
            }
        }

        return $allowed;
    }

    public function deleteProduct($sku)
    {
        $deletedItems = Mage::getSingleton('checkout/session')->getAmpromoDeletedItems();

        if (!$deletedItems)
            $deletedItems = array();

        $deletedItems[$sku] = true;

        Mage::getSingleton('checkout/session')->setAmpromoDeletedItems($deletedItems);
    }

    public function restore($sku)
    {
        $deletedItems = Mage::getSingleton('checkout/session')->getAmpromoDeletedItems();

        if (!$deletedItems || !isset($deletedItems[$sku]))
            return;

        unset($deletedItems[$sku]);

        Mage::getSingleton('checkout/session')->setAmpromoDeletedItems($deletedItems);
    }

    public function getDeletedItems()
    {
        $deletedItems = Mage::getSingleton('checkout/session')->getAmpromoDeletedItems();

        if (!$deletedItems)
            $deletedItems = array();

        return $deletedItems;
    }
}
