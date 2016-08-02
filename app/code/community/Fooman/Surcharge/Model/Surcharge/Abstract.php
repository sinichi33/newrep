<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

abstract class Fooman_Surcharge_Model_Surcharge_Abstract
{
    /**
     * calculate the surcharge
     *
     * @param                                  $type
     * @param Fooman_Surcharge_Model_Surcharge $surcharge
     * @param                                  $quote
     * @param                                  $address
     *
     * @return mixed
     */
    abstract public function calculateSurcharge($type, Fooman_Surcharge_Model_Surcharge $surcharge, $quote, $address);

    /**
     * retrieve basic settings for surcharge type
     *
     * @param $surchargeType
     * @param $storeId
     *
     * @return array
     */
    public function retrieveCommonSettings($surchargeType, $storeId)
    {
        $configPath = sprintf('surcharge/%s/', $surchargeType);
        $settingsArray = array();
        $settingsArray['type'] = $surchargeType;
        $settingsArray['store_id'] = $storeId;
        $settingsArray['enabled'] = Mage::getStoreConfig($configPath . 'enabled', $storeId);

        $commonSettings = array(
            'handlingtype', 'applygroupfilter', 'applycountryfilter', 'fixed', 'rate', 'description',
            'maxsurchargevalue', 'minsurchargevalue'
        );
        $settingsArray = $this->retrieveExtraSettings($settingsArray, $commonSettings);

        //if we apply filters load the associated information
        if (isset($settingsArray['applygroupfilter']) && $settingsArray['applygroupfilter']) {
            $settingsArray = $this->retrieveExtraSettings($settingsArray, 'group', true);
        }
        if (isset($settingsArray['applycountryfilter']) && $settingsArray['applycountryfilter']) {
            $settingsArray = $this->retrieveExtraSettings($settingsArray, 'countries', true);
        }

        return $settingsArray;
    }

    /**
     * load additional settings and merge into existing
     * if surcharge is enabled
     *
     * @param array        $settings
     * @param string|array $settingsToAdd
     * @param bool         $explode
     *
     * @return array
     */
    public function retrieveExtraSettings(array $settings, $settingsToAdd, $explode = false)
    {
        if ($settings['enabled']) {
            $configPath = sprintf('surcharge/%s/', $settings['type']);
            if (is_array($settingsToAdd)) {
                foreach ($settingsToAdd as $configKey) {
                    $settings[$configKey] = Mage::getStoreConfig($configPath . $configKey, $settings['store_id']);
                    if ($explode) {
                        $settings[$configKey] = explode(',', $settings[$configKey]);
                    }
                }
            } else {
                $settings[$settingsToAdd] = Mage::getStoreConfig($configPath . $settingsToAdd, $settings['store_id']);
                if ($explode) {
                    $settings[$settingsToAdd] = explode(',', $settings[$settingsToAdd]);
                }
            }
        }
        return $settings;
    }

    /**
     * determine if surcharge should be applied
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
        if (!$settings['enabled']) {
            return false;
        }
        if (!$this->_isSurchargeWithinAmounts($settings, $surcharge)) {
            return false;
        }
        if (!$this->_isSurchargeApplicableForCountry($settings, $quote, $address)) {
            return false;
        }
        if (!$this->_isSurchargeApplicableForGroup($settings, $quote)) {
            return false;
        }
        if (!$this->_isSurchargeApplicableForProducts($surcharge, $quote)) {
            return false;
        }

        return true;
    }

    /**
     * calculate the surcharge amount
     *
     * @param $settings
     * @param $surcharge
     */
    public function calculateAmount($settings, $surcharge)
    {
        $surchargeAmount = 0;
        $surchargeBasis = $surcharge->getOverrideSurchargeBasis()
            ? $surcharge->getOverrideSurchargeBasis()
            : $surcharge->getSurchargeBasis();
        $surchargeBasis -= $surcharge->getBasisDeductions();
        switch ($settings['handlingtype']) {
            case Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_FIXED:
                $surchargeAmount = $settings['fixed'];
                break;
            case Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_PERCENT:
                $surchargeAmount = $surchargeBasis * $settings['rate'] / 100;
                break;
            case Fooman_Surcharge_Model_System_HandlingTypes::HANDLING_TYPE_COMBINED:
                $surchargeAmount = ($surchargeBasis * $settings['rate'] / 100) + $settings['fixed'];
                break;
            case Fooman_Surcharge_Model_System_HandlingTypes::HANDLING_TYPE_MIN:
                $surchargeAmount = $surchargeBasis * $settings['rate'] / 100;
                if ($surchargeAmount < $settings['fixed']) {
                    $surchargeAmount = $settings['fixed'];
                }
                break;
        }
        if ($surchargeAmount != 0) {
            $surcharge->setSurchargeApplied(true);
            $surcharge->addSurchargeAmount($surchargeAmount);
            $surcharge->addSurchargeDescription($settings['description'], $surchargeAmount);
        }
    }

