<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-02-27T21:24:13+01:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/Grid.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_Grid extends Mage_Core_Model_Abstract
{
    // Customizable grids
    const GRID_SALES_ORDER = 'sales_order';
    const GRID_SALES_INVOICE = 'sales_invoice';
    const GRID_SALES_SHIPMENT = 'sales_shipment';
    const GRID_SALES_CREDITMEMO = 'sales_creditmemo';

    protected function _construct()
    {
        parent::_construct();
        $this->_init('xtento_enhancedgrid/grid');
    }

    /**
     * @param $roleId
     * Load grid configuration by admin role ID
     */
    public function loadByRole($roleId)
    {
    }

    public function getTypes()
    {
        $values = array();
        $values[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER] = Mage::helper('xtento_enhancedgrid')->__('Sales > Orders');
        $values[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_INVOICE] = Mage::helper('xtento_enhancedgrid')->__('Sales > Invoices');
        $values[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_SHIPMENT] = Mage::helper('xtento_enhancedgrid')->__('Sales > Shipments');
        $values[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_CREDITMEMO] = Mage::helper('xtento_enhancedgrid')->__('Sales > Credit Memos');
        return $values;
    }

    public function getConfiguredColumns()
    {
        $gridColumnState = Mage::getModel('xtento_enhancedgrid/columns')->load($this->getType());
        $originalColumns = unserialize($gridColumnState->getColumns());
        $customColumns = Mage::getSingleton('xtento_enhancedgrid/columns_custom')->getCustomColumns();
        if (array_key_exists($this->getType(), $customColumns)) {
            foreach ($customColumns[$this->getType()] as $columnIndex => $columnData) {
                $originalColumns[$columnIndex] = $columnData;
            }
        }
        $configuredColumns = @unserialize($this->getConfiguration());
        if (!$configuredColumns || empty($configuredColumns)) {
            $configuredColumns = $originalColumns;
            foreach ($configuredColumns as $columnIndex => $columnData) {
                foreach ($columnData as $key => $value) {
                    $configuredColumns[$columnIndex][$key . '_default'] = $value;
                }
            }
        } else {
            // Merge original and current configuration
            if (is_array($originalColumns)) {
                // Add columns that were added to the grid but aren't in the configuraiton
                foreach ($originalColumns as $columnIndex => $originalColumnData) {
                    if (!isset($configuredColumns[$columnIndex])) {
                        $configuredColumns[$columnIndex] = $originalColumnData;
                    }
                    // Save original value
                    if (array_key_exists($columnIndex, $originalColumns)) {
                        foreach ($originalColumns[$columnIndex] as $key => $value) {
                            if (array_key_exists($key, $configuredColumns[$columnIndex])) {
                                #if ($configuredColumns[$columnIndex][$key] != $originalColumns[$columnIndex][$key]) {
                                $configuredColumns[$columnIndex][$key . '_default'] = $value;
                                #}
                            } else {
                                $configuredColumns[$columnIndex][$key] = $value;
                            }
                        }
                    }
                }
                // Remove columns that were removed from the grid
                foreach ($configuredColumns as $columnIndex => $columnData) {
                    if (!isset($originalColumns[$columnIndex])) {
                        #unset($configuredColumns[$columnIndex]);
                    }
                }
            }
        }
        #var_dump($configuredColumns); die();
        return $configuredColumns;
    }
}