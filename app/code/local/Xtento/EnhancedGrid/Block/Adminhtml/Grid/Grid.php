<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2013-10-19T14:57:05+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Adminhtml/Grid/Grid.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Adminhtml_Grid_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setDefaultSort('grid_id');
        $this->setId('xtento_enhancedgrid_grid_grid');
        $this->setDefaultDir('asc');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('xtento_enhancedgrid/grid_collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('grid_id',
            array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Grid ID'),
                'width' => '50px',
                'index' => 'grid_id',
                'type' => 'number'
            )
        );

        $this->addColumn('type',
            array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Grid Type'),
                'index' => 'type',
                'type' => 'options',
                'options' => Mage::getSingleton('xtento_enhancedgrid/system_config_source_grid_type')->toOptionArray()
            )
        );

        $this->addColumn('role_ids',
            array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Admin Roles'),
                'filter' => false,
                'index' => 'role_ids',
                'renderer' => 'xtento_enhancedgrid/adminhtml_grid_grid_renderer_roles',
            )
        );

        $this->addColumn('enabled',
            array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Grid Configuration'),
                'index' => 'enabled',
                'type' => 'options',
                'options' => array(
                    0 => Mage::helper('xtento_enhancedgrid')->__('Enabled'),
                    1 => Mage::helper('xtento_enhancedgrid')->__('Disabled'),
                ),
                'width' => '100px',
                'renderer' => 'xtento_enhancedgrid/adminhtml_grid_grid_renderer_status',
            )
        );

        $this->addColumn('name',
            array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Configuration Name'),
                'index' => 'name'
            )
        );

        $this->addColumn('last_modification',
            array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Last Modification'),
                'index' => 'last_modification',
                'type' => 'datetime'
            )
        );

        $this->addColumn('action',
            array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('xtento_enhancedgrid')->__('Edit Grid'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
            ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('grid_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('grid');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('adminhtml')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('adminhtml')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('update_status', array(
            'label' => Mage::helper('adminhtml')->__('Update Status'),
            'url' => $this->getUrl('*/*/massUpdateStatus'),
            'additional' => array(
                'enabled' => array(
                    'name' => 'enabled',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('adminhtml')->__('Enabled'),
                    'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray()
                )
            )
        ));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}