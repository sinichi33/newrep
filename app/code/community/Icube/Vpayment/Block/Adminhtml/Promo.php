<?php

class Icube_Vpayment_Block_Adminhtml_Promo extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'vpayment';
        $this->_controller = 'adminhtml_promo';
        $this->_headerText = $this->__('Promo');
        $this->_addButtonLabel = $this->__('Add Promo');
        parent::__construct();
    }
}