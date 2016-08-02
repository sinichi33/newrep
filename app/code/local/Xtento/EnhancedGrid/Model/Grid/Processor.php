<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2015-10-28T16:10:40+01:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/Grid/Processor.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_Grid_Processor
{
    public function processBlock($block, $reinitMode = false)
    {
        $gridType = Mage::helper('xtento_enhancedgrid')->getGridBlockType($block);
        // Process grid
        if ($gridType) {
            // Get grid configuration
            $gridConfiguration = false;
            if (!Mage::helper('xtento_enhancedgrid')->isMageExport()) {
                Mage::getSingleton('xtento_enhancedgrid/columns')->getAndSaveGridColumns($block, $gridType);
            }
            $gridConfigurationCollection = Mage::getModel('xtento_enhancedgrid/grid')->getCollection();
            $gridConfigurationCollection->addFieldToFilter('type', $gridType);
            $gridConfigurationCollection->addFieldToFilter('enabled', 1);
            foreach ($gridConfigurationCollection as $foundGridConfiguration) {
                if (in_array(implode("", Mage::getSingleton('admin/session')->getUser()->getRoles()), explode(",", $foundGridConfiguration->getRoleIds()))) {
                    $gridConfiguration = $foundGridConfiguration;
                    break 1;
                }
            }
            // Process columns
            if ($gridConfiguration !== false) {
                // First, check if grid has been rewritten and only if yes try to apply modifications
                if (!method_exists($block, 'xtUpdateColumn')) {
                    return $this;
                }
                $blockColumns = $block->getColumns();
                // Pre-process existing columns to avoid filtering problems
                if (!$reinitMode) {
                    foreach ($blockColumns as $column) {
                        if ($column->getIndex() && strstr($column->getFilterIndex(), '.') === false && !preg_match('/\(/', $column->getIndex()) && !in_array($column->getIndex(), explode(",", Mage::getStoreConfig('enhancedgrid/advanced/disable_filter_index')))) {
                            $column->setFilterIndex('main_table.' . $column->getIndex());
                        }
                    }
                }
                // Add custom columns
                $customColumns = Mage::getSingleton('xtento_enhancedgrid/columns_custom')->getCustomColumns($gridConfiguration->getType());
                $usedCustomColumns = array();
                $configuredColumns = $gridConfiguration->getConfiguredColumns();
                foreach ($configuredColumns as $columnIndex => $columnData) {
                    if ($columnIndex == 'massaction') {
                        continue;
                    }
                    if (!$columnData['is_visible']) {
                        if (isset($blockColumns[$columnIndex])) {
                            $block->xtRemoveColumn($columnIndex);
                        }
                    } else {
                        if (!isset($blockColumns[$columnIndex])) {
                            if (!$reinitMode) {
                                foreach ($columnData as $key => $value) {
                                    if (preg_match("/\_default$/", $key)) {
                                        unset($columnData[$key]);
                                    }
                                }
                                if ($columnIndex == 'purchased_items') {
                                    if (isset($columnData['filter_by']) && $columnData['filter_by'] == 'sku') {
                                        $columnData['filter_index'] = current(explode(".", $customColumns[$columnIndex]['filter_index'])) . '.sku';
                                    } else if (isset($columnData['filter_by']) && $columnData['filter_by'] == 'name') {
                                        $columnData['filter_index'] = current(explode(".", $customColumns[$columnIndex]['filter_index'])) . '.name';
                                    } else {
                                        $columnData['filter_index'] = current(explode(".", $customColumns[$columnIndex]['filter_index'])) . '.sku';
                                    }
                                }
                                if (preg_match('/price/i', $columnData['renderer'])) {
                                    if (!array_key_exists('currency', $columnData)) {
                                        $columnData['currency'] = 'base_currency_code';
                                    }
                                }
                                $block->addColumn($columnIndex, $columnData);
                                if (isset($customColumns[$columnIndex])) {
                                    $usedCustomColumns[$columnIndex] = $customColumns[$columnIndex];
                                }
                            }
                        } else {
                            foreach ($columnData as $key => &$value) {
                                if ($key == "filter" && $value === "") {
                                    $value = false;
                                }
                            }
                            $block->xtUpdateColumn($columnIndex, $columnData);
                        }
                    }
                }

                if (Mage::helper('xtento_enhancedgrid')->isMageExport()) {
                    $block->xtRemoveColumn("sagepay_transaction_state");
                    $block->xtRemoveColumn("combined-input");
                    $block->xtRemoveColumn("carrier-selector");
                    $block->xtRemoveColumn("tracking-input");
                    // "Disabled export columns"
                    foreach (explode(",", Mage::getStoreConfig('enhancedgrid/advanced/disable_export_index')) as $columnIndex) {
                        $block->xtRemoveColumn($columnIndex);
                    }
                }

                if (!$reinitMode) {
                    // Apply column sort order
                    $block->xtResetColumnsOrder();
                    $previousIndex = null;
                    foreach ($configuredColumns as $columnIndex => $columnData) {
                        if (isset($columnData['is_visible']) && $columnData['is_visible']) {
                            if (!is_null($previousIndex)) {
                                $block->addColumnsOrder($columnIndex, $previousIndex);
                            }
                            $previousIndex = $columnIndex;
                        }
                    }
                    #var_dump($block->getColumns());
                    $block->sortColumnsByOrder();

                    Mage::register('xtento_enhancedgrid_block_info', new Varien_Object(array('block' => $block, 'custom_columns' => $usedCustomColumns, 'grid_configuration' => $gridConfiguration)), true);
                    $block->xtPrepareCollection();
                    if (!Mage::helper('xtento_enhancedgrid')->isMageExport()) {
                        Mage::unregister('xtento_enhancedgrid_block_info');
                    }
                    #$block->xtCountCollection();
                }
            }
        }
        return $this;
    }
}