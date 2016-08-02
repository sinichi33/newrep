<?php
/**
 * RMA Grid
 *
*/
class Icube_Rma_Block_Adminhtml_Rma_Grid extends Enterprise_Rma_Block_Adminhtml_Rma_Grid
{
    /**
     * Prepare grid columns
     *
     * @return Enterprise_Rma_Block_Adminhtml_Rma_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('increment_id', array(
            'header' => Mage::helper('enterprise_rma')->__('RMA #'),
            'width'  => '50px',
            'type'   => 'text',
            'index'  => 'increment_id'
        ));

        $this->addColumn('date_requested', array(
            'header' => Mage::helper('enterprise_rma')->__('Date Requested'),
            'index' => 'date_requested',
            'type' => 'datetime',
            'html_decorators' => array('nobr'),
            'width' => 1,
        ));

        $this->addColumn('order_increment_id', array(
            'header' => Mage::helper('enterprise_rma')->__('Order #'),
            'width'  => '50px',
            'type'   => 'text',
            'index'  => 'order_increment_id'
        ));

        $this->addColumn('order_date', array(
            'header' => Mage::helper('enterprise_rma')->__('Order Date'),
            'index' => 'order_date',
            'type' => 'datetime',
            'html_decorators' => array('nobr'),
            'width' => 1,
        ));

        $this->addColumn('customer_name', array(
            'header' => Mage::helper('enterprise_rma')->__('Customer Name'),
            'index' => 'customer_name',
        ));

        $this->addColumn('status', array(
            'header'  => Mage::helper('enterprise_rma')->__('Status'),
            'index'   => 'status',
            'type'    => 'options',
            'width'   => '100px',
            'options' => Mage::getModel('enterprise_rma/rma')->getAllStatuses()
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('enterprise_rma')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('enterprise_rma')->__('View'),
                        'url'       => array('base'=> $this->_getControllerUrl('edit')),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
    }
}
