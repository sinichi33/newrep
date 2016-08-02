<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Model_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Invoice
{
    protected function insertOrder(&$page, $order, $putOrderId = true)
    {
        parent::insertOrder($page, $order, $putOrderId);

        /* Add `Amasty Delivery Date` */
        if (Mage::helper('core')->isModuleEnabled('Amasty_Deliverydate')) {
            Mage::helper('amdeliverydate/pdf')->addDeliverydate($page, $order, $this);
        }

        /* Add `Amasty Order Attributes` */
        if (Mage::helper('core')->isModuleEnabled('Amasty_Orderattr')) {
            Mage::helper('amorderattr/pdf')->addAttrbutes($page, $order, $this);
        }
    }
}
