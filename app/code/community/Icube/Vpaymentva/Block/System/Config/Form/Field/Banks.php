<?php
class Icube_Vpaymentva_Block_System_Config_Form_Field_Banks extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('label', array(
            'label' => Mage::helper('adminhtml')->__('Bank Label'),
            'style' => 'width:120px',
        ));
        $this->addColumn('bank', array(
            'label' => Mage::helper('adminhtml')->__('Bank Code'),
            'style' => 'width:80px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Bank');
        parent::__construct();
    }
}