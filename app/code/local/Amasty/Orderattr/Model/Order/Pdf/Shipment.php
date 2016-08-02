<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Model_Order_Pdf_Shipment extends Mage_Sales_Model_Order_Pdf_Shipment
{
    protected function insertOrder(&$page, $shipment, $putOrderId = true)
    {
        parent::insertOrder($page, $shipment, $putOrderId);

        $order = $shipment->getOrder();

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
