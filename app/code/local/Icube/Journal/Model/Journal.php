<?php

require_once (BP . DS .'lib/Icube/xml2array.php');

class Icube_Journal_Model_Journal extends Mage_Core_Model_Abstract
{
	public function getLocalEnvironment()
	{
		return Mage::getStoreConfig('journal/setting/local',Mage::app()->getStore());	
	}
	
	public function _construct()
	{
		$this->_init('journal/journal');
	}

	/*	Create Reclass Journal
	*	$params = array(
	*				'order' => $order, 
	*				'paymentDate' => $paymentDate, 
	*			);
	*/
	public function createReclass($params)
	{
			$order = $params['order'];
			$dataGC = '<?xml version="1.0" encoding="UTF-8"?>';
			$dataGC .= '<Journal_B2C>';

			$werks = Mage::getStoreConfig('journal/xml_header/werks', Mage::app()->getStore());
			$werks = ($werks) ? $werks : 'O300';
			$vorgangart = Mage::getStoreConfig('journal/xml_header/vorgangart_reclass', Mage::app()->getStore());
			$vorgangart = ($vorgangart) ? $vorgangart : '3604';
			$dataGC .= 	'<b2c_journal_header>
						<WERKS>'.$werks.'</WERKS>
						<VORGDATUM>'.$params['paymentDate'].'</VORGDATUM>
						<BONNUMMER>'.$order->getIncrementId().'</BONNUMMER>
						<VORGANGART>'.$vorgangart.'</VORGANGART>
						<SOURCE>E_COMMERCE_MAGENTO_B2C</SOURCE>
						</b2c_journal_header>';

			$POSNR[1] = 0;		// AMOUNT General GIFCARD
			$POSNR[2] = 0;		// AMOUNT Redeem Point & CM GIFCARD
			$POSNR[3] = $order->getBaseGiftCardsAmount();	// Total used Giftcard amount
			$POSNR[4] = 0;		// Total forfeit balance from POSNR 1
			$POSNR[5] = 0;		// Total forfeit balance from POSNR 2
			$giftCards = @unserialize($order->getData('gift_cards'));
			foreach ( $giftCards as $giftCard) {
				
				$gc = Mage::getModel('enterprise_giftcardaccount/giftcardaccount')->load($giftCard['i']);
				$gcHistory = Mage::getModel('enterprise_giftcardaccount/history')
                                        ->getCollection()
                                        ->addFieldToFilter('giftcardaccount_id', $giftCard['i'])
                                        ->addFieldToFilter('additional_info', array(
                                            array('like' => '%#'.$order->getIncrementId().'%'), 
                                        ))->getFirstItem();
                
				if($gc->getType() == 'redeem' || $gc->getType() == 'refund'){
					$POSNR[2] += $gcHistory->getBalanceAmount() + abs($gcHistory->getBalanceDelta());
					$POSNR[5] += $gcHistory->getBalanceAmount();
				} else {
					$POSNR[1] += $gcHistory->getBalanceAmount() + abs($gcHistory->getBalanceDelta());
					$POSNR[4] += $gcHistory->getBalanceAmount();
				}

			}

			$posnr_log = '';
				for($i=1; $i<=5; $i++){
					$dataGC .= 	'<b2c_journal_item>
								<POSNR>'.$i.'</POSNR>
								<ZUONR>'.$order->getIncrementId().'</ZUONR>
								<WRBTR>'.round($POSNR[$i]).'</WRBTR>
								<WAERS>IDR</WAERS>
								</b2c_journal_item>';
					$posnr_log .= 'POSNR '.$i.' = '.round($POSNR[$i]).', ';
				}

			$dataGC .= '</Journal_B2C>';

		if($this->getLocalEnvironment())
		{
			$reclassJournalResult = '';
		}
		else
		{
			$reclassJournalResult = $this->journalSoapCall($dataGC);
		}
		
		Mage::log('Reclass Journal Result for Order ID #'.$order->getIncrementId().' '.$posnr_log.' : '.$reclassJournalResult, null, 'icube_journal_reclass.log');
		
		return $dataGC;
	}


