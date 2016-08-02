<?php
	
require_once(Mage::getModuleDir('controllers','Mage_Adminhtml').DS.'Sales'.DS.'Order'.DS.'InvoiceController.php');
class Icube_Invoice_Adminhtml_Sales_Order_InvoiceController extends Mage_Adminhtml_Sales_Order_InvoiceController
{	 
	 /**
     * Initialize invoice model instance
     *
     * @return Mage_Sales_Model_Order_Invoice
     */
    protected function _initSplitInvoice($savedQtys)
    {
        $this->_title($this->__('Sales'))->_title($this->__('Invoices'));

        $invoice = false;

        $invoiceId = $this->getRequest()->getParam('invoice_id');
        $orderId = $this->getRequest()->getParam('order_id');
        if ($invoiceId) {
            $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId);
            if (!$invoice->getId()) {
                $this->_getSession()->addError($this->__('The invoice no longer exists.'));
                return false;
            }
        } elseif ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            /**
             * Check order existing
             */
            if (!$order->getId()) {
                $this->_getSession()->addError($this->__('The order no longer exists.'));
                return false;
            }
            /**
             * Check invoice create availability
             */
            if (!$order->canInvoice()) {
                $this->_getSession()->addError($this->__('The order does not allow creating an invoice.'));
                return false;
            }

