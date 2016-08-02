<?php

class Icube_Vpaymentins_PaymentController extends Mage_Core_Controller_Front_Action {
  
  /**
   * Process Action - Redirect User to veritrans payment gateway
   * 
   * <veritrans_payment_process>
   * 
   * @return  void;
   * @see   <package>/<themes>/layout/veritrans.xml
   */
  public function processAction() {
    $session = Mage::getSingleton('core/session');
    if ($session->getTokenBrowser() == null) {
      $this->_redirect('vpaymentins/payment/unauthorized');
    } else {
      $this->loadLayout();
      $this->getLayout()->getBlock('head')->setTitle($this->__('Veritrans Payment Gateway'));
      $this->renderLayout();
    }
  }
  
  /**
   * Success Action - Customer successfully paid their order.
   *
   * <veritrans_payment_success>
   *
   * @return  void;
   * @see   <package>/<themes>/layout/veritrans.xml
   */
  public function successAction() {
    $this->loadLayout();
    $this->getLayout()->getBlock('head')->setTitle($this->__('Veritrans Payment Gateway'));
    $this->renderLayout();
  }
  
  /**
   * Cancel Action - Customer cancelled their order.
   *
   * <veritrans_payment_cancel>
   *
   * @return  void;
   * @see   <package>/<themes>/layout/veritrans.xml
   */
  public function cancelAction() {
    $this->loadLayout();
    $this->getLayout()->getBlock('head')->setTitle($this->__('Veritrans Payment Gateway'));
    $this->renderLayout();
  }
  
  /**
   * Error Action
   *
   * <veritrans_payment_error>
   *
   * @return  void;
   * @see   <package>/<themes>/layout/veritrans.xml
   */
  public function errorAction() {
    $this->loadLayout();
    $this->getLayout()->getBlock('head')->setTitle($this->__('Veritrans Payment Gateway'));
    $this->renderLayout();
  }
  
  /**
   * Unauthorized Action
   *
   * <veritrans_payment_unauthorized>
   *
   * @return  void;
   * @see   <package>/<themes>/layout/veritrans.xml
   */
  public function unauthorizedAction() {
    $message = $this->__("Whoaa there!");
    Mage::getSingleton('core/session')->addError($message);
    
    $this->loadLayout();
    $this->getLayout()->getBlock('head')->setTitle($this->__('Veritrans Unauthorized'));
    $this->renderLayout();
  }
  
  /**
   * Notification Action - Handle Incoming data from veritrans
   *
   * @return  void;
   * @see   <package>/<themes>/layout/veritrans.xml
   */
  public function notificationAction() {
    $checkout = Mage::getSingleton('checkout/session');
    $postdata = Mage::app()->getRequest()->getPost();
    
      Mage::log('POST:'.print_r($postdata,true),null,'POST_notif.log',true);
  }
  
  /**
   * Create Invoice for successfull veritrans Payment
   * 
   * @param string $orderIncrementId
   */
  protected function _createInvoice($orderIncrementId){
    $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
    $itemsQty = count($order->getAllItems());
    
    $invoice = $order->prepareInvoice($itemsQty);
    $invoice->register();
    $invoice->setOrder($order);
    $invoice->setEmailSent(true);
    $invoice->getOrder()->setIsInProcess(true);
    $invoice->pay();
    $invoice->save();
    $order->save();
  
    return $invoice->getIncrementId();
  }
  
  /**
   * Clear Veritrans Session
   * 
   * @return void
   */
  protected function _clearVeritransSession() {
    $session = Mage::getSingleton('core/session');
    
    $session->unsTokenBrowser();
    $session->unsVeritransQuoteId();
  }

    protected function getQuoteGrandTotalAction() {
        try{
            $id = $this->getRequest()->getPost('id');
            $promoCode = $this->getRequest()->getPost('promo_code');

            $quote = Mage::getModel('sales/quote')->load($id);
            $quote->getPayment()->importData(array('method' => 'vpaymentins'));
            $quote->getPayment()->setPromoCode($promoCode);
            $quote->getPayment()->save();
            $quote->save();
            echo '1';
        }catch (Exception $e){
            Mage::log('ex:'.$e->getMessage(),null,'icube_vt_payment.log',true);
        }
    }

    protected function calculateQuoteAction() {
        try{
            $id = $this->getRequest()->getPost('id');
            $quote = Mage::getModel('sales/quote')->load($id);
            $quote->setTotalsCollectedFlag(false)->collectTotals();
            $quote->save();
            $gt = intval(round($quote->getGrandTotal()));
            Mage::log("GRAND TOTAL:".$gt,null,"tesss.log",true);
            echo $gt;
        }catch (Exception $e){
            Mage::log('ex:'.$e->getMessage(),null,'icube_vt_payment.log',true);
        }
    }

    protected function calculateQuoteAjaxAction() {
        try{
            $id = $this->getRequest()->getPost('id');
            $quote = Mage::getModel('sales/quote')->load($id);
            $quote->setTotalsCollectedFlag(false)->collectTotals();
            $quote->save();
            $gt = intval(round($quote->getGrandTotal()));
            Mage::log("GRAND TOTAL:".$gt,null,"icube_vt_payment.log",true);
            $totals = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('icube/customcheckout/onepage/review/totals.phtml')->toHtml();
            echo json_encode(array('gt' => $gt,'totals' => $totals));
        }catch (Exception $e){
            Mage::log('ex:'.$e->getMessage(),null,'icube_vt_payment.log',true);
        }
    }
}