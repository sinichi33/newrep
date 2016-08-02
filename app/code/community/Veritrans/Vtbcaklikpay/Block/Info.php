<?php
/**
 * Veritrans VT Web form block
 *
 * @category   Mage
 * @package    Mage_Veritrans_Vtbcaklikpay_Block_Form
 * @author     Kisman Hong, plihplih.com
 * when Veritrans payment method is chosen, vtbcaklikpay/info.phtml template will be rendered at the right side, in progress bar.
 */
class Veritrans_Vtbcaklikpay_Block_Info extends Mage_Payment_Block_Info
{
    
    protected function _construct()
    {
        parent::_construct();
	$this->setInfoMessage( Mage::helper('vtbcaklikpay/data')->_getInfoTypeIsImage() == true ?
		'<img src="'. $this->getSkinUrl('images/logo_veritrans.gif'). '"/>' : '<b>'. Mage::helper('vtbcaklikpay/data')->_getTitle() . '</b>');
	$this->setPaymentMethodTitle( Mage::helper('vtbcaklikpay/data')->_getTitle() );
        $this->setTemplate('vtbcaklikpay/info.phtml');
    }
/*
    public function toPdf()
    {
        $this->setTemplate('vtbcaklikpay/pdf.phtml');
        return $this->toHtml();
    } */
}
?>
