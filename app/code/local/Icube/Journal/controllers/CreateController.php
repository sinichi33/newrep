<?php
class Icube_Journal_CreateController extends Mage_Core_Controller_Front_Action
{
	public function reclassAction(){

		$orderIncrementId	= $this->getRequest()->getParam('orderIncrementId');
		
		$order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);

		if ($order->hasInvoices()) {
		    $inv = $order->getInvoiceCollection()->getFirstItem();
		    $paymentDate = Mage::getModel('core/date')->date('Ymd', strtotime($inv->getCreatedAt()));
		} else $paymentDate	=	Mage::getModel('core/date')->date('Ymd');

		$giftCardSerialized = $order->getData('gift_cards');

		$params = array(
					'order' => $order, 
					'paymentDate' => $paymentDate, 
					);

		$createJournal = Mage::getModel('journal/journal')->createReclass($params);

		print_r( $createJournal );
	}

	public function bopisAction(){

		$invoiceIncrementId	= $this->getRequest()->getParam('invoiceIncrementId');
		$invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($invoiceIncrementId);

		$createJournal = Mage::getModel('journal/journal')->pickupVoucherJournal($invoice);

		print_r( $createJournal );
	}


	public function returnAction(){

		$creditMemoIncrementId	= $this->getRequest()->getParam('creditMemoIncrementId');
		$creditMemo = Mage::getResourceModel('sales/order_creditmemo_collection')->addFieldToFilter('increment_id', $creditMemoIncrementId)->getFirstItem();

		$createJournal = Mage::getModel('journal/journal')->createReturn($creditMemo);

		print_r( $createJournal );
	}
}