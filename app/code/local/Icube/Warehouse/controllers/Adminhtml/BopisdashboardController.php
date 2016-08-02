<?php
 
class Icube_Warehouse_Adminhtml_BopisdashboardController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Icube'))->_title($this->__('BOPIS Order'));
        $this->loadLayout();
        $this->_setActiveMenu('icube/bopisdashboard');
        $this->_addContent($this->getLayout()->createBlock('icube_warehouse/adminhtml_bopisdashboard'));
        $this->renderLayout();
    }
 
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('icube_warehouse/adminhtml_bopisdashboard_grid')->toHtml()
        );
    }
 
    public function exportIcubeCsvAction()
    {
        $fileName = 'bopisdashboard_icube.csv';
        $grid = $this->getLayout()->createBlock('icube_warehouse/adminhtml_bopisdashboard_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
 
    public function exportIcubeExcelAction()
    {
        $fileName = 'bopisdashboard_icube.xml';
        $grid = $this->getLayout()->createBlock('icube_warehouse/adminhtml_bopisdashboard_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('icube/bopisdashboard');

    }

    public function setReadyForPickupAction()
    {
        // Update status here
        $ids = $this->getRequest()->getParam('invoice_id');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('icube_warehouse')->__('Please select invoice(s).'));
        } else {
            try {
                $invoices = Mage::getModel('sales/order_invoice')->getCollection()
                                ->addFieldToFilter('entity_id', array('in' => $ids));
                foreach ($invoices as $invoice) {
                    if ($invoice->getReceiptId() != NULL) {
                        if ($invoice->getInvoiceStatus() != 'PICKED UP BY CUSTOMER' && $invoice->getInvoiceStatus() != 'READY FOR PICKUP') {
                            $invoice->setInvoiceStatus('READY FOR PICKUP');
                            $readyToEmail = $this->checkBopisReadyOrPicked($invoice);
                            if($readyToEmail) {
	                            $date = strtotime("+3 day");
								$expDate= date('d/m/Y', $date);
								
								// Get Store Pickup point
								$location = Mage::getModel('pickuppoint/pickuppoint')->load($invoice->getPickupLocationCode(), 'pickup_code');

	                            $order = $invoice->getOrder();
	                            $storeId = $order->getStore()->getId();
							    $mailer = Mage::getModel('core/email_template_mailer');
							    $emailInfo = Mage::getModel('core/email_info');
							    $emailInfo->addTo($order->getCustomerEmail(), $order->getCustomerName());
							    $mailer->addEmailInfo($emailInfo);
							      // Set all required params and send emails
						        $mailer->setSender(Mage::getStoreConfig('sales_email/order/identity', $storeId));
						        $mailer->setStoreId($storeId);
						        $mailer->setTemplateId('sales_email_bopis_ready_template');
						        $mailer->setTemplateParams(array('order' => $order, 'invoices'=>$readyToEmail, 'expDate'=>$expDate, 'location'=>$location));
						        $mailer->send();
                            }
                            $invoice->save();
                            Mage::getSingleton('core/session')->addSuccess('Invoice #'.$invoice->getIncrementId().' was set to "'.$invoice->getInvoiceStatus().'"');
                        }
                        else {
                            Mage::getSingleton('core/session')->addError('Invoice #'.$invoice->getIncrementId().' was not set to "Ready for Pick Up" because the status is "'.$invoice->getInvoiceStatus().'" already!');
                        }
                    }
                    else {
                        Mage::getSingleton('core/session')->addError('Invoice #'.$invoice->getIncrementId().' was not set to "Ready for Pick Up" because it has no Receipt ID!');
                    }
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
         
        $this->_redirect('*/*/index');
    }

    public function setPickedupAction()
    {
        // Update status here
        $ids = $this->getRequest()->getParam('invoice_id');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('icube_warehouse')->__('Please select invoice(s).'));
        } else {
            try {
                $invoices = Mage::getModel('sales/order_invoice')->getCollection()
                                ->addFieldToFilter('entity_id', array('in' => $ids));
                foreach ($invoices as $invoice) {
                    if ($invoice->getInvoiceStatus() == 'READY FOR PICKUP') {
                        $invoice->setInvoiceStatus('PICKED UP BY CUSTOMER');
                        $readyToEmail = $this->checkBopisReadyOrPicked($invoice);
                            if($readyToEmail) {
								$date = date('d/m/Y');

	                            $order = $invoice->getOrder();
	                            $storeId = $order->getStore()->getId();
							    $mailer = Mage::getModel('core/email_template_mailer');
							    $emailInfo = Mage::getModel('core/email_info');
							    $emailInfo->addTo($order->getCustomerEmail(), $order->getCustomerName());
							    $mailer->addEmailInfo($emailInfo);
							      // Set all required params and send emails
						        $mailer->setSender(Mage::getStoreConfig('sales_email/order/identity', $storeId));
						        $mailer->setStoreId($storeId);
						        $mailer->setTemplateId('sales_email_bopis_picked_template');
						        $mailer->setTemplateParams(array('order' => $order, 'invoices'=>$readyToEmail, 'date'=>$date));
						        $mailer->send();
                            }
                            $invoice->save();
                        Mage::getSingleton('core/session')->addSuccess('Invoice #'.$invoice->getIncrementId().' was set to "'.$invoice->getInvoiceStatus().'"');
                    }
                    else {
                        Mage::getSingleton('core/session')->addError('Invoice #'.$invoice->getIncrementId().' was not set to "Picked Up by Customer" because the status is not "Ready For Pick Up"!');
                    }
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
         
        $this->_redirect('*/*/index');
    }

    public function setReceiptIdAction()
    {
        // Update status here
        $ids = $this->getRequest()->getParam('invoice_id');
        $receiptId = $this->getRequest()->getParam('receipt_id');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('icube_warehouse')->__('Please select invoice(s).'));
        } else {
            if(count($ids) != 1) {
                Mage::getSingleton('core/session')->addError('You can only select 1 invoice to update Receipt ID!');
            }
            else{
                try {
                    $invoices = Mage::getModel('sales/order_invoice')->getCollection()
                                    ->addFieldToFilter('entity_id', array('in' => $ids));
                    foreach ($invoices as $invoice) {
                        if ($receiptId != NULL && $receiptId != '') {
                            $invoice->setReceiptId($receiptId)
                                    ->save();
                            Mage::getSingleton('core/session')->addSuccess('Invoice #'.$invoice->getIncrementId().' Receipt ID : '. $receiptId);
                        }
                        else {
                            Mage::getSingleton('core/session')->addError('There was an error entering Receipt ID for Invoice #'.$invoice->getIncrementId());
                        }
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }
        }
         
        $this->_redirect('*/*/index');
    }

    public function checkBopisReadyOrPicked($currentInvoice){
        $pickuppoint = $currentInvoice->getPickupLocationCode();
        $order = $currentInvoice->getOrder();
        
        $collection = Mage::getModel('sales/order_invoice')->getCollection()->addFieldToSelect('*')
                    //->addFieldToFilter('entity_id', array('neq' => $currentInvoice->getId()))
                    ->addFieldToFilter('order_id', $order->getId())
                    ->addFieldToFilter('pickup_location_code', $pickuppoint);

        $flag = $collection; //return collection
        foreach ($collection as $invoice) {
            if($invoice->getInvoiceStatus() != $currentInvoice->getInvoiceStatus() && $invoice->getEntityId() != $currentInvoice->getId()) {
                $flag = 0;
            }
        }
        return $flag;
    }
}