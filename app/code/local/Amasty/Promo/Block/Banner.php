<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */

    class Amasty_Promo_Block_Banner extends Mage_Catalog_Block_Product_Abstract
    {
        protected $_validRule = null;
        protected $_rulesCollection = null;

        protected function _getValidRule()
        {
            if (!$this->_validRule) {
                $this->_validRule = false ?
                    $this->_getQuoteBasedValidRule() :
                    $this->_getProductBasedValidRule() ;
            }
            return $this->_validRule;
        }

        protected function _getRulesCollection(){
            if (!$this->_rulesCollection) {
                $quote = Mage::getModel('checkout/cart')->getQuote();
                $store = Mage::app()->getStore($quote->getStoreId());

                $this->_rulesCollection = Mage::getModel('salesrule/rule')
                    ->getCollection()
                    ->setValidationFilter($store->getWebsiteId(), $quote->getCustomerGroupId(), $quote->getCouponCode());

                $this->_rulesCollection->getSelect()->where("simple_action in ('ampromo_items', 'ampromo_product') and is_active = 1");

            }
            return $this->_rulesCollection;
        }

        protected function _getProductBasedValidRule()
        {
            $validRule = new Varien_Object();
            $quoteItem = new Varien_Object();
            $quoteItem->setProduct($this->getProduct());

            foreach($this->_getRulesCollection() as $rule){
                $simpleAction = $rule->getSimpleAction();
                if ($rule->getActions()->validate($quoteItem)){
                    $validRule = $rule;
                    break;
                }
            }
            return $validRule;
        }

        protected function _getQuoteBasedValidRule()
        {
            $validRule = new Varien_Object();
            $currentQuote = Mage::getModel('checkout/cart')->getQuote();
            $afterQuote = Mage::getModel('sales/quote');
            $afterQuote->merge($currentQuote);
            $afterQuote->addProduct($this->getProduct());
            $afterQuote->collectTotals();

            $currentRules = array();

            foreach($this->_getRulesCollection() as $rule) {
                $simpleAction = $rule->getSimpleAction();
                foreach ($currentQuote->getItemsCollection() as $item) {
                    if ($item->getProduct()->getId() == $this->getProduct()->getId()){
                        if ($rule->getActions()->validate($item)){
                            $currentRules[] = $rule->getId();
                        }
                        break;
                    }
                }
            }

            foreach($this->_getRulesCollection() as $rule) {
                if (!in_array($rule->getId(), $currentRules)){
                    $simpleAction = $rule->getSimpleAction();
                    foreach ($afterQuote->getItemsCollection() as $item) {
                        if ($item->getProduct()->getId() == $this->getProduct()->getId()){
                            if ($rule->getActions()->validate($item)){
                                $validRule = $rule;
                            }
                            break;
                        }
                    }
                }
            }

            return $validRule;
        }

        function getDescription()
        {
            $validRule = $this->_getValidRule();
            return $validRule->getData('ampromo_' . $this->getPosition() . '_banner_description');
        }

        function getImage()
        {
            $validRule = $this->_getValidRule();
            return Mage::helper("ampromo/image")->getLink($validRule->getData('ampromo_' . $this->getPosition() . '_banner_img'));
        }

        function getAlt()
        {
            $validRule = $this->_getValidRule();
            return $validRule->getData('ampromo_' . $this->getPosition() . '_banner_alt');
        }

        function getHoverText()
        {
            $validRule = $this->_getValidRule();
            return $validRule->getData('ampromo_' . $this->getPosition() . '_banner_hover_text');
        }

        function getLink()
        {
            $validRule = $this->_getValidRule();
            return $validRule->getData('ampromo_' . $this->getPosition() . '_banner_link') ? $validRule->getData('ampromo_' . $this->getPosition() . '_banner_link') : "#";
        }

        function isShowGiftImages()
        {
            $validRule = $this->_getValidRule();
            return $validRule->getData('ampromo_' . $this->getPosition() . '_banner_gift_images') == 1;
        }
    }
?>