	/*	Create Return Journal
	*/
	public function createReturn($creditmemo, $orderIncrementId, $POSNR)
	{
		$cmDate = Mage::getModel('core/date')->date('Ymd', strtotime($creditmemo->getCreatedAt()));
		$data = '<?xml version="1.0" encoding="UTF-8"?>
					<Journal_B2C>';

		$werks = Mage::getStoreConfig('journal/xml_header/werks', Mage::app()->getStore());
		$werks = ($werks) ? $werks : 'O300';
		$vorgangart = Mage::getStoreConfig('journal/xml_header/vorgangart_credit_memo', Mage::app()->getStore());
		$vorgangart = ($vorgangart) ? $vorgangart : '3603';
		$data .= 	'<b2c_journal_header>
						<WERKS>'.$werks.'</WERKS>
						<VORGDATUM>'.$cmDate.'</VORGDATUM>
						<BONNUMMER>'.$creditmemo->getIncrementId().'</BONNUMMER>
						<VORGANGART>'.$vorgangart.'</VORGANGART>
						<SOURCE>E_COMMERCE_MAGENTO_B2C</SOURCE>
						</b2c_journal_header>';
			
		$posnr_log = '';
			for($i=1; $i<=9; $i++){
				$data .= 	'<b2c_journal_item>
							<POSNR>'.$i.'</POSNR>
							<ZUONR>'.$orderIncrementId.'</ZUONR>
							<WRBTR>'.round($POSNR[$i]).'</WRBTR>
							<WAERS>IDR</WAERS>
							</b2c_journal_item>';
					$posnr_log .= 'POSNR '.$i.' = '.round($POSNR[$i]).', ';
			}

		$data .= '</Journal_B2C>';

		if($this->getLocalEnvironment())
		{
			$returnJournalResult = '';
		}
		else
		{
			$returnJournalResult = $this->journalSoapCall($data);
		}
        Mage::log('Return Journal Result for Credit Memo ID #'.$creditmemo->getIncrementId().' '.$posnr_log.' : '.$returnJournalResult, null, 'icube_journal_return.log');

		return $data;
	}


	/*	Create BOPIS Journal
	*	$params = array(
		*				'invoice' => $invoice,
	*				);
	*/
	public function pickupVoucherJournal($invoice)
	{
			$order = $invoice->getOrder();
				
			$paymentDate = Mage::getModel('core/date')->date('Ymd', strtotime($invoice->getCreatedAt()));

			$dataGC = '<?xml version="1.0" encoding="UTF-8"?>';
			$dataGC .= '<Journal_B2C>';

			$werks = Mage::getStoreConfig('journal/xml_header/werks', Mage::app()->getStore());
			$werks = ($werks) ? $werks : 'O300';
			$vorgangart = Mage::getStoreConfig('journal/xml_header/vorgangart_bopis', Mage::app()->getStore());
			$vorgangart = ($vorgangart) ? $vorgangart : '3602';
			$dataGC .= 	'<b2c_journal_header>
						<WERKS>'.$werks.'</WERKS>
						<VORGDATUM>'.$paymentDate.'</VORGDATUM>
						<BONNUMMER>'.$order->getData('increment_id').'</BONNUMMER>
						<VORGANGART>'.$vorgangart.'</VORGANGART>
						<SOURCE>E_COMMERCE_MAGENTO_B2C</SOURCE>
						</b2c_journal_header>';
			
			// AR AMOUNT : Customer membayar sejumlah
			$POSNR[1] = $invoice->getBaseGrandTotal();
			
			// nilai gift card yang terpakai + shipping GC	
			$POSNR[2] = $invoice->getBaseGiftCardsAmount();		

			// selisih nilai harga barang dan pembayaran yang diproporsikan ke barang bila ada transport cost dibayar dengan gift card 
			$POSNR[3] = 0;	
			
			// Voucher Pick Up (BOPIS) Amount
			$POSNR[4] = $invoice->getBaseGrandTotal() + $invoice->getBaseGiftCardsAmount();
			
			// Transport Cost Amount (for delivery from store process)		
			$POSNR[5] = 0;		
			
			$posnr_log = '';
			$zuonr4 = $invoice->getStoreCode().''.$invoice->getPickupVoucher();
				for($i=1; $i<=5; $i++){
					$zuonr 	= ( $i == 4 ) ? $zuonr4 : $order->getIncrementId() ;
					$dataGC .= 	'<b2c_journal_item>
								<POSNR>'.$i.'</POSNR>
								<ZUONR>'.$zuonr.'</ZUONR>
								<WRBTR>'.round($POSNR[$i]).'</WRBTR>
								<WAERS>IDR</WAERS>
								</b2c_journal_item>';
					$posnr_log .= 'POSNR '.$i.' = '.round($POSNR[$i]).', ';
				}

			$dataGC .= '</Journal_B2C>';
			
		if($this->getLocalEnvironment())
		{
			$pickupJournalResult = '';
		}
		else
		{
			$pickupJournalResult = $this->journalSoapCall($dataGC);
		}
		Mage::log('Journal Pickup Result for Invoice ID #'.$invoice->getIncrementId().' '.$posnr_log.' voucher:'.$invoice->getPickupVoucher().' : '.$pickupJournalResult, null, 'icube_journal_pickup.log');
		
		return $dataGC;
	}
	
