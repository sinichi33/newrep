<?php
/*
 * @author     Po
 * @package    Fooman_Surcharge
*/

class Fooman_Surcharge_Model_Surcharge_Storecode extends Fooman_Surcharge_Model_Surcharge_Abstract
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
        if ($this->apply($settings, $surcharge, $quote, $address)) {
            $this->calculateAmountWithQuote($settings, $surcharge, $quote);
        }
        return $surcharge;
    }

    /**
     * check if surcharge applies
     *
     * @param                                  $settings
     * @param Fooman_Surcharge_Model_Surcharge $surcharge
     * @param                                  $quote
     * @param null                             $address
     *
     * @return bool
     */
    public function apply($settings, Fooman_Surcharge_Model_Surcharge $surcharge, $quote, $address)
    {
        return parent::apply($settings, $surcharge, $quote, $address);
    }
    
    /**
     * calculate the surcharge amount
     *
     * @param $settings
     * @param $surcharge
     */
    public function calculateAmountWithQuote($settings, $surcharge, $quote)
    {	
	    $surchargeAmount = 0;
	    $items = $quote->getAllVisibleItems();
            if (!empty($items)) {
                foreach ($items as $quoteItem) { 
	                $itemStore = $quoteItem->getStoreCode();
	                $basePrice = $quoteItem->getProduct()->getPrice();
	                
	                $fee = Mage::getModel('shippingbeta/handlingrate')
	                ->load($itemStore, 'store_id');
					
                    // ICUBE Update - add condition is_price_zone
					if($fee->getPriceZone() && $quoteItem->getProduct()->getIsPriceZone()==1)
					{
						switch ($settings['handlingtype']) {
			            case Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_FIXED:
			            	$handlingFeeItem = floor($fee->getPriceZone() * $quoteItem->getQty());
			            	// ICUBe Update - set Handling fee each item
			            	$quoteItem->setHandlingFeeItem($handlingFeeItem);
			                $surchargeAmount += $handlingFeeItem;
			                break;
			            case Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_PERCENT:
			            	$handlingFeeItem = floor($basePrice * $fee->getPriceZone() / 100 * $quoteItem->getQty());
			            	// ICUBe Update - set Handling fee each item
			            	$quoteItem->setHandlingFeeItem($handlingFeeItem);
			                $surchargeAmount += $handlingFeeItem;
			                break;
						}
					}     

                }
            }
        // Original Code - if ($surchargeAmount != 0) {
        if ($surchargeAmount > 1) {
            $surcharge->setSurchargeApplied(true);
            $surcharge->addSurchargeAmount($surchargeAmount);
            $surcharge->addSurchargeDescription($settings['description'], $surchargeAmount);
        }
    }
    

}