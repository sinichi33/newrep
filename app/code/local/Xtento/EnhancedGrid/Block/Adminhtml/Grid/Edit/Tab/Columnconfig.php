<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-08-25T21:36:36+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Adminhtml/Grid/Edit/Tab/Columnconfig.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Adminhtml_Grid_Edit_Tab_Columnconfig extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('enhanced_grid_current_grid');
        $configuredColumns = $model->getConfiguredColumns();

        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('purchased_items', array(
            'legend' => Mage::helper('xtento_enhancedgrid')->__('Column: Purchased Items'),
        ));

        $fieldset->addField('show_custom_options', 'select', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Show custom options'),
            'name' => 'columns[purchased_items][show_custom_options]',
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => $this->_getColumnConfigValue($configuredColumns, 'purchased_items', 'show_custom_options', true)
        ));

        $fieldset->addField('show_order_currency', 'select', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Show prices in order currency'),
            'name' => 'columns[purchased_items][show_order_currency]',
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => $this->_getColumnConfigValue($configuredColumns, 'purchased_items', 'show_order_currency', true),
            'note' => $this->__('If set to "No", prices in the "Purchased Items" table will be shown in the base currency of Magento')
        ));

        $fieldset->addField('show_thumbnail', 'select', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Show product thumbnail image'),
            'name' => 'columns[purchased_items][show_thumbnail]',
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => $this->_getColumnConfigValue($configuredColumns, 'purchased_items', 'show_thumbnail', true)
        ));

        $fieldset->addField('truncate_items', 'text', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Show only X items'),
            'name' => 'columns[purchased_items][truncate_items]',
            'value' => $this->_getColumnConfigValue($configuredColumns, 'purchased_items', 'truncate_items', '999'),
            'note' => $this->__('Show X items and hide the others by default; hover of the column to show more items')
        ));

        $fieldset->addField('filter_by', 'select', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Search/filter by'),
            'name' => 'columns[purchased_items][filter_by]',
            'values' => array(
                array('value' => 'sku', 'label' => Mage::helper('xtento_enhancedgrid')->__('SKU')),
                array('value' => 'name', 'label' => Mage::helper('xtento_enhancedgrid')->__('Product Name')),
            ),
            'value' => $this->_getColumnConfigValue($configuredColumns, 'purchased_items', 'filter_by', 'sku'),
            'note' => $this->__('If you try to search in the "Purchased Items" column, you can either have the search done based on product SKUs or product names')
        ));

        $availableAttributes = array(array('value' => '', 'label' => Mage::helper('xtento_enhancedgrid')->__('--- No attribute selected ---')));
        $productAttributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->setOrder('main_table.frontend_label', 'asc')
            ->load();
        foreach ($productAttributes as $productAttribute) {
            if ($productAttribute->getFrontendLabel()) {
                $availableAttributes[] = array('label' => sprintf("%s [%s]", $productAttribute->getFrontendLabel(), $productAttribute->getAttributeCode()), 'value' => $productAttribute->getAttributeCode());
            }
        }
        $fieldset->addField('attributes_to_show', 'multiselect', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Product attributes to show'),
            'name' => 'columns[purchased_items][attributes_to_show]',
            'values' => $availableAttributes,
            'value' => $this->_getColumnConfigValue($configuredColumns, 'purchased_items', 'attributes_to_show', ''),
            'note' => Mage::helper('xtento_enhancedgrid')->__('The selected product attributes will be shown in the "Purchased Items" column. Select multiple attributes using the CTRL/SHIFT buttons on your keyboard.'),
            'style' => 'width: auto; max-width: 500px;'
        ));

        $fieldset = $form->addFieldset('comment_history', array(
            'legend' => Mage::helper('xtento_enhancedgrid')->__('Column: Comment History'),
        ));

        $fieldset->addField('truncate_chars', 'text', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Truncate after X characters'),
            'name' => 'columns[comment_history][truncate_chars]',
            'value' => $this->_getColumnConfigValue($configuredColumns, 'comment_history', 'truncate_chars', '130'),
            'note' => $this->__('Show X characters and truncate the comment history after that')
        ));

        $fieldset = $form->addFieldset('payment_method', array(
            'legend' => Mage::helper('xtento_enhancedgrid')->__('Column: Payment Method'),
        ));

        $fieldset->addField('hide_disabled_methods', 'select', array(
            'label' => Mage::helper('xtento_enhancedgrid')->__('Hide disabled payment methods'),
            'name' => 'columns[payment_method][hide_disabled_methods]',
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value' => $this->_getColumnConfigValue($configuredColumns, 'payment_method', 'hide_disabled_methods', true),
            'note' => $this->__('Show only enabled payment methods in the "Payment Method" dropdown?')
        ));

        return parent::_prepareForm();
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