<?php
/**
 * Veritrans VT Web form block
 *
 * @category   Mage
 * @package    Mage_Veritrans_VtWeb_Block_Form
 * @author     Kisman Hong, plihplih.com
 * when Veritrans payment method is chosen, vtweb1/info.phtml template will be rendered at the right side, in progress bar.
 */
class Veritrans_Vtweb1_Block_Info extends Mage_Payment_Block_Info
{
    
    protected function _construct()
    {
        parent::_construct();
	$this->setInfoMessage( Mage::helper('vtweb1/data')->_getInfoTypeIsImage() == true ?
		'<img src="'. $this->getSkinUrl('images/Veritrans.png'). '"/>' : '<b>'. Mage::helper('vtweb1/data')->_getTitle() . '</b>');
	$this->setPaymentMethodTitle( Mage::helper('vtweb1/data')->_getTitle() );
        $this->setTemplate('vtweb1/info.phtml');
    }

    public function getOrder() {
        return Mage::registry('current_order');
    }
/*
    public function toPdf()
    {
        $this->setTemplate('vtweb1/pdf.phtml');
        return $this->toHtml();
    } */
}
?>