            $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($savedQtys);
            if (!$invoice->getTotalQty()) {
                Mage::throwException($this->__('Cannot create an invoice without products.'));
            }
        }

        Mage::register('current_invoice', $invoice);
        return $invoice;
    }
    
    public function saveAction()
    {
	    $data = $this->getRequest()->getPost('invoice');
        $orderId = $this->getRequest()->getParam('order_id');
        
        if (!empty($data['comment_text'])) {
            Mage::getSingleton('adminhtml/session')->setCommentText($data['comment_text']);
        }
        
        $qtyDc = array();
        $qtyStore = array();
        $qtyPickup = array();
        $shipInfo = array();
        $handlingdc = 0.0000;
        $handlingstore = array();
        $handlingpickup = array();
        $itemDetails = $data['item_details'];
        
        foreach($itemDetails as $key => $value)
        {
	        if($value[2] == 'DC' && $value[0] > 0)
	        {
				$qtyDc[$key] = $value[0];
				$handlingdc += $value[5];
                $shipInfo['dc'] = array('delivery_pickup' => $value[1], 'store_code' => $value[2], 'company_id' => $value[3], 'pickup_location' => $value[4], 'handling_fee_item' => $handlingdc);
	        }
	        elseif($value[2] != 'DC' && $value[1] == 'delivery' && $value[0] > 0)
		    {
			    $rowStore = array($key => $value[0]);
		        if(!empty($qtyStore[$value[2]])) 
		        {
			        $qtyStore[$value[2]][$key] = $value[0];
		        }
		        else
		        {
			        $qtyStore[$value[2]] = $rowStore;
		        }
		        
		        $handlingstore[$value[2]] += $value[5];
                $shipInfo[$value[2]] = array('delivery_pickup' => $value[1], 'store_code' => $value[2], 'company_id' => $value[3], 'pickup_location' => $value[4], 'handling_fee_item' => $handlingstore[$value[2]]);
		    }
		    elseif($value[1] == 'pickup' && $value[0] > 0)
	        {
		        $rowPickup = array($key => $value[0]);

		        if(!empty($qtyPickup[$value[2]]))
		        {
			        $qtyPickup[$value[2]][$key] = $value[0];
		        } else {
			        $qtyPickup[$value[2]] = $rowPickup;
		        }
		        
		        $handlingpickup[$value[2]] += $value[5]; 
                $shipInfo[$value[2]] = array('delivery_pickup' => $value[1], 'store_code' => $value[2], 'company_id' => $value[3], 'pickup_location' => $value[4], 'handling_fee_item' => $handlingpickup[$value[2]]);
                
	        }
        }
		
		// invoice DC for the very first time
		if($qtyDc)
		{
			$invoice = $this->_initSplitInvoice($qtyDc);
            $invoice->setInvoiceStatus($this->__('PENDING SHIPMENT'));
			$this->invoiceSplitExecute($data, $shipInfo['dc'], $invoice);
		}
			    
	    foreach($qtyStore as $key => $value)
        {
			// invoice $value each store_code
			$invoice = $this->_initSplitInvoice($value);
			$this->invoiceSplitExecute($data, $shipInfo[$key], $invoice);
        }
        
        foreach($qtyPickup as $key => $value)
        {
	        // invoice $value each store_code
	        $invoice = $this->_initSplitInvoice($value);
	        // get Free item invoice
	        $itemFree = $this->getOrderItemFree($invoice->getOrder(),$value);
	        $invoice->setFreeItems($itemFree);
	        $invoice->setInvoiceStatus($this->__('PENDING'));
			$this->invoiceSplitExecute($data, $shipInfo[$key], $invoice);
        }
		

		$order = Mage::getModel('sales/order')->load($orderId);
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
		
		Mage::getSingleton('adminhtml/session')->getCommentText(true);
        $this->_redirect('*/sales_order/view', array('order_id' => $orderId));
    }

    
    public function invoiceSplitExecute($data, $shipInfo, $invoice)
    {
        if ($invoice) 
        {
	        // calculate Giftcard, Grandtotal Invoice and handling fee
	        Mage::getModel('icube_invoice/service_calculate')->calculateHandlingGiftcard($shipInfo, $invoice);
			
            if (!empty($data['capture_case'])) {
                $invoice->setRequestedCaptureCase($data['capture_case']);
            }

            if (!empty($data['comment_text'])) {
                $invoice->addComment(
                    $data['comment_text'],
                    isset($data['comment_customer_notify']),
                    isset($data['is_visible_on_front'])
                );
            }
			
            $invoice->register();

            if (!empty($data['send_email'])) {
                $invoice->setEmailSent(true);
            }

            $invoice->getOrder()->setCustomerNoteNotify(!empty($data['send_email']));
            $invoice->getOrder()->setIsInProcess(true);

            $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder());
            $shipment = false;
            if (!empty($data['do_shipment']) || (int) $invoice->getOrder()->getForcedDoShipmentWithInvoice()) {
                $shipment = $this->_prepareSplitShipment($invoice, $savedQtys);
                if ($shipment) {
                    $shipment->setEmailSent($invoice->getEmailSent());
                    $transactionSave->addObject($shipment);
                }
            }
            $transactionSave->save();	        
			 
            //call pick up voucher
            if($invoice->getDeliveryPickup() == 'pickup') 
            {
                Mage::getModel('pickupvoucher/voucher')->getPickupVoucher($invoice);
            }
			
            if (isset($shippingResponse) && $shippingResponse->hasErrors()) {
                $this->_getSession()->addError($this->__('The invoice '.$invoice->getIncrementId().' and the shipment '.$shipment->getIncrementId().' have been created. The shipping label cannot be created at the moment.'));
                Mage::log('OrderId:'.$invoice->getOrderId().' InvoiceId:'.$invoice->getIncrementId().' ShipmentId:'.$shipment->getIncrementId(), null, 'icube_invoice.log');
            } elseif (!empty($data['do_shipment'])) {
                $this->_getSession()->addSuccess($this->__('The invoice '.$invoice->getIncrementId().' and shipment '.$shipment->getIncrementId().' have been created.'));
                Mage::log('OrderId:'.$invoice->getOrderId().' InvoiceId:'.$invoice->getIncrementId().' ShipmentId:'.$shipment->getIncrementId(), null, 'icube_invoice.log');
            } else {
                $this->_getSession()->addSuccess($this->__('The invoice '.$invoice->getIncrementId().' has been created.'));
                Mage::log('OrderId:'.$invoice->getOrderId().' InvoiceId:'.$invoice->getIncrementId(), null, 'icube_invoice.log');
            }

            // send invoice/shipment emails
            $comment = '';
            if (isset($data['comment_customer_notify'])) {
                $comment = $data['comment_text'];
            }
            try {
                $invoice->sendEmail(!empty($data['send_email']), $comment);
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($this->__('Unable to send the invoice email.'));
            }
            if ($shipment) {
                try {
                    $shipment->sendEmail(!empty($data['send_email']));
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($this->__('Unable to send the shipment email.'));
                }
            }
            Mage::unregister('current_invoice');
         
        }          
    }
    
    protected function _prepareSplitShipment($invoice, $savedQtys)
    {
        $shipment = Mage::getModel('sales/service_order', $invoice->getOrder())->prepareShipment($savedQtys);
        if (!$shipment->getTotalQty()) {
            return false;
        }


        $shipment->register();
        $tracks = $this->getRequest()->getPost('tracking');
        if ($tracks) {
            foreach ($tracks as $data) {
                $track = Mage::getModel('sales/order_shipment_track')
                    ->addData($data);
                $shipment->addTrack($track);
            }
        }
        return $shipment;
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