<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-05-22T18:23:14+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Adminhtml/Grid/Edit.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Adminhtml_Grid_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'xtento_enhancedgrid';
        $this->_controller = 'adminhtml_grid';

        if (Mage::registry('enhanced_grid_current_grid')->getId()) {
            $this->_addButton('duplicate_button', array(
                'label' => Mage::helper('xtento_enhancedgrid')->__('Duplicate Configuration'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/duplicate', array('_current' => true)) . '\')',
                'class' => 'add',
            ), 0);

            $this->_updateButton('save', 'label', Mage::helper('xtento_enhancedgrid')->__('Save Configuration'));
            $this->_updateButton('delete', 'label', Mage::helper('xtento_enhancedgrid')->__('Delete Configuration'));
            $this->_removeButton('reset');
        } else {
            $this->_removeButton('save');
        }

        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                if (editForm && editForm.validator.validate()) {
                    Element.show('loading-mask');
                    setLoaderPosition();
                    var tabsIdValue = grid_tabsJsTabs.activeTab.id;
                    var tabsBlockPrefix = 'grid_tabs_';
                    if (tabsIdValue.startsWith(tabsBlockPrefix)) {
                        tabsIdValue = tabsIdValue.substr(tabsBlockPrefix.length)
                    }
                }
                if (!$('edit_form').action.match(/\/key\//)) {
                    editForm.submit($('edit_form').action+'continue/edit/active_tab/'+tabsIdValue);
                } else {
                    editForm.submit($('edit_form').action.replace(/\/key\//, '/continue/edit/active_tab/'+tabsIdValue+'/key/')); // key must be last parameter
                }
            }
            varienGlobalEvents.attachEventHandler('formSubmit', function(){
                if (editForm && editForm.validator.validate()) {
                    Element.show('loading-mask');
                    setLoaderPosition();
                }
                if (typeof columnTable !== 'undefined' && ('fnFilter' in columnTable)) {
                    columnTable.fnFilter('');
                }
            });
        ";

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);
    }

    public function getHeaderText()
    {
        if (Mage::registry('enhanced_grid_current_grid')->getId()) {
            return Mage::helper('xtento_enhancedgrid')->__('Edit %s Grid \'%s\'', ucwords(str_replace('_', ' ', Mage::registry('enhanced_grid_current_grid')->getType())), Mage::helper('xtcore/core')->escapeHtml(Mage::registry('enhanced_grid_current_grid')->getName()));
        } else {
            return Mage::helper('xtento_enhancedgrid')->__('New Grid');
        }
    }
}