    /**
     * Determine if amount surcharge is based on is within the Minimum and Maximums
     *
     * @param                                  $settings
     * @param Fooman_Surcharge_Model_Surcharge $surcharge
     *
     * @internal param $surchargeType
     * @internal param $storeId
     * @internal param $surchargeBasedOn
     *
     * @return bool
     */
    protected function _isSurchargeWithinAmounts($settings, Fooman_Surcharge_Model_Surcharge $surcharge)
    {
        $max = $settings['maxsurchargevalue'];
        $min = $settings['minsurchargevalue'];

        if (strlen($max)) {
            if ($surcharge->getSurchargeBasis() >= $max) {
                return false;
            }
        }
        if (strlen($min) && $min > 0) {
            if ($surcharge->getSurchargeBasis() <= $min) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check result of country filter for surcharge
     *
     * @param $settings
     * @param $quote
     *
     * @param $address
     *
     * @return bool
     */
    protected function _isSurchargeApplicableForCountry($settings, $quote, $address)
    {
        $countryFilter = $settings['applycountryfilter'];

        if ($address instanceof Mage_Customer_Model_Address_Abstract && $countryFilter > 0) {
            $negate = false;
            $processThisAddress = false;
            $countries = $settings['countries'];
            switch ($countryFilter) {
                case Fooman_Surcharge_Model_System_SurchargeAddressTypes::SURCHARGE_ON_SHIPPING:
                    $negate = false;
                    $effectiveAddress = $quote->getShippingAddress();
                    if ($address->getAddressType() == 'shipping') {
                        $processThisAddress = true;
                    }
                    break;
                case Fooman_Surcharge_Model_System_SurchargeAddressTypes::SURCHARGE_ON_BILLING;
                    $negate = false;
                    $effectiveAddress = $quote->getBillingAddress();
                    if ($address->getAddressType() == 'billing') {
                        $processThisAddress = true;
                    }
                    break;
                case Fooman_Surcharge_Model_System_SurchargeAddressTypes::NO_SURCHARGE_ON_BILLING;
                    $negate = true;
                    $effectiveAddress = $quote->getBillingAddress();
                    if ($address->getAddressType() == 'billing') {
                        $processThisAddress = true;
                    }
                    break;
                case Fooman_Surcharge_Model_System_SurchargeAddressTypes::NO_SURCHARGE_ON_SHIPPING;
                    $negate = true;
                    $effectiveAddress = $quote->getShippingAddress();
                    if ($address->getAddressType() == 'shipping') {
                        $processThisAddress = true;
                    }
                    break;
            }
            if ($quote->getIsMultiShipping()) {
                $effectiveAddress = $address;
            } else {
                $processThisAddress = true;
            }
            if ($processThisAddress) {
                if ($negate) {
                    return !in_array($effectiveAddress->getCountry(), $countries);
                } else {
                    return in_array($effectiveAddress->getCountry(), $countries);
                }
            } else {
                return false;
            }
        } else {
            //no country filtering active = always applicable
            return true;
        }
    }

    /**
     * determine if surcharge is applicable for currently active user group
     *
     * @param $settings
     * @param $quote
     *
     * @return bool
     */
    protected function _isSurchargeApplicableForGroup($settings, $quote)
    {
        if ($settings['applygroupfilter']) {
            if (in_array($quote->getCustomerGroupId(), $settings['group'])) {
                $apply = true;
            } else {
                $apply = false;
            }
        } else {
            $apply = true;
        }
        return $apply;
    }

    /**
     * determine if surcharge is applicable for current products in quote
     *
     * @param Fooman_Surcharge_Model_Surcharge $surcharge
     * @param                                  $quote
     *
     * @return mixed
     */
    protected function _isSurchargeApplicableForProducts(Fooman_Surcharge_Model_Surcharge $surcharge, $quote)
    {
        if (is_null($surcharge->getExemptResult())) {
            $amountToDeduct = 0;
            /* @var $quote Mage_Sales_Model_Quote */
            $items = $quote->getAllItems();
            if (!empty($items)) {
                foreach ($items as $quoteItem) {
                    /* @var $quoteItem Mage_Sales_Model_Quote_Item */
                    $product = $quoteItem->getProduct()->load($quoteItem->getProduct()->getId());
                    if ($product->getFoomanOrderNoSurcharge()) {
                        $surcharge->setExemptResult(false);
                        return $surcharge->getExemptResult();
                    }
                    if ($product->getFoomanSurchargeExcludeProd()) {
                        $amountToDeduct += $quoteItem->getBaseRowTotalInclTax()
                            ? $quoteItem->getBaseRowTotalInclTax()
                            : $quoteItem->getBaseRowTotal() + $quoteItem->getBaseTaxAmount();
                    }
                }
            }
            $surcharge->setBasisDeductions(
                $surcharge->getBasisDeductions() + $amountToDeduct
            );
        }
        $surcharge->setExemptResult(true);
        return $surcharge->getExemptResult();
    }

}
