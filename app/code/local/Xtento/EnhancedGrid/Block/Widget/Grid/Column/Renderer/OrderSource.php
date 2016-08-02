<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2015-08-26T16:48:27+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Widget/Grid/Column/Renderer/OrderSource.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_OrderSource extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options
{
    public function render(Varien_Object $row)
    {
        $paymentMethod = $row->getData('payment_method2');
        if ($paymentMethod == 'm2epropayment') {
            try {
                $order = Mage::getModel('sales/order')->load($row->getEntityId());
                if ($order->getId()) {
                    $payment = $order->getPayment();
                    if ($payment->getMethodInstance()) {
                        $additionalData = @unserialize($payment->getAdditionalData());
                        if (isset($additionalData["component_mode"])) {
                            $compMode = $additionalData["component_mode"];
                            $title = "";
                            switch ($compMode) {
                                case Ess_M2ePro_Helper_Component_Ebay::NICK:
                                    $title = 'eBay';
                                    break;
                                case Ess_M2ePro_Helper_Component_Amazon::NICK:
                                    $title = 'Amazon';
                                    break;
                                case Ess_M2ePro_Helper_Component_Buy::NICK:
                                    $title = 'Rakuten.com';
                                    break;
                                /*case Ess_M2ePro_Helper_Component_Play::NICK:
                                    $title = Ess_M2ePro_Helper_Component_Play::TITLE;
                                    break;*/
                            }
                            return $title;
                        }
                    }
                }
            } catch (Exception $e) {
                // Could not get payment method instance - probably payment module was removed.
                return "";
            }
        } else {
            return $this->__('Magento');
        }
    }
}