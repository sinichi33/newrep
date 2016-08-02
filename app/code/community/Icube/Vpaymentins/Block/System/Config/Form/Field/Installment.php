<?php
class Icube_Vpaymentins_Block_System_Config_Form_Field_Installment extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('label', array(
            'label' => Mage::helper('adminhtml')->__('Installment Label'),
            'style' => 'width:120px',
        ));
        $this->addColumn('bank', array(
            'label' => Mage::helper('adminhtml')->__('Bank'),
            'style' => 'width:80px',
        ));
        $this->addColumn('tenor', array(
            'label' => Mage::helper('adminhtml')->__('Tenor'),
            'style' => 'width:40px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Installment');
        parent::__construct();
    }
}