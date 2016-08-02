<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2013-10-30T16:25:26+01:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Adminhtml/Grid/Edit/Tabs.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Adminhtml_Grid_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('grid_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('xtento_enhancedgrid')->__('Grid Customization'));

    }

    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('General Configuration'),
            'title' => Mage::helper('xtento_enhancedgrid')->__('General Configuration'),
            'content' => $this->getLayout()->createBlock('xtento_enhancedgrid/adminhtml_grid_edit_tab_general')->toHtml(),
        ));

        if (!Mage::registry('enhanced_grid_current_grid') || !Mage::registry('enhanced_grid_current_grid')->getId()) {
            // We just want to display the "General" tab until the first save
            return parent::_beforeToHtml();
        }

        $this->addTab('columns', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Grid Columns'),
            'title' => Mage::helper('xtento_enhancedgrid')->__('Grid Columns'),
            'content' => $this->getLayout()->createBlock('xtento_enhancedgrid/adminhtml_grid_edit_tab_columns')->toHtml(),
        ));

        $this->addTab('column_configuration', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Column Configuration'),
            'title' => Mage::helper('xtento_enhancedgrid')->__('Column Configuration'),
            'content' => $this->getLayout()->createBlock('xtento_enhancedgrid/adminhtml_grid_edit_tab_columnconfig')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}