	public function freeItemPickupVoucherJournal($invoice)
	{
			$order = $invoice->getOrder();			
			$paymentDate = Mage::getModel('core/date')->date('Ymd', strtotime($invoice->getCreatedAt()));

			$dataGC = '<?xml version="1.0" encoding="UTF-8"?>';
			$dataGC .= '<Journal_B2C>';

			$werks = Mage::getStoreConfig('journal/xml_header/werks', Mage::app()->getStore());
			$werks = ($werks) ? $werks : 'O300';
			$vorgangart = Mage::getStoreConfig('journal/xml_header/vorgangart_bopis', Mage::app()->getStore());
			$vorgangart = ($vorgangart) ? $vorgangart : '3602';
			$dataGC .= 	'<b2c_journal_header>
						<WERKS>'.$werks.'</WERKS>
						<VORGDATUM>'.$paymentDate.'</VORGDATUM>
						<BONNUMMER>'.$order->getData('increment_id').'</BONNUMMER>
						<VORGANGART>'.$vorgangart.'</VORGANGART>
						<SOURCE>E_COMMERCE_MAGENTO_B2C</SOURCE>
						</b2c_journal_header>';
			
			$POSNR[1] = count($invoice->getFreeItems());
			
			$POSNR[2] = 0;		

			$POSNR[3] = 0;	
			
			// Voucher GWP Amount
			$POSNR[4] = count($invoice->getFreeItems());
	
			$POSNR[5] = 0;		
			
			$posnr_log = '';

				for($i=1; $i<=5; $i++){
					$zuonr 	= ( $i == 4 ) ? $invoice->getPickupVoucher() : $order->getIncrementId() ;
					$dataGC .= 	'<b2c_journal_item>
								<POSNR>'.$i.'</POSNR>
								<ZUONR>'.$zuonr.'</ZUONR>
								<WRBTR>'.round($POSNR[$i]).'</WRBTR>
								<WAERS>IDR</WAERS>
								</b2c_journal_item>';
					$posnr_log .= 'POSNR '.$i.' = '.round($POSNR[$i]).', ';
				}

			$dataGC .= '</Journal_B2C>';
			
		if($this->getLocalEnvironment())
		{
			$pickupJournalResult = '';
		}
		else
		{
			$pickupJournalResult = $this->journalSoapCall($dataGC);
		}
		Mage::log('Journal Pickup Result for Invoice ID #'.$invoice->getIncrementId().' '.$posnr_log.' voucher:'.$invoice->getPickupVoucher().' : '.$pickupJournalResult, null, 'icube_journal_freeitem_pickup.log');
		
		return $dataGC;
	}
	
	public function handlingVoucherJournal($invoice)
	{
			$order = $invoice->getOrder();
	
			// Base Subtotal
			$orderBaseSub = $order->getBaseSubtotal()
        					+$order->getBaseTaxAmount()
							+$order->getBaseDiscountAmount();

			$gcBaseForHandling = 0;
			
			if($order->getBaseGiftCardsAmount() > $orderBaseSub)
			{
				$over = $order->getBaseGiftCardsAmount() - $orderBaseSub;
				if($over > $order->getBaseShippingAmount())
				{
					$gcBaseForHandling = $over - $order->getBaseShippingAmount();
				}	
			}
			
			$paymentDate = Mage::getModel('core/date')->date('Ymd', strtotime($invoice->getCreatedAt()));

			$dataGC = '<?xml version="1.0" encoding="UTF-8"?>';
			$dataGC .= '<Journal_B2C>';

			$werks = Mage::getStoreConfig('journal/xml_header/werks', Mage::app()->getStore());
			$werks = ($werks) ? $werks : 'O300';
			$vorgangart = Mage::getStoreConfig('journal/xml_header/vorgangart_bopis', Mage::app()->getStore());
			$vorgangart = ($vorgangart) ? $vorgangart : '3602';
			$dataGC .= 	'<b2c_journal_header>
						<WERKS>'.$werks.'</WERKS>
						<VORGDATUM>'.$paymentDate.'</VORGDATUM>
						<BONNUMMER>'.$order->getData('increment_id').'</BONNUMMER>
						<VORGANGART>'.$vorgangart.'</VORGANGART>
						<SOURCE>E_COMMERCE_MAGENTO_B2C</SOURCE>
						</b2c_journal_header>';
			
			// AR AMOUNT: Handling yg dibayar oleh customer
			$POSNR[1] = $invoice->getBaseFoomanSurchargeAmount()-$gcBaseForHandling;
			
			// nilai gift card yang terpakai untuk handling fee	
			$POSNR[2] = $gcBaseForHandling;		

			$POSNR[3] = 0 ;	
			
			// Voucher Handling Amount
			$POSNR[4] = $invoice->getBaseFoomanSurchargeAmount();
					
			$POSNR[5] = 0;		
			
			$posnr_log = '';

				for($i=1; $i<=5; $i++){
					$zuonr 	= ( $i == 4 ) ? $invoice->getHandlingVoucher() : $order->getIncrementId() ;
					$dataGC .= 	'<b2c_journal_item>
								<POSNR>'.$i.'</POSNR>
								<ZUONR>'.$zuonr.'</ZUONR>
								<WRBTR>'.round($POSNR[$i]).'</WRBTR>
								<WAERS>IDR</WAERS>
								</b2c_journal_item>';
					$posnr_log .= 'POSNR '.$i.' = '.round($POSNR[$i]).', ';
				}

			$dataGC .= '</Journal_B2C>';
		
		if($this->getLocalEnvironment())
		{
			$journalResult = '';
		}
		else
		{
			$journalResult = $this->journalSoapCall($dataGC);
		}
		
		Mage::log('Journal Handling Result for Invoice ID #'.$invoice->getIncrementId().' '.$posnr_log.' voucher:'.$invoice->getHandlingVoucher().' : '.$journalResult, null, 'icube_journal_handling.log');
		
		return $dataGC;
	}

