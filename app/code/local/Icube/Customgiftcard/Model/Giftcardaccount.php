<?php

/*
*   Overriding Enterprise_GiftCardAccount_Model_Giftcardaccount
*
*/
class Icube_Customgiftcard_Model_Giftcardaccount extends Enterprise_GiftCardAccount_Model_Giftcardaccount
{
    
    /**
     * Check if this gift card date is valid at the moment
     *
     * @return bool
     */
    public function isValidDate()
    {
        $fromDate = $this->getValidFromDate();
        $toDate = $this->getValidToDate();
        $currentDate = strtotime(Mage::getModel('core/date')->date('Y-m-d'));

        if ($fromDate && strtotime($fromDate) > $currentDate) {
            return true;
        } elseif ($toDate && strtotime($toDate) < $currentDate) {
            return true;
        }

        return false;
    }

    /**
     * Check all the gift card validity attributes
     *
     * @param bool $expirationCheck
     * @param bool $statusCheck
     * @param mixed $websiteCheck
     * @param mixed $balanceCheck
     * @return bool
     */
    public function isValid($expirationCheck = true, $statusCheck = true, $websiteCheck = false, $balanceCheck = true)
    {
        if (!$this->getId()) {
            $this->_throwException(
                Mage::helper('enterprise_giftcardaccount')->__('Wrong gift card account ID. Requested code: "%s"', $this->_requestedCode)
            );
        }

        if ($websiteCheck) {
            if ($websiteCheck === true) {
                $websiteCheck = null;
            }
            $website = Mage::app()->getWebsite($websiteCheck)->getId();
            if ($this->getWebsiteId() != $website) {
                $this->_throwException(
                    Mage::helper('enterprise_giftcardaccount')->__('Wrong gift card account website: %s.', $this->getWebsiteId())
                );
            }
        }

        if ($statusCheck && ($this->getStatus() != self::STATUS_ENABLED)) {
            $this->_throwException(
                Mage::helper('enterprise_giftcardaccount')->__('Gift card account %s is not enabled.', $this->getCode())
            );
        }

        if ($expirationCheck && $this->isExpired()) {
            $this->_throwException(
                Mage::helper('enterprise_giftcardaccount')->__('Gift card account %s is expired.', $this->getCode())
            );
        }

        if ($balanceCheck) {
            if ($this->getBalance() <= 0) {
                $this->_throwException(
                    Mage::helper('enterprise_giftcardaccount')->__('Gift card account %s balance does not have funds.', $this->getCode())
                );
            }
            if ($balanceCheck !== true && is_numeric($balanceCheck)) {
                if ($this->getBalance() < $balanceCheck) {
                    $this->_throwException(
                        Mage::helper('enterprise_giftcardaccount')->__('Gift card account %s balance is less than amount to be charged.', $this->getCode())
                    );
                }
            }
        }


        /**
        * ICUBE's custom validation
        *
        **/
        if (!isset($quote)) {
            $quote = $this->_getCheckoutSession()->getQuote();
            if(!$quote->getId()) $quote = Mage::getSingleton('adminhtml/session_quote')->getQuote();
        }

        // Restrict Combine for general GC only
        if ($this->getType() == 'general') {

            $gift_cards = unserialize($quote->getGiftCards());

            // loop through previously applied gift cards and find other general
            foreach ($gift_cards as $gc_check) {

                $gc_check_model = Mage::getModel('enterprise_giftcardaccount/giftcardaccount')->loadByCode($gc_check['c']);
                $gc_check_type = $gc_check_model->getType();

                if ($gc_check_type == 'general'
                    && ( $this->getRestrictCombine() || $gc_check_model->getRestrictCombine() )

                    && ( $this->getCode() != $gc_check['c'] )

                    ) {
                    $this->_throwException(
                        Mage::helper('enterprise_giftcardaccount')->__('Promo Gift Card cannot be combined with other Promo Gift Card')
                    );
                }
            }
        }

        $productSkus = array();
        foreach ($quote->getAllVisibleItems() as $item) {

            $product = $item->getProduct();
            array_push($productSkus, $product->getSku());

            // Promo Items
            if (!$this->getPromoItems() && $item->getBasePrice() < $product->getPrice() ) {
                $this->_throwException(
                    Mage::helper('enterprise_giftcardaccount')->__('Gift card account %s is not valid for promo item %s.', $this->getCode(), $product->getSku())
                );
            }

            // Excluded Product SKUs
            if ($this->getProductSkusExclusion()) {
                $productSkusExclusion = explode(',', $this->getProductSkusExclusion());
                $productSkusExclusion = array_unique($productSkusExclusion);
                $cartUrl = '<a href='.Mage::helper('checkout/cart')->getCartUrl().'>cart</a>';

                if(in_array($product->getSku(), $productSkusExclusion)) {
                    $this->_throwException(
                        Mage::helper('enterprise_giftcardaccount')->__('Gift card account %s is not valid for item %s. Please go back to %s to edit', $this->getCode(), $product->getName(), $cartUrl)
                    );
                }
            }

            // Category Ids
            if ($this->getCategoryIds()) {
                $categoryIds = explode(',', $this->getCategoryIds());
                $categoryIds = array_unique($categoryIds);
                
                $result = true;
                foreach ($categoryIds as $catId) {
                    if(in_array($catId, $product->getCategoryIds())){
                        $result = $result ? true : false;
                    }
                    else {
                        $categoryName[] = Mage::getModel('catalog/category')->load($catId)->getName();
                        $result = false;
                    }
                }

                if(!$result) {
                    $categoryName = implode(',', $categoryName);
                    $this->_throwException(
                        Mage::helper('enterprise_giftcardaccount')->__('Product %s is not assigned to category %s for Gift card account %s.',$product->getName(), $categoryName, $this->getCode())
                    );
                }
            }

            // Category Ids Exclusion
            if ($this->getCategoryIdsExclusion()) {
                $categoryIdsExclusion = explode(',', $this->getCategoryIdsExclusion());
                $categoryIdsExclusion = array_unique($categoryIdsExclusion);

                $result = true;
                foreach ($categoryIdsExclusion as $catId) {
                    if(in_array($catId, $product->getCategoryIds())){
                        $result = $result ? true : false;
                    }
                    else {
                        $categoryName[] = Mage::getModel('catalog/category')->load($catId)->getName();
                        $result = false;
                    }
                }

                if($result) {
                    $categoryName[] = Mage::getModel('catalog/category')->load($catId)->getName();
                    $categoryName = implode(',', $categoryName);
                    $this->_throwException(
                        Mage::helper('enterprise_giftcardaccount')->__('Product %s in category %s is not allowed for Gift card account %s.',$product->getName(), $categoryName, $this->getCode())
                    );
                }
            }

        }

        // Product SKUs
        if ($this->getProductSkus()) {
            $listSkus = explode(',', $this->getProductSkus());
            $listSkus = array_unique($listSkus);
            $cartUrl = '<a href='.Mage::helper('checkout/cart')->getCartUrl().'>cart</a>';
            
            foreach ($listSkus as $sku) {
                if(!in_array($sku, $productSkus)) {
                    $this->_throwException(
                        Mage::helper('enterprise_giftcardaccount')->__('Gift card account %s is not valid if shopping cart does not contain SKU(s) : %s. Please go back to %s to edit', $this->getCode(), $this->getProductSkus(), $cartUrl)
                    );
                }
            }
        }
        
        if ($quote->getBaseSubtotalWithDiscount() < $this->getMinPurchaseValue()) {
            $this->_throwException(
                Mage::helper('enterprise_giftcardaccount')->__('Amount to be charged is less than Gift card account %s minimum purchase value.', $this->getCode())
            );
        }

        if ($this->isValidDate()) {
            $this->_throwException(
                Mage::helper('enterprise_giftcardaccount')->__('Gift card account %s date is not valid at the moment.', $this->getCode())
            );
        }

        return true;
    }

    /**
     * Obscure real exception message to prevent brute force attacks
     *
     * @throws Mage_Core_Exception
     * @param string $realMessage
     * @param string $fakeMessage
     */
    protected function _throwException($realMessage, $fakeMessage = '')
    {
        $e = Mage::exception('Mage_Core', $realMessage);
        Mage::logException($e);
        if (!$realMessage) {
            $fakeMessage = Mage::helper('enterprise_giftcardaccount')->__('Wrong gift card code.');
            $e->setMessage($fakeMessage);
        } else {
            $e->setMessage($realMessage);
        }
        throw $e;
    }
   
}
