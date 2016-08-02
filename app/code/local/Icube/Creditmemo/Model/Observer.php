<?php

class Icube_Creditmemo_Model_Observer
{
    /*
    *   Create Giftcard Refund
    *   
    */
    public function createGiftcardRefund(Varien_Event_Observer $observer)
    {
	    $creditmemo = $observer->getEvent()->getCreditmemo();

	    if($creditmemo->getIsCreateGc())
		{
			/* The Calculation below based on teamwork : tasks/6116809 */
	        $order = Mage::getModel('sales/order')->load($creditmemo->getOrderId());
	        $orderGcAmount = $order->getGiftCardsAmount();
	        $orderGrandTotal = $order->getBaseSubtotal()
        					+$order->getBaseTaxAmount()
							+$order->getBaseDiscountAmount()
							+$order->getBaseShippingAmount()
							+$order->getBaseFoomanSurchargeAmount();

			/**
			 * Calculate various helper variables
			 * TODO: refactor this duplication with app/code/local/Icube/Creditmemo/Block/Adminhtml/Sales/Creditmemo/Create/Refundasgc.php
			 */
			$totalPayments = $orderGcAmount + $order->getBaseTotalInvoiced(); // total payment of all MOP
			$totalItemsSalesPrice = $orderGrandTotal - $order->getBaseShippingAmount();

			/**
			 * in case where GC payment is equal or less than total item sales price
			 */
			if ($orderGcAmount <= $totalItemsSalesPrice) {
				$totalItemsPaidByGc = $orderGcAmount; // all GC allocated to item sales price

				// get outstanding payment, and allocate cash to it (if any, > 0 )
				$totalItemsPaidByCash = ($totalItemsSalesPrice - $orderGcAmount) > 0 ? ($totalItemsSalesPrice - $orderGcAmount) : 0;

				$totalShippingPaidByGc = 0;
				// allocate any remaining cash to shipping
				$totalShippingPaidByCash = $order->getBaseTotalInvoiced() - $totalItemsPaidByCash;

				// raise exception if we fail to allocate remaining cash to shipping
				if ($totalShippingPaidByCash != $order->getBaseShippingAmount()) {
					Mage::throwException(
							Mage::helper('sales')->__('Error when calculating shipping paid by cash: %s compared to base shipping amount: %s',$totalShippingPaidByCash,$order->getBaseShippingAmount())
					);
				}

			}
			/**
			 * in case where GC payment is more than total item sales price
			 */
			elseif ($orderGcAmount > $totalItemsSalesPrice) {
				$totalItemsPaidByGc = $totalItemsSalesPrice; // allocate all sales price to be paid by Gc
				$totalItemsPaidByCash = 0; // no cash payment

				$residualGc = $orderGcAmount - $totalItemsPaidByGc;
				$totalShippingPaidByGc = ($order->getBaseShippingAmount() - $residualGc) >= 0 ? $residualGc : 0;
				$totalShippingPaidByCash = $order->getBaseShippingAmount() - $totalShippingPaidByGc;
			}

			$prorateGc = 0;
			// PRORATE GC based on each item
			/** @var Mage_Sales_Model_Order_Creditmemo_Item $creditmemoItem */
			foreach ($creditmemo->getAllItems() as $creditmemoItem)
			{

				$rowAfterDiscount = $creditmemoItem->getRowTotal() + $creditmemoItem->getHandlingFeeItem() - $creditmemoItem->getDiscountAmount();
				$prorateGc += ( $rowAfterDiscount /$totalItemsSalesPrice)*$totalItemsPaidByGc;
			}



			// get from previously calculated
	        $refundAsGc = (int)$creditmemo->getRefundAsGc();
			$refundAsCash = $creditmemo->getGrandTotal() - $refundAsGc;

			// Create GC Refund new
			if($refundAsGc > 0)
			{
		        $this->cretateGiftCard($order, $refundAsGc, Icube_Customgiftcard_Helper_Data::REFUND);
			}		
				        
	        $POSNR = array();

			// POSNR 1 - cash against item
			if ($totalItemsPaidByCash > 0) {
				$itemCashToReturn = $creditmemo->getBaseGrandTotal() - $creditmemo->getShippingAmount();
				$POSNR[1] = ( ($creditmemo->getBaseSubtotal() + $creditmemo->getBaseDiscountAmount() + $creditmemo->getFoomanSurchargeAmount() ) / $totalItemsSalesPrice) * $totalItemsPaidByCash;
				$POSNR[1] = round($POSNR[1]);
			}
			else {
				$POSNR[1] = 0;
			}

			$POSNR[2] = $creditmemo->getAdjustmentNegative(); // Penalty / Adjustment Fee
			$POSNR[3] = $refundAsGc; // Refund as New GC
			$POSNR[4] = $creditmemo->getShippingAmount(); // Refund Shipping
			$POSNR[5] = 0; // unused
			$POSNR[6] = 0; // unused
			$POSNR[7] = 0; // unused
			$POSNR[8] = $refundAsCash; // Refund as Cash
			$POSNR[9] = round($prorateGc); // POSNR 9 - GC againts item

			// Create Return Journal send POSNR over XML
			$returnJournal = Mage::getModel('journal/journal')->createReturn($creditmemo,$order->getIncrementId(),$POSNR);
			
			// Update Credit Memo Refund As GC and Cash
			$cmId = $creditmemo->getEntityId();
			$resource = Mage::getSingleton('core/resource');
			$writeConnection = $resource->getConnection('core_write');
			$query = "UPDATE sales_flat_creditmemo SET refund_as_gc = '$refundAsGc', refund_cash = '$refundAsCash' WHERE entity_id = '$cmId'";
			$writeConnection->query($query);			    	    
		}      
    }
	