	public function createRedeem($params)
	{
			$giftcard = $params['giftcard'];
			$zuonr = $params['companyId'];
			$dataGC = '<?xml version="1.0" encoding="UTF-8"?>';
			$dataGC .= '<Journal_B2C>';
			$giftcardDateCreated = Mage::getModel('core/date')->date('Ymd', strtotime($giftcard->getDateCreated()));

			$werks = Mage::getStoreConfig('journal/xml_header/werks', Mage::app()->getStore());
			$werks = ($werks) ? $werks : 'O300';
			$vorgangart = Mage::getStoreConfig('journal/xml_header/vorgangart_redeem', Mage::app()->getStore());
			$vorgangart = ($vorgangart) ? $vorgangart : '3601';
			$dataGC .= 	'<b2c_journal_header>
						<WERKS>'.$werks.'</WERKS>
						<VORGDATUM>'.$giftcardDateCreated.'</VORGDATUM>
						<BONNUMMER>'.$giftcard->getId().'</BONNUMMER>
						<VORGANGART>'.$vorgangart.'</VORGANGART>
						<SOURCE>E_COMMERCE_MAGENTO_B2C</SOURCE>
						</b2c_journal_header>';

			$posnr_log = '';
			$dataGC .= 	'<b2c_journal_item>
						<POSNR>1</POSNR>
						<ZUONR>'.$zuonr.'</ZUONR>
						<WRBTR>'.$giftcard->getBalance().'</WRBTR>
						<WAERS>IDR</WAERS>
						</b2c_journal_item>';
			$posnr_log .= 'POSNR 1 = '.$giftcard->getBalance().', ';

			$dataGC .= 	'<b2c_journal_item>
						<POSNR>2</POSNR>
						<ZUONR>'.$zuonr.'</ZUONR>
						<WRBTR>'.$giftcard->getBalance().'</WRBTR>
						<WAERS>IDR</WAERS>
						</b2c_journal_item>';
			$posnr_log .= 'POSNR 2 = '.$giftcard->getBalance().', ';


			$dataGC .= '</Journal_B2C>';

		if($this->getLocalEnvironment())
		{
			$redeemJournalResult = '';
		}
		else
		{
			$redeemJournalResult = $this->journalSoapCall($dataGC);
		}
		Mage::log('Redeem Journal Result for Gift Card ID #'.$giftcard->getId().' '.$posnr_log.' : '.$redeemJournalResult, null, 'icube_journal_redeem.log');
		
		return $dataGC;
	}


	private function journalSoapCall($data){

		$arr = new XML2Array();
        //convert file's content to array
        $parameter = $arr->createArray($data);

        $url = Mage::getStoreConfig('journal/setting/api',Mage::app()->getStore());
        //$url = 'http://10.1.32.80/Uniws_rupa2/index.php/RetailEcommerce/dev/?wsdl';

        $soap = new SoapClient($url, array("trace" => 1,
              "exceptions" => 1));
        $soap->__soapCall('Save_journal_data',array('parameters' => $parameter));

            //--------log-----------
            $result = $soap->__last_response;
            $report = $arr->createArray($result);

            $result = $report["SOAP-ENV:Envelope"]["SOAP-ENV:Body"]["Save_journal_dataResponse"]["Save_journal_Result"];
        
        return $result;
	}


}

