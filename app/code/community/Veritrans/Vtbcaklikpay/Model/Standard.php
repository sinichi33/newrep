<?php
/**
 * Veritrans VT Web Model Standard
 *
 * @category   Mage
 * @package    Mage_Veritrans_Vtbcaklikpay_Model_Standard
 * @author     Kisman Hong, plihplih.com
 * this class is used after placing order, if the payment is Veritrans, this class will be called and link to redirectAction at Veritrans_Vtbcaklikpay_PaymentController class
 */
class Veritrans_Vtbcaklikpay_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'vtbcaklikpay';
	
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;
	
	protected $_formBlockType = 'vtbcaklikpay/form';
  protected $_infoBlockType = 'vtbcaklikpay/info';
	
	// call to redirectAction function at Veritrans_Vtbcaklikpay_PaymentController
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('vtbcaklikpay/payment/redirect', array('_secure' => true));
	}
}
?>