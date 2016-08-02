<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_ShippingMethods
{

    /**
     * create a shipping request from origin to obtain list
     * of installed shipping methods
     *
     * @return array
     */
    public function toOptionArray()
    {
        $storeName = Mage::app()->getRequest()->getParam('store');
        if ($storeName) {
            $storeId = Mage::app()->getStore($storeName)->getId();
        } else {
            $storeId = Mage::app()->getStore()->getId();
        }
        $returnArray = array();
        $carriers = Mage::getModel('shipping/config')->getAllCarriers($storeId);
        if ($carriers) {
            foreach ($carriers as $code => $carrier) {
                try {
                    if ($carrier) {
                        $methods = $carrier->getAllowedMethods();
                        $carrierMethods = array();
                        $carrierName = $carrier->getConfigData('title');
                        if (empty($carrierName)) {
                            $carrierName = $carrier->getId();
                        }
                        foreach ($methods as $methodCode=>$name) {
                            if (empty($name)) {
                                $name = $methodCode;
                            }
                            $carrierMethods[] = array(
                                'value' => $code . '_' . $methodCode,
                                'label' => $name
                            );
                        }
                        $returnArray[] = array('value' => $carrierMethods, 'label' => $carrierName);
                    }
                } catch (Exception $e) {
                    $returnArray[] = array('value' => $code, 'label' => $code);
                }
            }
        }

        return $returnArray;
    }
}