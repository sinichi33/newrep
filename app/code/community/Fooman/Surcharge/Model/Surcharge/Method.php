<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Surcharge_Method extends Fooman_Surcharge_Model_Surcharge_Abstract
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
            $this->calculateAmount($settings, $surcharge);
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
        if ($surcharge->getCcSurchargeApplied()) {
            return false;
        }
        /*
        if ($quote->getBaseSubtotal() - $quote->getBaseDiscountAmount() < 0.0001) {
            return false;
        }*/
        if (parent::apply($settings, $surcharge, $quote, $address)) {
            $settings = $this->retrieveExtraSettings($settings, 'methods', true);
            $paymentPosted = $surcharge->getPaymentPosted();
            if (isset($paymentPosted['method']) && !empty($paymentPosted['method'])) {
                $data = $paymentPosted['method'];
            } else {
                $data = $quote->getPayment()->getMethod();
            }
            if (!empty($data)) {
                if (in_array($data, $settings['methods'])) {
                    return true;
                }
            }
        }
        return false;
    }
}
