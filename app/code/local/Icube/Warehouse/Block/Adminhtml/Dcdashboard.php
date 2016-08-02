<?php
 
class Icube_Warehouse_Block_Adminhtml_Dcdashboard extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'icube_warehouse';
        $this->_controller = 'adminhtml_dcdashboard';
        $this->_headerText = Mage::helper('icube_warehouse')->__('DC Dashboard');
 
        parent::__construct();
        $this->_removeButton('add');
    }
}