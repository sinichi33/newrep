<?php
class Icube_Vpayment_Block_Adminhtml_Promo_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'vpayment';
        $this->_controller = 'adminhtml_promo';
        parent::__construct();
        $this->_updateButton('save', 'label', $this->__('Save Promo'));
        $this->_updateButton('delete', 'label', $this->__('Delete Promo'));
    }  

    public function getHeaderText()
    {  
        if (Mage::registry('promo')->getId()) {
            return $this->__('Edit Promo ('.Mage::registry('promo')->getId().')');
        }  
        else {
            return $this->__('New Promo');
        }  
    }  
}