<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-06-13T17:00:50+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Widget/Grid/Column/Filter/PaymentMethod.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Widget_Grid_Column_Filter_PaymentMethod extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select
{
    protected function _getOptions()
    {
        $options = array(array('value' => null, 'label' => ''));

        $allActivePaymentMethods = Mage::getModel('payment/config')->getActiveMethods();
        $paymentMethods = Mage::helper('xtcore/payment')->getPaymentMethodList(true, false, false, null, false);
        foreach ($paymentMethods as $code => $title) {
            if ($this->getColumn()->getHideDisabledMethods() && !Mage::getStoreConfigFlag('payment/' . $code . '/active')) {
                continue;
            }
            if ($this->getColumn()->getHideDisabledMethods() && isset($allActivePaymentMethods[$code]) && is_object($allActivePaymentMethods[$code]) && !$allActivePaymentMethods[$code]->canUseCheckout() && !$allActivePaymentMethods[$code]->canUseInternal()) {
                continue;
            }
            $options[] = array('value' => $code, 'label' => $title);
        }
        return $options;
    }
}
