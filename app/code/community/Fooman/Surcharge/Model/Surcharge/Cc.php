<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Surcharge_Cc extends Fooman_Surcharge_Model_Surcharge_Abstract
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
        if (parent::apply($settings, $surcharge, $quote, $address)) {
            $settings = $this->retrieveExtraSettings($settings, 'cctypes', true);

            //There are credit card payment methods that do not use Mage_Payment_Model_Method_Cc
            $paymentMethod = $quote->getPayment()->getMethod();
            if (!empty($paymentMethod)
                && $quote->getPayment()->getMethodInstance() instanceof Mage_Payment_Model_Method_Cc
            ) {
                $paymentPosted = $surcharge->getPaymentPosted();
                if (isset($paymentPosted['cc_type']) && !empty($paymentPosted['cc_type'])) {
                    $data = $paymentPosted['cc_type'];
                } else {
                    $data = $quote->getPayment()->getCcType();
                }
                if (!empty($data)) {
                    if (in_array($data, $settings['cctypes'])) {
                        //card has been found add surcharge
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
