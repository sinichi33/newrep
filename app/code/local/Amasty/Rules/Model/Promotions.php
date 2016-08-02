<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Rules
 */
class Amasty_Rules_Model_Promotions
{
    private $_discount = array();
    public $itemsWithDiscount = null;


    public function process($observer)
    {

        $rule = $observer->getEvent()->getRule();
        $item = $observer->getEvent()->getItem();

        if (!$item->getId()) {
            return false;
        }

        $address = $observer->getEvent()->getAddress();
        $quote = $observer->getEvent()->getQuote();
        $itemId = $item->getId();
        $result = $observer->getEvent()->getResult();

        $amountToDisplay = 0;
        $isSpecialPromotions = false;
        $types = Mage::helper('amrules')->getDiscountTypes(true);
        if (isset($types[$rule->getSimpleAction()])) {

            if (!isset($this->_discount[$rule->getId()])) {
                $className = str_replace('_', '', $rule->getSimpleAction());
                $ruleProcessor = Mage::getSingleton(
                    'amrules_discount/' . $className
                );

                $discount = $ruleProcessor->calculateDiscount(
                    $rule, $address, $quote
                );

                $discount = $ruleProcessor->prepareDiscount($discount,$address);

                $this->_discount[$rule->getId()] = $discount;

            }

            $r = $this->_discount[$rule->getId()];

            if (!empty($r[$itemId])) {

                $isSpecialPromotions = true;
                isset($r[$item->getId()]['percent']) ? $r[$item->getId()]['percent'] : $r[$item->getId()]['percent'] = 0;

                $amountToDisplay = $r[$itemId]['discount'];
            }
        }else { //it's default rule
            $amountToDisplay = $observer->getEvent()->getResult()->getDiscountAmount();
        }

        if ($this->skip($rule, $item, $address) && $amountToDisplay > 0.0001 ) {
            $this->unsetDiscount($result, $item );
            return false;
        }

        if ($isSpecialPromotions)
            $this->setDiscount(
                $result, $item, $r[$itemId]['discount'],
                $r[$itemId]['base_discount'], $r[$itemId]['percent']
            );

        if ($amountToDisplay > 0.0001)
            $this->_addFullDescription($address,$rule,$item,$amountToDisplay);

        return true;
    }

    protected function setDiscount($result, $item, $discount, $baseDiscount, $percent) {
        $result->setDiscountAmount($discount);
        $result->setBaseDiscountAmount($baseDiscount);
        if ($percent > 0) {
            $item->setDiscountPercent($percent);
        }
        $item->setIsSpecialPromotion(true);
    }

    protected function unsetDiscount($result, $item) {
        $result->setDiscountAmount(0);
        $result->setBaseDiscountAmount(0);
        $item->setDiscountPercent(0);
        $item->setIsSpecialPromotion(false);
    }

    /**
     * determines if we should skip the items with special price or other (in futeure) conditions
     *
     * @return bool
     */
    public function skip($rule, $item, $address)
    {
        if ($rule->getSimpleAction() == 'cart_fixed') {
            return false;
        }

        $website_id = Mage::app()->getWebsite()->getId();
        $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();

        $origProduct = $item->getProduct();
        $tierPrices = $origProduct->getTierPrice();
        if (Mage::getStoreConfig('amrules/general/skip_tier_price')) {
            foreach ($tierPrices as $tierPrice) {
                if (($tierPrice['cust_group'] == $groupId || Mage_Customer_Model_Group::CUST_GROUP_ALL == $tierPrice['cust_group'])
                    && $item->getQty() >= $tierPrice['price_qty'] && $website_id == $tierPrice['website_id']) {
                    return true;
                }
            }
        }

        if ($item->getProductType() == 'bundle') {
            return false;
        }

        if (is_null($this->itemsWithDiscount)) {
            $productIds = array();
            $this->itemsWithDiscount = array();

            foreach (Mage::getSingleton('amrules_discount/abstract')->getAllItems($address) as $addressItem) {
                $productIds[] = $addressItem->getProductId();
            }

            if (!$productIds) {
                return false;
            }

            $productsCollection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addPriceData()
                ->addAttributeToFilter('entity_id', array('in' => $productIds))
                ->addAttributeToFilter(
                    'price', array('gt' => new Zend_Db_Expr('final_price'))
                );

            foreach ($productsCollection as $product) {
                $this->itemsWithDiscount[] = $product->getId();
            }
        }

        if (Mage::getStoreConfig('amrules/general/skip_special_price_configurable')) {
            if ($item->getProductType() == "configurable") {
                foreach ($item->getChildren() as $child) {
                    if (in_array($child->getProductId(), $this->itemsWithDiscount)) {
                        return true;
                    }
                }
            }
        }
        if (Mage::getStoreConfig('amrules/general/skip_special_price')) {
            if (in_array($item->getProductId(), $this->itemsWithDiscount)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adds a detailed description of the discount
     */
    protected function _addFullDescription($address, $rule, $item, $discount)
    {
        // we need this to fix double prices with one step checkouts
        $ind = $rule->getId() . '-' . $item->getId();
        if (isset($this->descrPerItem[$ind])) {
            return $this;
        }
        $this->descrPerItem[$ind] = true;

        $descr = $address->getFullDescr();
        if (!is_array($descr)) {
            $descr = array();
        }

        if (empty($descr[$rule->getId()])) {

            $ruleLabel = $rule->getStoreLabel($address->getQuote()->getStore());
            if (!$ruleLabel) {
                if (Mage::helper('ambase')->isModuleActive('Amasty_Coupon')) {
                    if (!$ruleLabel) {
                        $ruleLabel = $rule->getCouponCode(); // possible wrong code, known issue
                    }
                } else { // most frequent case
                    // take into account "generate and import amasty extension"
                    //	UseAutoGeneration
                    if ($rule->getUseAutoGeneration() || $rule->getCouponCode()) {
                        $ruleLabel = $address->getQuote()->getCouponCode();
                    }
                }
            }

            if (!$ruleLabel) {
                $ruleLabel = $rule->getName();
            }

            $descr[$rule->getId()] = array('label' => '<strong>' . htmlspecialchars($ruleLabel) . '</strong>', 'amount' => 0);
        }
        // skip the rule as it adds discount to each item
        // version before 1.4.1 has no class constants for actions
        $skipTypes = array('cart_fixed',  Amasty_Rules_Helper_Data::TYPE_AMOUNT);

        if (!in_array($rule->getSimpleAction(), $skipTypes) && Mage::getStoreConfig('amrules/general/breakdown_products')) {
            $sep = ($descr[$rule->getId()]['amount'] > 0) ? ', <br/> ' : ': ';
            $descr[$rule->getId()]['label'] = $descr[$rule->getId()]['label'] . $sep . htmlspecialchars($item->getName());
        }

        $descr[$rule->getId()]['amount'] += $discount;

        $address->setFullDescr($descr);

    }
}