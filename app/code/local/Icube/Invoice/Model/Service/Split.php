<?php

class Icube_Invoice_Model_Service_Split extends Mage_Core_Model_Abstract 
{
	
	public function invoiceSplitOrder($order)
    {
        if($order->canInvoice())
        {
            $qtyDc = array();
            $qtyStore = array();
            $qtyPickup = array();
            $shipInfo = array();
            $handlingdc = 0.0000;
	        $handlingstore = array();
	        $handlingpickup = array();
            
            foreach($order->getAllVisibleItems() as $item)
            {
              if($item->getStoreCode() == 'DC' && $item->getQtyOrdered() > 0)
                {
	              $qtyDc[$item->getId()] = $item->getQtyOrdered();
	              $handlingdc += $item->getHandlingFeeItem();
	              $shipInfo['dc'] = array('delivery_pickup' => $item->getDeliveryPickup(), 'store_code' => $item->getStoreCode(), 'company_id' => $item->getCompanyId(), 'pickup_location' => $item->getPickupLocationCode(), 'handling_fee_item' => $handlingdc);
                }
                elseif($item->getStoreCode() != 'DC' && $item->getDeliveryPickup() == 'delivery' && $item->getQtyOrdered() > 0)
              {
                $rowStore = array($item->getId() => $item->getQtyOrdered());
                  if(!empty($qtyStore[$item->getStoreCode()])) 
                  {
                    $qtyStore[$item->getStoreCode()][$item->getId()] =  $item->getQtyOrdered();
                  }
                  else
                  {
                    $qtyStore[$item->getStoreCode()] = $rowStore;
                  }
                    $handlingstore[$item->getStoreCode()] += $item->getHandlingFeeItem();
                    $shipInfo[$item->getStoreCode()] = array('delivery_pickup' => $item->getDeliveryPickup(), 'store_code' => $item->getStoreCode(), 'company_id' =>$item->getCompanyId(), 'pickup_location' => $item->getPickupLocationCode(), 'handling_fee_item' => $handlingstore[$item->getStoreCode()]);
              }
              elseif($item->getDeliveryPickup() == 'pickup' &&  $item->getQtyOrdered() > 0)
                {
                  $rowPickup = array($item->getId() => $item->getQtyOrdered());

                  if(!empty($qtyPickup[$item->getStoreCode()]))
                  {
                    $qtyPickup[$item->getStoreCode()][$item->getId()] = $item->getQtyOrdered();
                  } else {
                    $qtyPickup[$item->getStoreCode()] = $rowPickup;
                  }
                    $handlingpickup[$item->getStoreCode()] += $item->getHandlingFeeItem();
                    $shipInfo[$item->getStoreCode()] = array('delivery_pickup' => $item->getDeliveryPickup(), 'store_code' => $item->getStoreCode(), 'company_id' => $item->getCompanyId(), 'pickup_location' => $item->getPickupLocationCode(), 'handling_fee_item' => $handlingpickup[$item->getStoreCode()]);
                }
            }

            if($qtyDc)
            {
              $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($qtyDc);
              $invoice->setInvoiceStatus('PENDING SHIPMENT');
              $this->invoiceSplitExecute($invoice, $shipInfo['dc']);
            }
                  
            foreach($qtyStore as $key => $value)
            {
              // invoice $value each store_code
              $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($value);
              $this->invoiceSplitExecute($invoice, $shipInfo[$key]);
            }
                
            foreach($qtyPickup as $key => $value)
            {
              // invoice $value each store_code
              $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($value);
              // get Free item invoice
              $itemFree = $this->getOrderItemFree($invoice->getOrder(),$value);
	          $invoice->setFreeItems($itemFree);
	          $invoice->setInvoiceStatus('PENDING');
              $this->invoiceSplitExecute($invoice, $shipInfo[$key]);
            }

			//call reclass journal model
            if ($order->getGiftCards() != 'a:0:{}' )
            {
                if ($order->hasInvoices()) {
                    $inv = $order->getInvoiceCollection()->getFirstItem();
                    $paymentDate = Mage::getModel('core/date')->date('Ymd', strtotime($inv->getCreatedAt()));
                } else $paymentDate = Mage::getModel('core/date')->date('Ymd');

                $params = array(
                      'order' => $order, 
                      'paymentDate' => $paymentDate, 
                      );
                
                $createJournal = Mage::getModel('journal/journal')->createReclass($params);
            }
        }
        else {
            Mage::log('Cannot create an invoice for order '.$order->getIncrementId(), null, 'veritrans.log', true);
        }
    }

    public function invoiceSplitExecute($invoice, $shipInfo)
    {
        if ($invoice) {

			// calculate Giftcard, Grandtotal Invoice and handling fee
			Mage::getModel('icube_invoice/service_calculate')->calculateHandlingGiftcard($shipInfo, $invoice);

            $invoice->getOrder()->setIsInProcess(true);
            $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
            
            $invoice->register();

            $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder());

            $transactionSave->save();

            $invoice->getOrder()->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();
			
            //call pick up voucher
            if($invoice->getDeliveryPickup() == 'pickup') 
            {
                Mage::getModel('pickupvoucher/voucher')->getPickupVoucher($invoice);
            }
                     
        }          
    }
    
    public function getOrderItemFree($order, $itemToInvoice)
	{
	     $result = array();
	     foreach($order->getAllVisibleItems() as $item)
	        {
		        if($item->getPrice() == 0 && array_key_exists($item->getItemId(),$itemToInvoice))
	            $result[] = $item->getItemId();
	        }
	        return $result;
	}
    
}