<?php 

class Icube_PickupVoucher_Model_Voucher extends Mage_Core_Model_Abstract {


	public function _construct()
	{
		$this->_init('pickupvoucher/voucher');
	}
	
	public function getLocalEnvironment()
	{
		return Mage::getStoreConfig('pickupvoucher/code/local',Mage::app()->getStore());	
	}
	
	public function getPickupVoucher($invoice)
	{
		// use 1 pickup voucher for all
		$this->getPickupVoucherWithoutFreeItem($invoice);
/*
		if( !$invoice->getFreeItems() )
		{
			$this->getPickupVoucherWithoutFreeItem($invoice);
		}
		else if($invoice->getFreeItems() && $invoice->getTotalQty() != count($invoice->getFreeItems()) ) 
		{
			$this->getPickupVoucherWithoutFreeItem($invoice);
			$this->getFreeItemPickupVoucher($invoice);
		}
		else
		{
			$this->getFreeItemPickupVoucher($invoice);
		} */
	}
	
	public function getPickupVoucherWithoutFreeItem($invoice)
	{
		require_once (BP . DS .'lib/Nusoap/lib/nusoap.php');
		
		$wsdl = Mage::getStoreConfig("pickupvoucher/code/api");
		$client = new nusoap_client($wsdl, 'wsdl');
		
		$ToCompany 	= $invoice->getCompanyId();
		$NoBU 		= $invoice->getStoreCode();				
		$NoSO 		= $invoice->getOrder()->getData('increment_id');
		$NoInvoice 	= $invoice->getIncrementId();
		$Amount		= $invoice->getBaseSubtotal()
					+$invoice->getBaseTaxAmount()
					+$invoice->getBaseDiscountAmount()
					+$invoice->getBaseFoomanSurchargeAmount();

		// add Rp. 1 for each free items
		$Amount += count($invoice->getFreeItems());

		$HandlingFee = $invoice->getBaseFoomanSurchargeAmount() ? 1 : 0;
		
		$param = array('ToCompany' => $ToCompany, 'NoBU' => $NoBU, 'NoSO' => $NoSO, 'NoInvoice' => $NoInvoice, 'Amount' => $Amount, 'HandlingFee'=>$HandlingFee);

	    if($this->getLocalEnvironment())
		{
			$response = '';
		}
		else
		{
			$response = $client->call('generateVoucher', $param);
		}
	    
	    $data = str_replace('"',"",$response ['generateVoucherResult']);
        $data = str_replace('[{',"",$data);
        $data = str_replace('}]',"",$data);
        $data = explode(",", $data);

        $isOk 		= explode(":", $data[0]);
        $voucherId 	= explode(":", $data[1]);
        $hasError 	= explode(":", $data[2]);
        $errMsg 	= explode(":", $data[3]);

		if ($hasError[1] == "true" )
		{
			$voucherCode = $errMsg[1];

		}
		else
		{
			$resource = Mage::getSingleton('core/resource');
			$writeConnection = $resource->getConnection('core_write');
			$readConnection = $resource->getConnection('core_read');
			
			$readQuery = "SELECT pickup_voucher FROM sales_flat_invoice WHERE increment_id = '$NoInvoice'";
			$existingVouchers = $readConnection->fetchOne($readQuery);
			
			if($existingVouchers)
			{
				$voucherCode = $existingVouchers.','.$voucherId[1];
			}
			else
			{
				$voucherCode = $voucherId[1];
			}

			$query = "UPDATE sales_flat_invoice SET pickup_voucher = '$voucherCode' WHERE increment_id = '$NoInvoice'";
			$writeConnection->query($query);

			$query_grid = "UPDATE sales_flat_invoice_grid SET pickup_voucher = '$voucherCode' WHERE increment_id = '$NoInvoice'";
			$writeConnection->query($query_grid);

		}

		// call Bopis Journal
		$invoice->setPickupVoucher($voucherId[1]);
		$createJournal = Mage::getModel('journal/journal')->pickupVoucherJournal($invoice);
	
		 Mage::log('OrderId:'.$NoSO.' InvoiceId:'.$NoInvoice.' Amount:'.$Amount.' voucherCode:'.$voucherCode.' ToCompany:'.$ToCompany.' NoBU:'.$NoBU.' HandlingFee:'.$HandlingFee, null, 'icube_pickupvoucher.log');
		return $voucherCode;
	}
	
