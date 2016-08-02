<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_Methods
{

    /**
     * retrieve list of installed payment methods
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
        $methods = Mage::getStoreConfig(Mage_Payment_Helper_Data::XML_PATH_PAYMENT_METHODS, $storeId);
        foreach ($methods as $code => $method) {
            $model = Mage::getStoreConfig(
                Mage_Payment_Helper_Data::XML_PATH_PAYMENT_METHODS . '/' . $code . '/model', $storeId
            );
            $method = Mage::getModel($model);
            //some none-core payment methods produce an error when asking for the title
            //catch the error here
            try {
                if ($method) {
                    if ($method->getTitle()) {
                        $returnArray[] = array(
                            'value' => $code, 'label' => $this->escapeHtmlByVersion($method->getTitle())
                        );
                    } else {
                        $returnArray[] = array('value' => $code, 'label' => $code);
                    }
                } else {
                    $returnArray[] = array('value' => $code, 'label' => $code);
                }
            } catch (Exception $e) {
                $returnArray[] = array('value' => $code, 'label' => $code);
            }
        }
        return $returnArray;
    }

    protected function escapeHtmlByVersion($string)
    {
        $helper = Mage::helper('core');
        if (method_exists($helper, 'escapeHtml')) {
            return $helper->escapeHtml($string);
        } else {
            return $helper->htmlEscape($string);
        }
    }
}
