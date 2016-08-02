<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2015-11-05T13:32:45+01:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/Grid/Rewrite.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_Grid_Rewrite extends Mage_Core_Model_Abstract
{
    /**
     * Rewrite grid blocks and collection models in real time
     */
    public function rewriteGrids()
    {
        $request = Mage::app()->getRequest();
        $module = 'adminhtml';
        $collectionModule = 'sales_mysql4';
        $blockName = false;
        $collectionName = false;
        $newClass = false;
        $newModelClass = false;
        $itemEntityName = false;
        $itemParentIdField = false;
        $rewriteClass = true;

        // Handle each grid separately
        // Sales > Orders
        if (Mage::helper('xtento_enhancedgrid')->getController($request) == Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER) {
            $blockName = 'sales_order_grid';
            $collectionName = 'order_grid_collection';
            $itemEntityName = 'order_item';
            $itemParentIdField = 'order_id';
            if (in_array($request->getControllerName(), array('sales_archive'))) {
                // Sales > Orders Archive (EE only)
                $blockName = 'adminhtml_sales_archive_order_grid';
                $collectionName = 'order_collection';
                $newModelClass = 'Xtento_EnhancedGrid_Model_Sales_Enterprise_Salesarchive_Order_Collection';
                $newClass = 'Xtento_EnhancedGrid_Block_Rewrite_Sales_Enterprise_Sales_Archive_Order_Grid';
                $module = 'enterprise_salesarchive';
                $collectionModule = 'enterprise_salesarchive_resource';
            }
        }
        // Sales > Invoices
        if (Mage::helper('xtento_enhancedgrid')->getController($request) == Xtento_EnhancedGrid_Model_Grid::GRID_SALES_INVOICE) {
            $blockName = 'sales_invoice_grid';
            $collectionName = 'order_invoice_grid_collection';
            $itemEntityName = 'invoice_item';
            $itemParentIdField = 'parent_id';
        }
        // Sales > Shipments
        if (Mage::helper('xtento_enhancedgrid')->getController($request) == Xtento_EnhancedGrid_Model_Grid::GRID_SALES_SHIPMENT) {
            $blockName = 'sales_shipment_grid';
            $collectionName = 'order_shipment_grid_collection';
            $itemEntityName = 'shipment_item';
            $itemParentIdField = 'parent_id';
        }
        // Sales > Credit Memos
        if (Mage::helper('xtento_enhancedgrid')->getController($request) == Xtento_EnhancedGrid_Model_Grid::GRID_SALES_CREDITMEMO) {
            $blockName = 'sales_creditmemo_grid';
            $collectionName = 'order_creditmemo_grid_collection';
            $itemEntityName = 'creditmemo_item';
            $itemParentIdField = 'parent_id';
        }
        // Customer > View > Orders tab, registering the rewritten order_collection is required in some cases
        if ($request->getControllerName() == 'customer' && ($request->getActionName() == 'orders' || $request->getActionName() == 'lastOrders')) {
            $rewriteClass = false;
            $collectionName = 'order_grid_collection';
        }

        // Field mapping for filters, output in $classCode
        $filterMappedFields = 'array("entity_id", "store_id", "created_at", "updated_at", "customer_id", "status", "order_currency_code", "base_currency_code", "total_paid", "base_total_paid", "store_name", "increment_id")';

        // Rewrite collection
        if ($collectionName !== false) {
            // Get new class name, if none
            if (!$newModelClass) {
                $newModelClass = 'Xtento_EnhancedGrid_Model_Sales_' . str_replace(' ', '_', ucwords(str_replace('_', ' ', $collectionName)));
            }

            $classCode = '
            class NewClass extends ExtendsClass
            {
                protected function _construct() {
                    parent::_construct();
                    $filterMapFields = '.$filterMappedFields.';
                    foreach ($filterMapFields as $field) {
                        $this->addFilterToMap($field, "main_table." . $field);
                    }
                    return $this;
                }

                public function getSize() {
                    $countSelect = $this->getSelectCountSql();
                    $countSelect->reset(Zend_Db_Select::ORDER);
                    $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
                    $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);

                    if(count($this->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0) {
                        $countSelect->reset(Zend_Db_Select::GROUP);
                        $countSelect->distinct(true);
                        $group = $this->getSelect()->getPart(Zend_Db_Select::GROUP);
                        if (Mage::helper("xtento_enhancedgrid")->isMageExport() && !preg_match("/main_table/", (string)$countSelect)) {
                            $countColumnName = str_replace("main_table.", "", "COUNT(DISTINCT ".implode(", ", $group).")");
                        } else {
                            $countColumnName = "COUNT(DISTINCT ".implode(", ", $group).")";
                        }
                        if (preg_match("/AS `a`/", (string)$countSelect) || preg_match("/AS a/", (string)$countSelect)) {
                            $countColumnName = str_replace("main_table.entity_id", "a.entity_id", $countColumnName);
                        }
                        $countSelect->columns($countColumnName);
                    } else {
                        $countColumnName = "COUNT(*)";
                        $countSelect->columns($countColumnName);
                    }

                    #echo((string)$countSelect); die();
                    $collectionCount = $this->getConnection()->fetchRow($countSelect, array());
                    #var_dump($collectionCount); die();
                    if (!empty($collectionCount) && isset($collectionCount[$countColumnName])) {
                        return $collectionCount[$countColumnName];
                    } else {
                        return parent::getSize();
                    }
                }
            }
            ';

            $this->_registerNewClass($collectionModule, $collectionName, $newModelClass, $classCode, 'model');
            if ($rewriteClass) {
                $this->_rewriteClass($collectionModule, $collectionName, $newModelClass, 'model');
            }
            #if (strstr($collectionModule, '_mysql4')) {
            #    $this->_registerNewClass(str_replace("_mysql4", "_resource", $collectionModule), $collectionName, $newModelClass, $classCode, 'model');
            #    $this->_rewriteClass(str_replace("_mysql4", "_resource", $collectionModule), $collectionName, $newModelClass, 'model');
            #}
        }

        // Rewrite block
        if ($blockName !== false) {
            // Get new class name, if none
            if (!$newClass) {
                $newClass = 'Xtento_EnhancedGrid_Block_Rewrite_' . str_replace(' ', '_', ucwords(str_replace('_', ' ', $blockName)));
            }

            $classCode = '
            class NewClass extends ExtendsClass
            {
                private $_readyToPrepareCollection = false;

                public function xtRemoveColumn($columnIndex)
                {
                    if (array_key_exists($columnIndex, $this->_columns)) {
                        unset($this->_columns[$columnIndex]);
                        if ($this->_lastColumnId == $columnIndex) {
                            $keys = array_keys($this->_columns);
                            $this->_lastColumnId = array_pop($keys);
                        }
                    }
                    return $this;
                }

                public function xtUpdateColumn($columnIndex, $columnData)
                {
                    if (array_key_exists($columnIndex, $this->_columns)) {
                        foreach ($this->_columns[$columnIndex]->getData() as $key => $value) {
                            if (isset($columnData[$key]) && !preg_match("/\_default$/", $key)) {
                                $this->_columns[$columnIndex]->setData($key, $columnData[$key]);
                            }
                        }
                    }
                    return $this;
                }

                public function xtSetRenderer($rendererClass)
                {
                    $this->_renderer = $this->getLayout()->createBlock($rendererClass)->setColumn($this);
                    return $this;
                }

                protected function _getExportHeaders()
                {
                    Mage::getModel("xtento_enhancedgrid/grid_processor")->processBlock($this, true);
                    return parent::_getExportHeaders();
                }

                public function xtResetColumnsOrder()
                {
                    $this->_columnsOrder = array();
                    return $this;
                }

                public function setCollection($collection){
                    parent::setCollection($collection);

                    #if ($this->_isExport) return;

                    $sortField = $this->getParam($this->getVarNameSort(), $this->_defaultSort);

                    $filters = $this->getParam($this->getVarNameFilter(), null);
                    if (is_string($filters)) {
                        $filters = $this->helper("adminhtml")->prepareFilterString($filters);
                    }
                    if ($this->_isExport || $sortField == "purchased_items" || ($filters && (is_array($filters) && !empty($filters["purchased_items"])))) {
                        //$collection->getSize();
                        $alreadyJoined = false;
                        $selectFrom = $collection->getSelect()->getPart("from");
                        if (is_array($selectFrom)) {
                            foreach ($selectFrom as $alias => $data) {
                                if ($alias === "'.$itemEntityName.'") {
                                    $alreadyJoined = true;
                                }
                            }
                        }
                        if (!$alreadyJoined) {
                            $collection->join("'.$itemEntityName.'", "'.$itemEntityName.'.'.$itemParentIdField.'=main_table.entity_id", array("sku", "name"));
                            $collection->getSelect()->group("main_table.entity_id");
                        }
                    }

                    $filterMapFields = '.$filterMappedFields.';
                    foreach ($filterMapFields as $field) {
                        $collection->addFilterToMap($field, "main_table." . $field);
                    }
                }

                protected function _prepareCollection()
                {
                    #if ($this->_readyToPrepareCollection) {
                        return parent::_prepareCollection();
                    #} else {
                    #    return $this;
                    #}
                }

                public function xtPrepareCollection()
                {
                    $this->_isExport = Mage::helper("xtento_enhancedgrid")->isMageExport(); // Fix for modules rewriting order grid+collection resetting this to false
                    $this->_readyToPrepareCollection = true;
                    return $this->_prepareCollection();
                }

                protected function _afterToHtml($html) {
                    $html = parent::_afterToHtml($html);
                    if (!Mage::app()->getRequest()->isPost()) {
                        $html .= <<<SCRIPT
                        <script type="text/javascript">
                            function assignColorToGrid() {
                                rows = $$("#{$this->getId()} tbody tr:not([class~=xtento-item-tr])");
                                for (var row = 0; row < rows.length; row++) {
                                    if (row % 2 == 0) {
                                        $(rows[row]).addClassName("even");
                                    } else {
                                        $(rows[row]).removeClassName("even");
                                    }
                                }

                                rows = $$("#{$this->getId()} table tbody tr[class~=xtento-item-tr]");
                                for (var row = 0; row < rows.length; row++) {
                                    Element.removeClassName(rows[row], "even");
                                }
                            }
                            function showMoreItems(orderId) {
                                hiddenRows = $$("tr[class~=xtento-item-tr-hidden-"+orderId+"]");
                                for (var hiddenRow = 0; hiddenRow < hiddenRows.length; hiddenRow++) {
                                    hiddenRows[hiddenRow].show();
                                }
                            }
                            Event.observe(window, "load", function () {
                                originalCallback = {$this->getId()}JsObject.initCallback;
                                {$this->getId()}JsObject.initCallback = function(){ originalCallback(); assignColorToGrid(); };
                                // Initialize
                                assignColorToGrid();
                            });
                        </script>
                        <style type="text/css">
                            .xtento-item-tr {
                                background-color: #fff;
                            }
                            .xtento-item-table {
                                border: 0;
                            }
                            .xtento-item-table th {
                                border-width: 0 1px 1px 0;
                                border-color: #DADFE0;
                                border-style: solid;
                            }
                        </style>
SCRIPT;
                    }
                    return $html;
                }
            }
            ';
            $this->_registerNewClass($module, $blockName, $newClass, $classCode, 'block');
            $this->_rewriteClass($module, $blockName, $newClass, 'block');
        }
        return $this;
    }

    private function _registerNewClass($module, $class, $newClass, $classCode, $type = 'block')
    {
        if (class_exists($newClass, false)) {
            return $this;
        }
        $extendsClass = $this->_getExtendClass($module, $class, $type);
        $extendsClass = str_replace('Mysql4_Model', 'Model_Mysql4', $extendsClass);
        if (!class_exists($extendsClass, true)) {
            return $this;
        }
        #echo str_replace(array('NewClass', 'ExtendsClass'), array($newClass, $extendsClass), $classCode);

        eval(str_replace(array('NewClass', 'ExtendsClass'), array($newClass, $extendsClass), $classCode));

        return $this;
    }

    private function _getExtendClass($module, $class, $type = 'block')
    {
        // Mage::app()->getConfig()->reinit(); // Required if you get: Invalid block type ...
        $node = Mage::app()->getConfig()->getNode('global/' . $type . 's/' . $module . '/rewrite/' . $class);
        if (is_object($node)) {
            $node = $node->asCanonicalArray();
            if (is_array($node) && count($node)) {
                $className = $node[0];
            } else {
                $className = $node;
            }
        } else {
            $node = Mage::app()->getConfig()->getNode('global/' . $type . 's/' . $module);
            if (!empty($node)) {
                $className = $node->getClassName();
            }
            if (empty($className)) {
                $className = 'mage_' . $module . '_' . $type;
            }
            if (!empty($class)) {
                $className .= '_' . $class;
            }
        }

        return uc_words($className);
    }

    private function _rewriteClass($module, $class, $newClass, $type = 'block')
    {
        $rewrittenXml = new Varien_Simplexml_Config();
        $rewrittenXml->loadString("
        <config>
            <global>
                <" . $type . "s>
                    <" . $module . ">
                        <rewrite>
                            <" . $class . ">" . $newClass . "</" . $class . ">
                        </rewrite>
                    </" . $module . ">
                </" . $type . "s>
            </global>
        </config>
        ");
        #if ($type=='model') {
        #    echo $rewrittenXml->getXmlString();
        #}
        Mage::app()->getConfig()->extend($rewrittenXml, true);
        return $this;
    }

    public function getResourceModelClassName($module, $class, $type) {
    	return $this->_getExtendClass($module, $class, $type);
    }
}