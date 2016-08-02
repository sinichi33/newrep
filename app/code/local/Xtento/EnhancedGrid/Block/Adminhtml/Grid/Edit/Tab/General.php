<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-05-24T11:54:02+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Adminhtml/Grid/Edit/Tab/General.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Adminhtml_Grid_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('enhanced_grid_current_grid');
        // Set default values
        if (!$model->getId()) {
            $model->setEnabled(1);
        }

        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('xtento_enhancedgrid')->__('General Configuration'),
        ));

        if ($model->getId()) {
            $fieldset->addField('grid_id', 'hidden', array(
                'name' => 'grid_id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Configuration Name'),
            'name' => 'name',
            'required' => true,
        ));

        $type = $fieldset->addField('type', 'select', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Grid Type'),
            'name' => 'type',
            'options' => Mage::getSingleton('xtento_enhancedgrid/system_config_source_grid_type')->toOptionArray(),
            'required' => true,
            'note' => Mage::helper('xtento_enhancedgrid')->__('This setting can\'t be changed after creating a customized grid. Add a new grid for different grid types.')
        ));

        if (!Mage::registry('enhanced_grid_current_grid') || !Mage::registry('enhanced_grid_current_grid')->getId()) {
            $fieldset->addField('continue_button', 'note', array(
                'text' => $this->getChildHtml('continue_button'),
            ));
        } else {
            $fieldset->addField('role_ids', 'multiselect', array(
                'label' => 'Admin Roles',
                'name' => 'role_ids',
                'required' => true,
                'values' => Mage::getModel('xtento_enhancedgrid/system_config_source_admin_roles')->toOptionArray(),
                'note' => Mage::helper('xtento_enhancedgrid')->__('The customized grid will ONLY be visible to the selected admin roles. Select multiple by holding the CTRL key on your keyboard. <b>Attention:</b> You cannot assign one admin role to multiple grid configurations.')
            ));
            $fieldset->addField('enabled', 'select', array(
                'label' => Mage::helper('xtento_enhancedgrid')->__('Enabled'),
                'name' => 'enabled',
                'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
                'note' => Mage::helper('xtento_enhancedgrid')->__('If the grid configuration is not enabled, you will not see a customized grid.')
            ));

            if ($model->getId()) {
                // 1.3 Compatibility. Does not accept the disabled param directly in the addField array.
                $type->setDisabled(true);
            }

            $fieldset = $form->addFieldset('grid_fieldset', array(
                'legend' => Mage::helper('xtento_enhancedgrid')->__('Grid Configuration'),
            ));
            if ($model->getType() === Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER) {
                $fieldset->addField('hidden_status', 'multiselect', array(
                    'label' => Mage::helper('xtento_enhancedgrid')->__('Hidden Order Statuses'),
                    'name' => 'hidden_status',
                    'values' => array_merge_recursive(array(array('value' => '', 'label' => Mage::helper('xtento_enhancedgrid')->__('--- No hidden order statuses ---'))), Mage::getSingleton('xtento_enhancedgrid/system_config_source_order_status')->toOptionArray()),
                    'note' => $this->__('Selected order statuses will be hidden by default on the Sales > Orders grid for selected admin roles.')
                ));
            }
            $fieldset->addField('hidden_stores', 'multiselect', array(
                'label' => Mage::helper('xtento_enhancedgrid')->__('Hidden Stores'),
                'name' => 'hidden_stores',
                'values' => array_merge_recursive(array(array('value' => '', 'label' => Mage::helper('xtento_enhancedgrid')->__('--- No hidden stores ---'))), Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()),
                'note' => $this->__('Selected stores will be hidden by default on the Sales > Orders grid for selected admin roles.')
            ));
        }

        $form->setValues($model->getData());

        return parent::_prepareForm();
    }

    protected function _prepareLayout()
    {
        $this->setChild('continue_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label' => Mage::helper('catalog')->__('Continue'),
                'onclick' => "saveAndContinueEdit()",
                'class' => 'save'
            ))
        );
        return parent::_prepareLayout();
    }

    private function _getColumnConfigValue($columns, $column, $name, $default = false)
    {
        if (isset($columns[$column]) && array_key_exists($name, $columns[$column])) {
            return $columns[$column][$name];
        } else {
            return $default;
        }
    }
}