<?php 

require_once (BP . DS .'lib/Nusoap/lib/nusoap.php');

class Icube_PickupVoucher_VoucherController extends Mage_Core_Controller_Front_Action {

	public function createPickupVoucherAction()
	{
         // testing pickup/voucher/createPickupVoucher?invoiceid=100000168    

		$invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($this->getRequest()->getParam('invoiceid'));
        
        $testPickup = Mage::getModel('pickupvoucher/voucher')->getPickupVoucher($invoice);

        echo "return :".$testPickup;
	}
}