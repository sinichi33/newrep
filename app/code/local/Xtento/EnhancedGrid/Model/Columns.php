<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-08-11T14:11:55+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/Columns.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_Columns extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('xtento_enhancedgrid/columns');
    }

    public function getRenderers()
    {
        $values = array(
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action' => Mage::helper('xtento_enhancedgrid')->__('Action'),
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Checkbox' => Mage::helper('xtento_enhancedgrid')->__('Checkbox'),
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Concat' => Mage::helper('xtento_enhancedgrid')->__('Concat'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Country' => Mage::helper('xtento_enhancedgrid')->__('Country'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency' => Mage::helper('xtento_enhancedgrid')->__('Currency'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Date' => Mage::helper('xtento_enhancedgrid')->__('Date'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime' => Mage::helper('xtento_enhancedgrid')->__('Date + Time'),
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input' => Mage::helper('xtento_enhancedgrid')->__('Input'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Ip' => Mage::helper('xtento_enhancedgrid')->__('IP'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Longtext' => Mage::helper('xtento_enhancedgrid')->__('Long Text'),
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Massaction' => Mage::helper('xtento_enhancedgrid')->__('Massaction'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Number' => Mage::helper('xtento_enhancedgrid')->__('Number'),
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options' => Mage::helper('xtento_enhancedgrid')->__('Options'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Price' => Mage::helper('xtento_enhancedgrid')->__('Price'),
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Radio' => Mage::helper('xtento_enhancedgrid')->__('Radio'),
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Select' => Mage::helper('xtento_enhancedgrid')->__('Select'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Store' => Mage::helper('xtento_enhancedgrid')->__('Store'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text' => Mage::helper('xtento_enhancedgrid')->__('Text'),
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Theme' => Mage::helper('xtento_enhancedgrid')->__('Theme'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Wrapline' => Mage::helper('xtento_enhancedgrid')->__('Wrapline'),
        );
        return $values;
    }

    public function getFilters()
    {
        $values = array(
            'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Checkbox' => Mage::helper('xtento_enhancedgrid')->__('Checkbox'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Country' => Mage::helper('xtento_enhancedgrid')->__('Country'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Date' => Mage::helper('xtento_enhancedgrid')->__('Date'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Datetime' => Mage::helper('xtento_enhancedgrid')->__('Date + Time'),
            #'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Price' => Mage::helper('xtento_enhancedgrid')->__('Price'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Radio' => Mage::helper('xtento_enhancedgrid')->__('Radio'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range' => Mage::helper('xtento_enhancedgrid')->__('Range'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select' => Mage::helper('xtento_enhancedgrid')->__('Select'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Store' => Mage::helper('xtento_enhancedgrid')->__('Store'),
            'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Text' => Mage::helper('xtento_enhancedgrid')->__('Text'),
        );
        return $values;
    }

    /**
     * @param $gridModel
     * @param $gridState
     * @param $newColumns
     * @return mixed
     *
     * Merge old and new grid column configuration when the grid columns are modified by the user in the admin panel
     */
    public function mergeGridColumns($gridModel, $gridState, $newColumns)
    {
        if (!$gridModel->getConfiguration()) {
            $gridColumnState = Mage::getModel('xtento_enhancedgrid/columns')->load($gridState->getType());
            $gridColumns = unserialize($gridColumnState->getColumns());
        } else {
            $gridColumns = unserialize($gridState->getConfiguration());
        }
        $customColumns = Mage::getSingleton('xtento_enhancedgrid/columns_custom')->getCustomColumns();
        if (array_key_exists($gridState->getType(), $customColumns)) {
            foreach ($customColumns[$gridState->getType()] as $columnIndex => $columnData) {
                $gridColumns[$columnIndex] = $columnData;
            }
        }
        foreach ($newColumns as $columnIndex => $columnData) {
            if (isset($gridColumns[$columnIndex])) {
                foreach ($columnData as $key => $value) {
                    if (array_key_exists($key, $gridColumns[$columnIndex])) {
                        if ($gridColumns[$columnIndex][$key] != $value) {
                            $gridColumns[$columnIndex][$key] = $value;
                        }
                    } else {
                        $gridColumns[$columnIndex][$key] = $value;
                    }
                }
            }
        }
        foreach ($gridColumns as $columnIndex => $columnData) {
            foreach ($columnData as $key => $value) {
                if ($key == 'is_visible') {
                    if (isset($newColumns[$columnIndex]['is_visible'])) {
                        $gridColumns[$columnIndex][$key] = 1;
                    } else {
                        $gridColumns[$columnIndex][$key] = 0;
                    }
                }
            }
        }
        // Sort columns
        uasort($gridColumns, array($this, '_sortColumns'));
        return $gridColumns;
    }

    /**
     * @param $grid
     * @param $gridType
     *
     * Get columns from existing grid block
     */
    public function getAndSaveGridColumns($grid, $gridType)
    {

        // Fetch grid columns
        $sortOrder = 0;
        $columns = array();
        $columnIds = array();
        $columnIndexes = array();

        // Get columns from block
        foreach ($grid->getColumns() as $column) {
            $sortOrder++;
            $columns[$column->getId()] = array(
                'id' => $column->getId(),
                'index' => $column->getIndex(),
                'width' => $column->getWidth(),
                'align' => $column->getAlign(),
                'header' => $column->getHeader(),
                'type' => $column->getType(),
                'renderer' => ($column->getRenderer()) ? get_class($column->getRenderer()) : '',
                'filter' => ($column->getFilter()) ? get_class($column->getFilter()) : '',
                'sort_order' => $sortOrder * 10,
                'origin' => 'grid',
                'is_visible' => 1,
                'is_system' => ($column->getIsSystem() ? 1 : 0),
            );
            $columnIndexes[] = $column->getIndex();
            $columnIds[] = $column->getId();
        }

        // Get columns from collection not yet added but available
        if ($grid->getCollection() && $grid->getCollection()->count() > 0) {
            $collectionItem = $grid->getCollection()->getFirstItem();
            foreach ($collectionItem->getData() as $key => $value) {
                if (!in_array($key, $columnIndexes, true) && !in_array($key, $columnIds, true) && (is_scalar($value) || is_null($value))) {
                    $sortOrder++;
                    $columns[$key] = array(
                        'id' => $key,
                        'index' => $key,
                        'width' => '',
                        'align' => 'left',
                        'header' => ucwords(str_replace('_', ' ', $key)),
                        'type' => '',
                        'renderer' => '',
                        'filter' => '',
                        'sort_order' => $sortOrder * 10,
                        'origin' => 'collection',
                        'is_visible' => 0,
                        'is_system' => 0,
                    );
                }
            }
        }

        // Hide virtual/filtering column SKU/Product Name
        if (isset($columns['sku'])) {
            unset($columns['sku']);
        }
        if (isset($columns['name'])) {
            unset($columns['name']);
        }

        // Sort columns
        uasort($columns, array($this, '_sortColumns'));

        // Save grid columns
        $gridColumns = Mage::getModel('xtento_enhancedgrid/columns')->load($gridType);
        if (!$gridColumns->getType()) {
            $gridColumns->setType($gridType);
        }
        $gridColumns->setColumns(serialize($columns))->save();
    }

    private function _sortColumns($a, $b)
    {
        return ($a['sort_order'] < $b['sort_order'] ? -1 : ($a['sort_order'] > $b['sort_order'] ? 1 : strcmp($a['header'], $b['header'])));
    }
}