<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Surcharge_Product extends Fooman_Surcharge_Model_Surcharge_Abstract
{

    /**
     * calculate the surcharge
     *
     * @param                                  $type
     * @param Fooman_Surcharge_Model_Surcharge $surcharge
     * @param                                  $quote
     * @param                                  $address
     *
     * @return Fooman_Surcharge_Model_Surcharge|mixed
     */
    public function calculateSurcharge($type, Fooman_Surcharge_Model_Surcharge $surcharge, $quote, $address)
    {
        $settings = $this->retrieveCommonSettings($type, $surcharge->getStoreId());
        $settings = $this->retrieveExtraSettings($settings, 'type');

        if (parent::apply($settings, $surcharge, $quote, $address)) {
            $surchargeAmount = 0;
            $onceApplied = false;
            $multiplier = 1;

            /* @var $quote Mage_Sales_Model_Quote */
            $items = $quote->getAllItems();
            if (!empty($items)) {
                foreach ($items as $quoteItem) {
                    /* @var $quoteItem Mage_Sales_Model_Quote_Item */
                    $product = $quoteItem->getProduct()->load($quoteItem->getProduct()->getId());
                    if ($product->getFoomanProductSurcharge()) {
                        $parentMultiplier = 1;
                        if ($quoteItem->getParentItem()) {
                            $parentMultiplier = $quoteItem->getParentItem()->getQty();
                        }
                        if ($settings['type'] == Fooman_Surcharge_Model_System_ProductSurchargeTypes::PER_ITEM) {
                            $multiplier = $quoteItem->getQty() * $parentMultiplier;
                        }
                        if ($settings['type'] == Fooman_Surcharge_Model_System_ProductSurchargeTypes::PER_ORDER) {
                            $surchargeAmount = max(
                                $surchargeAmount, $product->getFoomanProductSurcharge() * $multiplier
                            );
                        } else {
                            $surchargeAmount += $product->getFoomanProductSurcharge() * $multiplier;
                        }
                        $onceApplied = true;
                    }
                }
                if ($onceApplied) {
                    $surcharge->addSurchargeAmount($surchargeAmount);
                    $surcharge->addSurchargeDescription($settings['description'], $surchargeAmount);
                }
            }
        }
        return $surcharge;
    }
}