	public function getFreeItemPickupVoucher($invoice)
	{
		require_once (BP . DS .'lib/Nusoap/lib/nusoap.php');
		
		$wsdl = Mage::getStoreConfig("pickupvoucher/code/api");
		$client = new nusoap_client($wsdl, 'wsdl');
		
		$ToCompany 	= $invoice->getCompanyId();
		$NoBU 		= $invoice->getStoreCode();				
		$NoSO 		= $invoice->getOrder()->getData('increment_id');
		$NoInvoice 	= $invoice->getIncrementId();
		$Amount 	= count($invoice->getFreeItems());
		$HandlingFee = 0;
		
		$param = array('ToCompany' => $ToCompany, 'NoBU' => $NoBU, 'NoSO' => $NoSO, 'NoInvoice' => $NoInvoice, 'Amount' => $Amount, 'HandlingFee'=>$HandlingFee);

	    if($this->getLocalEnvironment())
		{
			$response = '';
		}
		else
		{
			$response = $client->call('generateVoucher', $param);
		}
	    
	    $data = str_replace('"',"",$response ['generateVoucherResult']);
        $data = str_replace('[{',"",$data);
        $data = str_replace('}]',"",$data);
        $data = explode(",", $data);

        $isOk 		= explode(":", $data[0]);
        $voucherId 	= explode(":", $data[1]);
        $hasError 	= explode(":", $data[2]);
        $errMsg 	= explode(":", $data[3]);

		if ($hasError[1] == "true" ) 
		{
			$voucherCode = $errMsg[1];
		}
		else
		{
			$resource = Mage::getSingleton('core/resource');
			$writeConnection = $resource->getConnection('core_write');
			$readConnection = $resource->getConnection('core_read');
			
			$readQuery = "SELECT pickup_voucher FROM sales_flat_invoice WHERE increment_id = '$NoInvoice'";
			$existingVouchers = $readConnection->fetchOne($readQuery);
			
			if($existingVouchers)
			{
				$voucherCode = $existingVouchers.','.$voucherId[1];
			}
			else
			{
				$voucherCode = $voucherId[1];
			}

			$query = "UPDATE sales_flat_invoice SET pickup_voucher = '$voucherCode' WHERE increment_id = '$NoInvoice'";
			$writeConnection->query($query);

			$query_grid = "UPDATE sales_flat_invoice_grid SET pickup_voucher = '$voucherCode' WHERE increment_id = '$NoInvoice'";
			$writeConnection->query($query_grid);

		}
		
		// call GWP Journal
		$invoice->setPickupVoucher($voucherId[1]);
		$createJournal = Mage::getModel('journal/journal')->freeItemPickupVoucherJournal($invoice);
	
		 Mage::log('OrderId:'.$NoSO.' InvoiceId:'.$NoInvoice.' Amount:'.$Amount.' voucherCode:'.$voucherCode.' ToCompany:'.$ToCompany.' NoBU:'.$NoBU.' HandlingFee:'.$HandlingFee, null, 'icube_free_pickupvoucher.log');
		return $voucherCode;
	}

	
	public function getHandlingFeeVoucher($invoice)
	{
		require_once (BP . DS .'lib/Nusoap/lib/nusoap.php');
		
		$wsdl = Mage::getStoreConfig("pickupvoucher/code/api");
		$client = new nusoap_client($wsdl, 'wsdl');
		
		$ToCompany 	= 'COR';
		$NoBU 		= '0000';				
		$NoSO 		= $invoice->getOrder()->getData('increment_id');
		$NoInvoice 	= $invoice->getIncrementId();
		$Amount 	= $invoice->getBaseFoomanSurchargeAmount();
		$HandlingFee = 0;
		
		$param = array('ToCompany' => $ToCompany, 'NoBU' => $NoBU, 'NoSO' => $NoSO, 'NoInvoice' => $NoInvoice, 'Amount' => $Amount, 'HandlingFee'=>$HandlingFee);

	    if($this->getLocalEnvironment())
		{
			$response = '';
		}
		else
		{
			$response = $client->call('generateVoucher', $param);
		}
	    
	    $data = str_replace('"',"",$response ['generateVoucherResult']);
        $data = str_replace('[{',"",$data);
        $data = str_replace('}]',"",$data);
        $data = explode(",", $data);

        $isOk 		= explode(":", $data[0]);
        $voucherId 	= explode(":", $data[1]);
        $hasError 	= explode(":", $data[2]);
        $errMsg 	= explode(":", $data[3]);
        
		if ($hasError[1] == "true" ) {

			$voucherCode = $errMsg[1];

		}else{

			$voucherCode = $voucherId[1];
			$resource = Mage::getSingleton('core/resource');
			$writeConnection = $resource->getConnection('core_write');
				 
			$query = "UPDATE sales_flat_invoice SET handling_voucher = '$voucherCode' WHERE increment_id = '$NoInvoice'";
			$writeConnection->query($query);

			$query_grid = "UPDATE sales_flat_invoice_grid SET handling_voucher = '$voucherCode' WHERE increment_id = '$NoInvoice'";
			$writeConnection->query($query_grid);

		}
        
        // call Handling Journal
		$invoice->setHandlingVoucher($voucherCode);
		$createJournal = Mage::getModel('journal/journal')->handlingVoucherJournal($invoice);
        
        Mage::log('OrderId:'.$NoSO.' InvoiceId:'.$NoInvoice.' Amount:'.$Amount.' voucherCode:'.$voucherCode.' ToCompany:'.$ToCompany.' NoBU:'.$NoBU.' HandlingFee:'.$HandlingFee, null, 'icube_handlingvoucher.log');
		
	}

}