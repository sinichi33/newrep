<?php
/**
 * Veritrans VT Web form block
 *
 * @category   Mage
 * @package    Mage_Veritrans_VtWeb_Block_Form
 * @author     Kisman Hong, plihplih.com
 * when Veritrans payment method is chosen, vtweb1/form.phtml template will be rendered through this class.
 */
class Veritrans_Vtweb1_Block_Form extends Mage_Payment_Block_Form
{
    
    protected function _construct()
    {
        parent::_construct();


     $bank_transfer = array();
     $direct_debit = array();
     $emoney = array();

    if (Mage::getStoreConfig('payment/vtweb1/enable_creditcard') == '1') {
      $credit_card = 'credit_card';
    }
    if (Mage::getStoreConfig('payment/vtweb1/enable_permatava') == '1') {
      $bank_transfer = 'bank_transfer';
    }
    if (Mage::getStoreConfig('payment/vtweb1/enable_cimbclick') == '1') {
      $direct_debit[] = 'cimb_clicks';
    }
    if (Mage::getStoreConfig('payment/vtweb1/enable_mandiriclickpay') == '1') {
      $direct_debit[] = 'mandiri_clickpay';
    }
    if (Mage::getStoreConfig('payment/vtweb1/enable_briepay') == '1') {
      $direct_debit[] = 'bri_epay';
    }
    if (Mage::getStoreConfig('payment/vtweb1/enable_mandiribill') == '1') {
      $direct_debit[] = 'echannel';
    }
    if (Mage::getStoreConfig('payment/vtweb1/enable_tcash') == '1') {
      $emoney['telkomsel_cash'] = 'telkomsel_cash';
    }
    if (Mage::getStoreConfig('payment/vtweb1/enable_xltunai') == '1') {
      $emoney['xl_tunai'] = 'xl_tunai';
    }
    if (Mage::getStoreConfig('payment/vtweb1/enable_bbmmoney') == '1') {
        $emoney['bbm_money'] = 'bbm_money';
    }
    if (Mage::getStoreConfig('payment/vtweb1/enable_mandiriecash') == '1') {
        $emoney['mandiri_ecash'] = 'mandiri_ecash';
    }
    	$this->setCreditCard($credit_card);
    	$this->setBankTransfer($bank_transfer);
    	$this->setDirectDebit($direct_debit);
    	$this->setEmoney($emoney);
		$this->setFormMessage(Mage::helper('vtweb1/data')->_getFormMessage());
        $this->setTemplate('vtweb1/form.phtml');
    }
}
?>