<?php
class Icube_Vpaymentins_Model_System_Config_Installmentoption
{
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Bank')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Tenor'))
        );
    }
    
    
    public function toArray()
    {
        return array(
            1 => Mage::helper('adminhtml')->__('Bank'),
            2 => Mage::helper('adminhtml')->__('Tenor'),
        );
    }
}