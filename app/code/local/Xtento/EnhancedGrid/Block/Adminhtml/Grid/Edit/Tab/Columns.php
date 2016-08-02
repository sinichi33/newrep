<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2013-10-06T17:25:04+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Adminhtml/Grid/Edit/Tab/Columns.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Adminhtml_Grid_Edit_Tab_Columns extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $this->setTemplate('xtento/enhancedgrid/columns.phtml');
        return parent::_prepareForm();
    }

    protected function getColumns()
    {
        $model = Mage::registry('enhanced_grid_current_grid');
        $configuredColumns = $model->getConfiguredColumns();
        return $configuredColumns;
    }
}