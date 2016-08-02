<?php
/**
 * Veritrans VT Web Model Standard
 *
 * @category   Mage
 * @package    Mage_Veritrans_Vtweb1_Model_Standard
 * @author     Kisman Hong, plihplih.com
 * this class is used after placing order, if the payment is Veritrans, this class will be called and link to redirectAction at Veritrans_Vtweb1_PaymentController class
 */
class Veritrans_Vtweb1_Model_Standard extends Mage_Payment_Model_Method_Abstract {
    const TRX_STATUS_SETTLEMENT   = 'settlement';
	protected $_code = 'vtweb1';
	
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;
	
	protected $_formBlockType = 'vtweb1/form';
  protected $_infoBlockType = 'vtweb1/info';
	
	// call to redirectAction function at Veritrans_Vtweb1_PaymentController
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('vtweb1/payment/redirect', array('_secure' => true));
	}
}
?>