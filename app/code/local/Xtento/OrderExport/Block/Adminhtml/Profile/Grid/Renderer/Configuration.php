<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2013-02-09T23:35:56+01:00
 * File:          app/code/local/Xtento/OrderExport/Block/Adminhtml/Profile/Grid/Renderer/Configuration.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Block_Adminhtml_Profile_Grid_Renderer_Configuration extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $configuration = array();
        $configuration['Cronjob Export'] = ($row->getCronjobEnabled()) ? Mage::helper('xtento_orderexport')->__('Enabled') : Mage::helper('xtento_orderexport')->__('Disabled');
        $configuration['Event Export'] = ($row->getEventObservers() !== '') ? Mage::helper('xtento_orderexport')->__('Enabled') : Mage::helper('xtento_orderexport')->__('Disabled');
        if (!empty($configuration)) {
            $configurationHtml = '';
            foreach ($configuration as $key => $value) {
                $configurationHtml .= Mage::helper('xtento_orderexport')->__($key).': <i>'.$value.'</i><br/>';
            }
            return $configurationHtml;
        } else {
            return '---';
        }
    }
}