<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2015-10-28T16:12:43+01:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/Grid/Collection.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_Grid_Collection extends Mage_Core_Model_Abstract
{
    public function addCustomFieldsToCollection($collection, $blockInfo)
    {
        // Amasty Flags compatibility
        if (Mage::getConfig()->getModuleConfig('Amasty_Flags')->is('active', 'true')) {
            $observerClass = Mage::getModel('amflags/observer');
            $reflectionClass = new ReflectionMethod(get_class($observerClass), '_prepareCollection');
            $reflectionClass->setAccessible(true);
            $reflectionClass->invoke($observerClass, $collection);
        }
        // End Amasty Flags compatibility

        $columnJoinField = array();
        $joinsToDo = array();
        $block = $blockInfo->getBlock();
        $gridConfiguration = $blockInfo->getGridConfiguration();
        $customColumns = $blockInfo->getCustomColumns();
        foreach ($customColumns as $columnIndex => $columnData) {
            if (isset($columnData['join']) && !empty($columnData['join'])) {
                $collection->join(
                    $columnData['join']['table'],
                    str_replace('{{table}}', /*'`' . */$columnData['join']['table']/* . '`'*/, $columnData['join']['condition']),
                    $columnData['join']['field']
                );
                $columnJoinField[$columnIndex] = $columnData['join']['field'];
                #echo $collection->getSelect(); die();
            }
            if (isset($columnData['join_left']) && !empty($columnData['join_left'])) {
                $joinData = $columnData['join_left'];
                $alreadyJoined = false;
                foreach ($joinsToDo as &$joinToDo) {
                    // Check if this table is being joined already
                    if ($joinToDo['join_data']['name'] == $joinData['name']) {
                        $joinToDo['join_data']['cols'] = array_merge_recursive($joinToDo['join_data']['cols'], $joinData['cols']);
                        $alreadyJoined = true;
                    }
                }
                if (!$alreadyJoined) {
                    $joinsToDo[] = array(
                        'type' => 'join_left',
                        'join_data' => $joinData,
                    );
                }
            }
            /*if (isset($columnData['join_field']) && !empty($columnData['join_field'])) {
                $joinData = $columnData['join_field'];
                $collection->joinField(
                    $joinData['alias'],
                    $joinData['table'],
                    $joinData['field'],
                    $joinData['bind'],
                    $joinData['cond'],
                    $joinData['joinType']
                );
            }*/
            /*'join_field' => array(
                'alias' => 'shipping_description',
                'table' => 'sales/order',
                'field' => 'shipping_description',
                'bind' => 'entity_id=entity_id',
                'cond' => null,
                'joinType' => 'left'
            ),*/
        }

        #print_r($joinsToDo); die();
        foreach ($joinsToDo as $join) {
            $selectString = $collection->getSelect()->__toString();

            $joinType = $join['type'];
            $joinData = $join['join_data'];

            // Check if columns have been joined already; if yes don't join them again
            $skipJoin = false;
            if (is_array($joinData['cols'])) {
                foreach ($joinData['cols'] as $columnName => $field) {
                    if (preg_match('/AS `' . $columnName . '`/', $selectString) || preg_match('/AS ' . $columnName . '/', $selectString)) {
                        // Already joined, continue
                        $skipJoin = true;
                        break;
                    }
                }
            }

            // Check if tables have been joined already; if yes don't join them again
            $tableJoined = false;
            if (is_array($joinData['name'])) {
                foreach ($joinData['name'] as $tableAlias => $table) {
                    if (array_key_exists($tableAlias, $collection->getSelect()->getPart(Zend_Db_Select::FROM))) {
                        $tableJoined = true;
                        break;
                    }
                }
            }

            // Duplicate join, skip
            if ($skipJoin) {
                #continue;
            }

            // Join column(s)
            if ($joinType == 'join_left') {
                if (!$skipJoin) {
                    if (!$tableJoined) {
                        $collection->getSelect()->joinLeft(
                            $joinData['name'],
                            $joinData['cond'],
                            $joinData['cols']
                        );
                        if (isset($joinData['where']) && !empty($joinData['where'])) {
                            $collection->getSelect()->where($joinData['where']);
                        }
                    } else {
                        // Table already joined, select just the field
                        $collection->getSelect()->columns($joinData['cols']);
                    }
                }
            }
        }

        #var_dump($collection->getSelect()->__toString());
        #die();

        foreach ($block->getColumns() as $column) {
            if (isset($columnJoinField[$column->getId()])) {
                $column->setIndex($columnJoinField[$column->getId()]);
                #$column->setFilterIndex('main_table.'.$columnJoinField[$column->getId()]);
            }
        }

        // Hide certain order status(es)
        if ($gridConfiguration->getHiddenStatus() != '') {
            $collection->addAttributeToFilter('main_table.status', array('nin' => explode(",", $gridConfiguration->getHiddenStatus())));
        }
        if ($gridConfiguration->getHiddenStores() != '') {
            $collection->addAttributeToFilter('main_table.store_id', array('nin' => explode(",", $gridConfiguration->getHiddenStores())));
        }

        $collection->getSelect()->group('main_table.entity_id');

        /* Potentially required for long column names:
        $connnection->exec('SET SESSION group_concat_max_len = 4096;');
        */
    }
}