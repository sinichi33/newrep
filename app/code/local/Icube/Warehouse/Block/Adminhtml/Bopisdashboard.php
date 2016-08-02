<?php
 
class Icube_Warehouse_Block_Adminhtml_Bopisdashboard extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'icube_warehouse';
        $this->_controller = 'adminhtml_bopisdashboard';
        $this->_headerText = Mage::helper('icube_warehouse')->__('BOPIS Dashboard');
 
        parent::__construct();
        $this->_removeButton('add');
    }
}