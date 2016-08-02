<?php

/**
 * Product:       Xtento_CustomOrderNumber (1.0.6)
 * ID:            8xAf+oCns/QOEdaiZub3aLgVCGFua6nB8AAizsm8sRY=
 * Packaged:      2016-02-24T02:27:18+00:00
 * Last Modified: 2014-03-23T16:26:20+01:00
 * File:          app/code/local/Xtento/CustomOrderNumber/Model/System/Config/Source/Counter/Reset.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomOrderNumber_Model_System_Config_Source_Counter_Reset
{
    public function toOptionArray()
    {
        $options = array();
        $options[] = array('value' => '', 'label' => Mage::helper('xtento_customordernumber')->__('--- No automatic reset ---'));
        $options[] = array('value' => 'daily', 'label' => Mage::helper('xtento_customordernumber')->__('Reset every day'));
        $options[] = array('value' => 'monthly', 'label' => Mage::helper('xtento_customordernumber')->__('Reset every month'));
        $options[] = array('value' => 'yearly', 'label' => Mage::helper('xtento_customordernumber')->__('Reset every year'));

        return $options;
    }
}