	public function cretateGiftCard($order, $amount, $type)
	{
			$getConfig = Mage::getStoreConfig('icube_creditmemo/giftcard/expiry');
			$dateConfig = $getConfig == 0 ? 180 : $getConfig;
            $gift_card = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');
            $dateExpires  = Mage::getModel('core/date')->date('Y-m-d');
            $dateExpires  = date('Y-m-d', strtotime("+$dateConfig days", strtotime($dateExpires)));

            $gift_card
                ->setStatus($gift_card::STATUS_ENABLED)
                ->setDateExpires($dateExpires)
                ->setWebsiteId(1)
                ->setState($gift_card::STATE_AVAILABLE)
                ->setIsRedeemable($gift_card::NOT_REDEEMABLE)
                ->setBalance($amount)
                ->setCustomerId($order->getCustomerId())
                ->setRecipientName($order->getCustomerName())
                ->setRecipientEmail($order->getCustomerEmail())
                ->setRecipientStore($order->getStoreId())
                ->setType($type);
                
            try {
                $gift_card->save();
                $sending = null;

                try {
                    // send email to customer
                    $gift_card->sendEmail();
                    $sending = $gift_card->getEmailSent();
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('customgiftcard')->__('The gift card account has been saved, but email was not sent.')
                    );
                }

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
	}
	
	public function insertCreditMemoGcHistory($orderId, $creditmemoId, $type, $value)
	{
		$history = Mage::getModel('icube_creditmemo/history');
	    $history->setOrderIncrementId($orderId)
			    ->setCreditmemoIncrementId($creditmemoId)
			    ->setValue($value)
			    ->setType($type);
	    $history->save();
	}
	
    /*
    *   Set Billing Number
    *   
    */
    public function insertBillingNumberAndIsCreateGc(Varien_Event_Observer $observer)
    {
        $request = $observer->getEvent()->getRequest();
        /** @var Mage_Sales_Model_Order_Creditmemo $creditmemo */
		$creditmemo = $observer->getEvent()->getCreditmemo();

        $input = $request->getParam('creditmemo');

        if (isset($input['billing_number']) )  
        {
            $creditmemo->setBillingNumber($input['billing_number']);
        }
        
        if (isset($input['is_create_gc']) )  
        {
            $creditmemo->setIsCreateGc($input['is_create_gc']);
        }
        
        if (isset($input['refund_as_gc']) )  
        {
            $creditmemo->setRefundAsGc($input['refund_as_gc']);
        }

        if (isset($input['refund_as_cash']) )
        {
            $creditmemo->setRefundAsCash($input['refund_as_cash']);
        }

    }
    
}
