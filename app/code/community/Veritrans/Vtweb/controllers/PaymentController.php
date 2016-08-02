<?php
/**
 * Veritrans VT Web Payment Controller
 *
 * @category   Mage
 * @package    Mage_Veritrans_VtWeb_PaymentController
 * @author     Kisman Hong (plihplih.com), Ismail Faruqi (@ifaruqi_jpn)
 * This class is used for handle redirection after placing order.
 * function redirectAction -> redirecting to Veritrans VT Web
 * function responseAction -> when payment at Veritrans VT Web is completed or
 * failed, the page will be redirected to this function,
 * you must set this url in your Veritrans MAP merchant account.
 * http://yoursite.com/vtweb/payment/notification
 */

require_once(Mage::getBaseDir('lib') . '/veritrans-php/Veritrans.php');

class Veritrans_Vtweb_PaymentController
    extends Mage_Core_Controller_Front_Action {

  /**
   * @return Mage_Checkout_Model_Session
   */
  protected function _getCheckout() {
    return Mage::getSingleton('checkout/session');
  }

  // The redirect action is triggered when someone places an order,
  // redirecting to Veritrans payment page.
  public function redirectAction() {
    $orderIncrementId = $this->_getCheckout()->getLastRealOrderId();
    $order = Mage::getModel('sales/order')
        ->loadByIncrementId($orderIncrementId);
    $sessionId = Mage::getSingleton('core/session');

    /* send an order email when redirecting to payment page although payment
       has not been completed. */
    $order->setState(Mage::getStoreConfig('payment/vtweb/'),true,
        'New order, waiting for payment.');
    $order->sendNewOrderEmail();
    $order->setEmailSent(true);

    $api_version = Mage::getStoreConfig('payment/vtweb/api_version');
    $payment_type = Mage::getStoreConfig('payment/vtweb/payment_types');
    $enable_installment = Mage::getStoreConfig('payment/vtweb/enable_installment');
    $is_enabled_bni = Mage::getStoreConfig('payment/vtweb/enable_installment_bni');
    $is_enabled_mandiri = Mage::getStoreConfig('payment/vtweb/enable_installment_mandiri');

    Veritrans_Config::$isProduction =
        Mage::getStoreConfig('payment/vtweb/environment') == 'production'
        ? true : false;

    Veritrans_Config::$serverKey =
        Mage::getStoreConfig('payment/vtweb/server_key_v2');
    
    Veritrans_Config::$is3ds =
        Mage::getStoreConfig('payment/vtweb/enable_3d_secure') == '1'
        ? true : false;

    Veritrans_Config::$isSanitized =
        Mage::getStoreConfig('payment/vtweb/enable_sanitized') == '1'
        ? true : false; 

    $transaction_details = array();
    $prefix = Mage::getStoreConfig('payment/vtweb/prefix_id');
    $transaction_details['order_id'] = $prefix.$orderIncrementId;

    $order_billing_address = $order->getBillingAddress();
    $billing_address = array();
    $billing_address['first_name']   = $order_billing_address->getFirstname();
    $billing_address['last_name']    = $order_billing_address->getLastname();
    $billing_address['address']      = $order_billing_address->getStreet(1);
    $billing_address['city']         = $order_billing_address->getCity();
    $billing_address['postal_code']  = $order_billing_address->getPostcode();
    $billing_address['country_code'] = $this->convert_country_code($order_billing_address->getCountry());
    $billing_address['phone']        = $order_billing_address->getTelephone();

    $order_shipping_address = $order->getShippingAddress();
    $shipping_address = array();
    $shipping_address['first_name']   = $order_shipping_address->getFirstname();
    $shipping_address['last_name']    = $order_shipping_address->getLastname();
    $shipping_address['address']      = $order_shipping_address->getStreet(1);
    $shipping_address['city']         = $order_shipping_address->getCity();
    $shipping_address['postal_code']  = $order_shipping_address->getPostcode();
    $shipping_address['phone']        = $order_shipping_address->getTelephone();
    $shipping_address['country_code'] =
        $this->convert_country_code($order_shipping_address->getCountry());

    $customer_details = array();
    $customer_details['billing_address']  = $billing_address;
    $customer_details['shipping_address'] = $shipping_address;
    $customer_details['first_name']       = $order_billing_address
        ->getFirstname();
    $customer_details['last_name']        = $order_billing_address
        ->getLastname();
    $customer_details['email']            = $order_billing_address->getEmail();
    $customer_details['phone']            = $order_billing_address
        ->getTelephone();

    $items               = $order->getAllItems();
    $shipping_amount     = $order->getShippingAmount();
    $shipping_tax_amount = $order->getShippingTaxAmount();
    $tax_amount = $order->getTaxAmount();
      $handling_fee = $order->getBaseFoomanSurchargeAmount();
      $gc_amount = $order->getBaseGiftCardsAmount()*-1;

    $item_details = array();


    foreach ($items as $each) {
      $item = array(
          'id'       => $each->getProductId(),
          'price'    => $each->getPrice(),
          'quantity' => $each->getQtyToInvoice(),
          'name'     => $this->repString($this->getName($each->getName()))
        );
      
      if ($item['quantity'] == 0) continue;
      // error_log(print_r($each->getProductOptions(), true));
      $item_details[] = $item;
    }
    
    $num_products = count($item_details);

    unset($each);

    if ($order->getDiscountAmount() != 0) {
      $couponItem = array(
          'id' => 'DISCOUNT',
          'price' => $order->getDiscountAmount(),
          'quantity' => 1,
          'name' => 'DISCOUNT'
        );
      $item_details[] = $couponItem;
    }

    if ($shipping_amount > 0) {
      $shipping_item = array(
          'id' => 'SHIPPING',
          'price' => $shipping_amount,
          'quantity' => 1,
          'name' => 'Shipping Cost'
        );
      $item_details[] =$shipping_item;
    }
    
    if ($shipping_tax_amount > 0) {
      $shipping_tax_item = array(
          'id' => 'SHIPPING_TAX',
          'price' => $shipping_tax_amount,
          'quantity' => 1,
          'name' => 'Shipping Tax'
        );
      $item_details[] = $shipping_tax_item;
    }

    if ($tax_amount > 0) {
      $tax_item = array(
          'id' => 'TAX',
          'price' => $tax_amount,
          'quantity' => 1,
          'name' => 'Tax'
        );
      $item_details[] = $tax_item;
    }

      if ($gc_amount < 0) {
          $item = array(
              'id' => 'GIFTCARD',
              'price' => $gc_amount,
              'quantity' => 1,
              'name' => 'Giftcard Amount'
          );
          $item_details[] = $item;
      }

      if ($handling_fee > 0) {
          $handlingfee_item = array(
              'id' => 'HANDLING_FEE',
              'price' => $handling_fee,
              'quantity' => 1,
              'name' => 'Handling Fee'
          );
          $item_details[] = $handlingfee_item;
      }


      // convert to IDR
    $current_currency = Mage::app()->getStore()->getCurrentCurrencyCode();
    if ($current_currency != 'IDR') {
      $conversion_func = function ($non_idr_price) {
          return $non_idr_price *
              Mage::getStoreConfig('payment/vtweb/conversion_rate');
        };
      foreach ($item_details as &$item) {
        $item['price'] =
            intval(round(call_user_func($conversion_func, $item['price'])));
      }
      unset($item);
    }
    else {
      foreach ($item_details as &$each) {
        $each['price'] = (int) $each['price'];
      }
      unset($each);
    }

    $list_enable_payments = array();

    if (Mage::getStoreConfig('payment/vtweb/enable_creditcard') == '1') {
      $list_enable_payments[] = 'credit_card';
    }
    if (Mage::getStoreConfig('payment/vtweb/enable_cimbclick') == '1') {
      $list_enable_payments[] = 'cimb_clicks';
    }
    if (Mage::getStoreConfig('payment/vtweb/enable_mandiriclickpay') == '1') {
      $list_enable_payments[] = 'mandiri_clickpay';
    }
    if (Mage::getStoreConfig('payment/vtweb/enable_permatava') == '1') {
      $list_enable_payments[] = 'bank_transfer';
    }
    if (Mage::getStoreConfig('payment/vtweb/enable_briepay') == '1') {
      $list_enable_payments[] = 'bri_epay';
    }
      if (Mage::getStoreConfig('payment/vtweb/enable_tcash') == '1') {
          $list_enable_payments[] = 'telkomsel_cash';
      }
      if (Mage::getStoreConfig('payment/vtweb/enable_mandiriecash') == '1') {
          $list_enable_payments[] = 'mandiri_ecash';
      }
    if (Mage::getStoreConfig('payment/vtweb/enable_xltunai') == '1') {
      $list_enable_payments[] = 'xl_tunai';
    }
    if (Mage::getStoreConfig('payment/vtweb/enable_mandiribill') == '1') {
      $list_enable_payments[] = 'echannel';
    }
    if (Mage::getStoreConfig('payment/vtweb/enable_bbmmoney') == '1') {
      $list_enable_payments[] = 'bbm_money';
    }
    if (Mage::getStoreConfig('payment/vtweb/enable_indomaret') == '1') {
      $list_enable_payments[] = 'cstore';
    }
    if (Mage::getStoreConfig('payment/vtweb/enable_dompetku') == '1') {
      $list_enable_payments[] = 'indosat_dompetku';
    }


    $payloads = array();
    $payloads['transaction_details'] = $transaction_details;
    $payloads['item_details']        = $item_details;
    $payloads['customer_details']    = $customer_details;
    $payloads['vtweb']               = array('enabled_payments'=> $list_enable_payments);

    $isWarning = false;
    $isInstallment = false;
    
    $totalPrice = 0;

    foreach ($item_details as $item) {
      $totalPrice += $item['price'] * $item['quantity'];
    }

    if ($enable_installment == 'allProducts') {
      $installment_terms = array();
      
      if ($is_enabled_bni == 1) {
        $bni_term = Mage::getStoreConfig('payment/vtweb/installment_bni_term');
        $bni_term_array = explode(',', $bni_term);

        if (strlen($bni_term) != 0) {
          $isInstallment = true;
          $installment_terms['bni'] = $bni_term_array;
        }
      }

      if ($is_enabled_mandiri == 1) {
        $mandiri_term = Mage::getStoreConfig('payment/vtweb/installment_mandiri_term');
        $mandiri_term_array = explode(',', $mandiri_term);
        
        if (strlen($mandiri_term) != 0) {
          $isInstallment = true;
          $installment_terms['mandiri'] = $mandiri_term_array;
        }
      }

      $payment_options = array(
        'installment' => array(
          'required' => false,
          'installment_terms' => $installment_terms
        )
      );

      if ($isInstallment && ($totalPrice >= 500000)) {
        $payloads['vtweb']['payment_options'] = $payment_options;
      }
    }
    else if ($enable_installment == 'certainProducts') {
      if ($num_products == 1) {
        $productOptions = $items[0]->getProductOptions();
        
        if (array_key_exists('attributes_info', $productOptions)) {
          foreach ($productOptions['attributes_info'] as $attribute) {
            if (in_array('Payment', $attribute)) {
              $installment_value = explode(',', $attribute['value']);

              if (strtolower($installment_value[0]) == 'installment') {
                $installment_terms = array();
                $installment_terms[strtolower($installment_value[1])] = array($installment_value[2]);

                $payment_options = array(
                  'installment' => array(
                    'required' => true,
                    'installment_terms' => $installment_terms
                  )
                );

                $isInstallment = true;

                if ($totalPrice >= 500000) {
                  $payloads['vtweb']['payment_options'] = $payment_options;
                }
              }
            }
          }

          unset($attribute);
        }
      }
      else {
        foreach ($items as $each) {
          $productOptions = $each->getProductOptions();

          if (array_key_exists('attributes_info', $productOptions)) {
            foreach ($productOptions['attributes_info'] as $attribute) {
              if (in_array('Payment', $attribute)) {
                $installment_value = explode(',', $attribute['value']);

                if (strtolower($installment_value[0]) == 'installment') {
                  $isWarning = true;
                }
              }
            }
          }
        }

        unset($each);
      }
    }

    try {
        Mage::log('$payloads:'.print_r($payloads,true),null,'vtweb_payloads.log',true);
      $redirUrl = Veritrans_VtWeb::getRedirectionUrl($payloads);
      
      if ($isWarning) {
        $this->_getCheckout()->setMsg($redirUrl);        
        $this->_redirectUrl(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . 'vtweb/paymentwarning/warning/message/1');
      }
      else if (($totalPrice < 500000) && ($isInstallment)) {
        $this->_getCheckout()->setMsg($redirUrl);        
        $this->_redirectUrl(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . 'vtweb/paymentwarning/warning/message/2');
      }
      else {
        $this->_redirectUrl($redirUrl);
      }
    }
    catch (Exception $e) {
      error_log($e->getMessage());
      Mage::log('error:'.print_r($e->getMessage(),true),null,'vtweb.log',true);
    }
  }

  // The response action is triggered when your gateway sends back a response
  // after processing the customer's payment, we will not update to success
  // because success is valid when notification (security reason)
  public function responseAction() {
    //var_dump($_POST); use for debugging value.
    if($_GET['order_id']) {

        $prefix = Mage::getStoreConfig('payment/vtweb/prefix_id');
        Mage::log('GET:'.print_r($_GET,true),null,'responseAction.log',true);
        $orderId = $_GET['order_id']; // Generally sent by gateway
        $orderId = str_replace($prefix,'',$orderId);
        $status = $_GET['status_code'];
        $transStatus = $_GET['transaction_status'];
        if($status == '200' && !is_null($orderId) && $orderId != '') {
            // Redirected by Veritrans, if ok

            
            //This is for mandiri promo by Sutrisno on May Day
                //Create Mandiri Giftcard
                /*
                $getSession = Mage::getSingleton('core/session')->getManPromo(); 

                if ($getSession == 'ok') {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $custid = $customerData->getId();
                $expire = date('Y-m-d', strtotime("+2 week"));
                $today = date('Y-m-d');
                $gcpool = $readCon->fetchOne('SELECT code FROM enterprise_giftcardaccount_pool WHERE status = 0 AND campaign_name = "promomandiri"');

                $gift_card = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');
                $gift_card
                    ->setCode($gcpool)
                    ->setStatus($gift_card::STATUS_ENABLED)
                    ->setDateExpires($expire)
                    ->setCustomerId($custid)
                    ->setWebsiteId(1)
                    ->setState($gift_card::STATE_AVAILABLE)
                    ->setIsRedeemable(0)
                    ->setCategoryIdsExclusion(5312)
                    ->setMinPurchaseValue(100000)
                    ->setValidFromDate($today)
                    ->setValidToDate($expire)
                    ->setRestrictCombine(1)
                    ->setCampaignName(promomandiri)
                    ->setBalance(50000)
                    ->setBinType(MANDIRI);

                $gift_card->save();

                $writeCon->query('UPDATE enterprise_giftcardaccount_pool SET status = 1 WHERE code = "'.$gcpool.'"');

                //send email voucher
                $customer = Mage::getSingleton('customer/session')->getCustomer();
                $emailcust = $customer->getEmail();
                $namecust = $customer->getName();
                $firstname = $customer->getFirstname();
                $expire = date('d-m-Y', strtotime("+2 week"));

                $storeId = Mage::app()->getStore()->getStoreId();
                $mailer = Mage::getModel('core/email_template_mailer');
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($emailcust, $firstname);
                $mailer->addEmailInfo($emailInfo);
                $mailer->setSender(Mage::getStoreConfig('sales_email/order/identity', $storeId));
                $mailer->setStoreId($storeId);
                $mailer->setTemplateId('ruparupa_customer_account_new_giftcard_template');
                $mailer->setTemplateParams(array(
                        'voucher'      => $gcpool_code,
                        'fullname'     => $namecust,
                        'expire'       => $expire,
                        'name'         => $firstname
                    )
                );
                $mailer->send(); } */

            Mage::getSingleton('checkout/session')->unsQuoteId();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=>true));
            
        } else if($status == '201' && !is_null($orderId) && $orderId != '') {
            //set order to review state
            $new_order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            $new_order->setStatus(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW);
            $new_order->save();

            // Redirected by Veritrans, if ok
            Mage::getSingleton('checkout/session')->unsQuoteId();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=>true));
        }
        else if($status == '202' && $transStatus == 'deny' && !is_null($orderId) && $orderId != '') {
            // There is a problem in the response we got
//                $this->cancelAction();
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            $order->addStatusHistoryComment('Gateway has declined the payment', FALSE);
            $order->save();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure'=>true));
        }
        else {
            // Back to merchant - reorder
            Mage_Core_Controller_Varien_Action::_redirect('vtweb/payment/reorder', array('_secure'=>true));
        }
    } else if ($_GET['id']) { // BCA klikpay
        Veritrans_Config::$serverKey = Mage::getStoreConfig('payment/vtbcaklikpay/server_key_v2');
        $data = Veritrans_Transaction::status($_GET['id']);
        if($data->transaction_status == Veritrans_Vtweb_Model_Standard::TRX_STATUS_SETTLEMENT) {
            Mage::getSingleton('checkout/session')->unsQuoteId();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=>true));
        } else {
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            $order->addStatusHistoryComment('Gateway has declined the payment', FALSE);
            $order->save();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure'=>true));
        }
    }
    else{
      Mage_Core_Controller_Varien_Action::_redirect('');
    }
  }

  // Veritrans will send notification of the payment status, this is only way we
  // make sure that the payment is successed, if success send the item(s) to
  // customer :p
  public function notificationAction() {

    Veritrans_Config::$isProduction =
        Mage::getStoreConfig('payment/vtweb/environment') == 'production' ? true : false;
    Veritrans_Config::$serverKey = Mage::getStoreConfig('payment/vtweb/server_key_v2');
    $notif = new Veritrans_Notification();
    Mage::log('get status result'.print_r($notif,true),null,'vtweb.log',true);

    $prefix = Mage::getStoreConfig('payment/vtweb/prefix_id');
    $orderId = str_replace($prefix,'',$notif->order_id);
      if (strpos($orderId,'-') !== false) {
          $arrOrderId = explode("-",$orderId);
          $orderId = $arrOrderId[0];
      }
    $order = Mage::getModel('sales/order');
    $order->loadByIncrementId($orderId);

    $transaction = $notif->transaction_status;
    $fraud = $notif->fraud_status;
    $payment_type = $notif->payment_type;

    if ($transaction == 'capture') {
        $order->setInstallmentTenor($notif->installment_term);
        if ($fraud == 'challenge') {
          $order->setStatus(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW);
        }
        else if ($fraud == 'accept') {
          // ---- ICUBE UPDATE ----
          // ---- comment out default veritrans code ----
          // $invoice = $order->prepareInvoice()
          //   ->setTransactionId($order->getId())
          //   ->addComment('Payment successfully processed by Veritrans.')
          //   ->register()
          //   ->pay();

          // $transaction_save = Mage::getModel('core/resource_transaction')
          //   ->addObject($invoice)
          //   ->addObject($invoice->getOrder());

          // $transaction_save->save();

          //Icube Update - call Model split invoice
		  Mage::getModel('icube_invoice/service_split')->invoiceSplitOrder($order);

          $order->setStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
          //This function is disabled by Sutrisno 21-3-2016 to prevent double email sent
          /*$order->sendOrderUpdateEmail(true,
              'Thank you, your payment is successfully processed.');*/
        }
    }
    else if ($transaction == 'cancel' || $transaction == 'deny' ) {
       $order->setStatus(Mage_Sales_Model_Order::STATE_CANCELED);
        $order->addStatusToHistory(Mage_Sales_Model_Order::STATE_CANCELED);
        $order->addStatusHistoryComment('Payment has been canceled.', FALSE);
    }   
   else if ($transaction == 'settlement') {
      
      // Icube Update - send email VA order payed
      if($payment_type == 'bank_transfer')
      {
	    $storeId = $order->getStore()->getId();
	    $mailer = Mage::getModel('core/email_template_mailer');
	    $emailInfo = Mage::getModel('core/email_info');
	    $emailInfo->addTo($order->getCustomerEmail(), $order->getCustomerName());
	    $mailer->addEmailInfo($emailInfo);
	      // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig('sales_email/order/identity', $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId('sales_email_payment_confirm_template');
        $mailer->setTemplateParams(array('order' => $order));
        $mailer->send();
	  }
	  else
	  {
		  // Icube Update - send default magento email for other payment
		  $order->sendOrderUpdateEmail(true,'Thank you, your payment is successfully processed.');
	  }
      
      if($payment_type != 'credit_card'){
          // ---- ICUBE UPDATE ----
          // ---- comment out default veritrans code ----
          // $invoice = $order->prepareInvoice()
          //   ->setTransactionId($order->getId())
          //   ->addComment('Payment successfully processed by Veritrans.')
          //   ->register()
          //   ->pay();

          // $transaction_save = Mage::getModel('core/resource_transaction')
          //   ->addObject($invoice)
          //   ->addObject($invoice->getOrder());

          // $transaction_save->save();

          //Icube Update - call Model split invoice
		  Mage::getModel('icube_invoice/service_split')->invoiceSplitOrder($order);

          $order->setStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
      }
    }
   else if ($transaction == 'pending') {
     $order->setStatus(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);
//     $order->sendOrderUpdateEmail(true, 'Thank you, your payment is pending.');
    }
    else if ($transaction == 'cancel') {
     $order->setStatus(Mage_Sales_Model_Order::STATE_CANCELED);
    }
    else if ($transaction == 'expire') {
        $order->setStatus(Veritrans_Vtweb_Model_Standard::ORDER_STATUS_EXPIRE);

        //this code is added by Sutrisno 21-3-2016 to send expire email to customer
        $storeId = $order->getStore()->getId();
        $reorder = $this->getUrl('sales/order/reorder', array('order_id' => $order->getId()));
        $customerName = $order->getCustomerName();
        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($order->getCustomerEmail(), $customerName);
        $mailer->addEmailInfo($emailInfo);
        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig('sales_email/order/identity', $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId('sales_email_payment_expire_template');
        $mailer->setTemplateParams(array(
                    'order'      => $order,
                    'reorder'    => $reorder
                )
            );
        //$mailer->setTemplateParams(array('order' => $order));
        $mailer->send();
    }
    else {
      $order->setStatus(Mage_Sales_Model_Order::STATUS_FRAUD);
    }
    $order->save();
  }

  // The cancel action is triggered when an order is to be cancelled
  public function cancelAction() {
    if (Mage::getSingleton('checkout/session')->getLastRealOrderId()) {
        $order = Mage::getModel('sales/order')->loadByIncrementId(
            Mage::getSingleton('checkout/session')->getLastRealOrderId());
        if($order->getId()) {
      // Flag the order as 'cancelled' and save it
          $order->cancel()->setState(Mage_Sales_Model_Order::STATE_CANCELED,
              true, 'Gateway has declined the payment.')->save();
        }
    }
  }

  /**
   * Convert 2 digits coundry code to 3 digit country code
   *
   * @param String $country_code Country code which will be converted
   */
  public function convert_country_code( $country_code ) {

    // 3 digits country codes
    $cc_three = array(
      'AF' => 'AFG',
      'AX' => 'ALA',
      'AL' => 'ALB',
      'DZ' => 'DZA',
      'AD' => 'AND',
      'AO' => 'AGO',
      'AI' => 'AIA',
      'AQ' => 'ATA',
      'AG' => 'ATG',
      'AR' => 'ARG',
      'AM' => 'ARM',
      'AW' => 'ABW',
      'AU' => 'AUS',
      'AT' => 'AUT',
      'AZ' => 'AZE',
      'BS' => 'BHS',
      'BH' => 'BHR',
      'BD' => 'BGD',
      'BB' => 'BRB',
      'BY' => 'BLR',
      'BE' => 'BEL',
      'PW' => 'PLW',
      'BZ' => 'BLZ',
      'BJ' => 'BEN',
      'BM' => 'BMU',
      'BT' => 'BTN',
      'BO' => 'BOL',
      'BQ' => 'BES',
      'BA' => 'BIH',
      'BW' => 'BWA',
      'BV' => 'BVT',
      'BR' => 'BRA',
      'IO' => 'IOT',
      'VG' => 'VGB',
      'BN' => 'BRN',
      'BG' => 'BGR',
      'BF' => 'BFA',
      'BI' => 'BDI',
      'KH' => 'KHM',
      'CM' => 'CMR',
      'CA' => 'CAN',
      'CV' => 'CPV',
      'KY' => 'CYM',
      'CF' => 'CAF',
      'TD' => 'TCD',
      'CL' => 'CHL',
      'CN' => 'CHN',
      'CX' => 'CXR',
      'CC' => 'CCK',
      'CO' => 'COL',
      'KM' => 'COM',
      'CG' => 'COG',
      'CD' => 'COD',
      'CK' => 'COK',
      'CR' => 'CRI',
      'HR' => 'HRV',
      'CU' => 'CUB',
      'CW' => 'CUW',
      'CY' => 'CYP',
      'CZ' => 'CZE',
      'DK' => 'DNK',
      'DJ' => 'DJI',
      'DM' => 'DMA',
      'DO' => 'DOM',
      'EC' => 'ECU',
      'EG' => 'EGY',
      'SV' => 'SLV',
      'GQ' => 'GNQ',
      'ER' => 'ERI',
      'EE' => 'EST',
      'ET' => 'ETH',
      'FK' => 'FLK',
      'FO' => 'FRO',
      'FJ' => 'FJI',
      'FI' => 'FIN',
      'FR' => 'FRA',
      'GF' => 'GUF',
      'PF' => 'PYF',
      'TF' => 'ATF',
      'GA' => 'GAB',
      'GM' => 'GMB',
      'GE' => 'GEO',
      'DE' => 'DEU',
      'GH' => 'GHA',
      'GI' => 'GIB',
      'GR' => 'GRC',
      'GL' => 'GRL',
      'GD' => 'GRD',
      'GP' => 'GLP',
      'GT' => 'GTM',
      'GG' => 'GGY',
      'GN' => 'GIN',
      'GW' => 'GNB',
      'GY' => 'GUY',
      'HT' => 'HTI',
      'HM' => 'HMD',
      'HN' => 'HND',
      'HK' => 'HKG',
      'HU' => 'HUN',
      'IS' => 'ISL',
      'IN' => 'IND',
      'ID' => 'IDN',
      'IR' => 'RIN',
      'IQ' => 'IRQ',
      'IE' => 'IRL',
      'IM' => 'IMN',
      'IL' => 'ISR',
      'IT' => 'ITA',
      'CI' => 'CIV',
      'JM' => 'JAM',
      'JP' => 'JPN',
      'JE' => 'JEY',
      'JO' => 'JOR',
      'KZ' => 'KAZ',
      'KE' => 'KEN',
      'KI' => 'KIR',
      'KW' => 'KWT',
      'KG' => 'KGZ',
      'LA' => 'LAO',
      'LV' => 'LVA',
      'LB' => 'LBN',
      'LS' => 'LSO',
      'LR' => 'LBR',
      'LY' => 'LBY',
      'LI' => 'LIE',
      'LT' => 'LTU',
      'LU' => 'LUX',
      'MO' => 'MAC',
      'MK' => 'MKD',
      'MG' => 'MDG',
      'MW' => 'MWI',
      'MY' => 'MYS',
      'MV' => 'MDV',
      'ML' => 'MLI',
      'MT' => 'MLT',
      'MH' => 'MHL',
      'MQ' => 'MTQ',
      'MR' => 'MRT',
      'MU' => 'MUS',
      'YT' => 'MYT',
      'MX' => 'MEX',
      'FM' => 'FSM',
      'MD' => 'MDA',
      'MC' => 'MCO',
      'MN' => 'MNG',
      'ME' => 'MNE',
      'MS' => 'MSR',
      'MA' => 'MAR',
      'MZ' => 'MOZ',
      'MM' => 'MMR',
      'NA' => 'NAM',
      'NR' => 'NRU',
      'NP' => 'NPL',
      'NL' => 'NLD',
      'AN' => 'ANT',
      'NC' => 'NCL',
      'NZ' => 'NZL',
      'NI' => 'NIC',
      'NE' => 'NER',
      'NG' => 'NGA',
      'NU' => 'NIU',
      'NF' => 'NFK',
      'KP' => 'MNP',
      'NO' => 'NOR',
      'OM' => 'OMN',
      'PK' => 'PAK',
      'PS' => 'PSE',
      'PA' => 'PAN',
      'PG' => 'PNG',
      'PY' => 'PRY',
      'PE' => 'PER',
      'PH' => 'PHL',
      'PN' => 'PCN',
      'PL' => 'POL',
      'PT' => 'PRT',
      'QA' => 'QAT',
      'RE' => 'REU',
      'RO' => 'SHN',
      'RU' => 'RUS',
      'RW' => 'EWA',
      'BL' => 'BLM',
      'SH' => 'SHN',
      'KN' => 'KNA',
      'LC' => 'LCA',
      'MF' => 'MAF',
      'SX' => 'SXM',
      'PM' => 'SPM',
      'VC' => 'VCT',
      'SM' => 'SMR',
      'ST' => 'STP',
      'SA' => 'SAU',
      'SN' => 'SEN',
      'RS' => 'SRB',
      'SC' => 'SYC',
      'SL' => 'SLE',
      'SG' => 'SGP',
      'SK' => 'SVK',
      'SI' => 'SVN',
      'SB' => 'SLB',
      'SO' => 'SOM',
      'ZA' => 'ZAF',
      'GS' => 'SGS',
      'KR' => 'KOR',
      'SS' => 'SSD',
      'ES' => 'ESP',
      'LK' => 'LKA',
      'SD' => 'SDN',
      'SR' => 'SUR',
      'SJ' => 'SJM',
      'SZ' => 'SWZ',
      'SE' => 'SWE',
      'CH' => 'CHE',
      'SY' => 'SYR',
      'TW' => 'TWN',
      'TJ' => 'TJK',
      'TZ' => 'TZA',
      'TH' => 'THA',
      'TL' => 'TLS',
      'TG' => 'TGO',
      'TK' => 'TKL',
      'TO' => 'TON',
      'TT' => 'TTO',
      'TN' => 'TUN',
      'TR' => 'TUR',
      'TM' => 'TKM',
      'TC' => 'TCA',
      'TV' => 'TUV',
      'UG' => 'UGA',
      'UA' => 'UKR',
      'AE' => 'ARE',
      'GB' => 'GBR',
      'US' => 'USA',
      'UY' => 'URY',
      'UZ' => 'UZB',
      'VU' => 'VUT',
      'VA' => 'VAT',
      'VE' => 'VEN',
      'VN' => 'VNM',
      'WF' => 'WLF',
      'EH' => 'ESH',
      'WS' => 'WSM',
      'YE' => 'YEM',
      'ZM' => 'ZMB',
      'ZW' => 'ZWE'
    );

    // Check if country code exists
    if( isset( $cc_three[ $country_code ] ) && $cc_three[ $country_code ] != '' ) {
      $country_code = $cc_three[ $country_code ];
    }

    return $country_code;
  }

    public function reorderAction() {
        $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
        if($order->canCancel()){
            $order->cancel();
            $order->addStatusToHistory(Mage_Sales_Model_Order::STATE_CANCELED);
            $order->addStatusHistoryComment('Canceled on VT Web page.', FALSE);
            $order->save();
        }
        $entity_id = $order->getEntityId();
        $this->_redirect('sales/order/reorder/order_id/'.$entity_id);
    }

    private function invoiceOrder($transactNo){
        $order = Mage::getModel('sales/order')->loadByIncrementId($transactNo);
        if($order->canInvoice()) {
            $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
            if ($invoice->getTotalQty()) {
                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                $invoice->register();
                $transactionSave = Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());
                $transactionSave->save();
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();
            }
            else {
                Mage::log('Cannot create an invoice without products.', null, 'veritrans.log', true);
            }
        }
        else {
            Mage::log('Cannot create an invoice', null, 'veritrans.log', true);
        }
    }

    public function splitInvoiceAction()
    {
	  //.../vtweb/payment/splitInvoice?id=100000157
      $order = Mage::getModel('sales/order')->loadByIncrementId($this->getRequest()->getParam('id'));
	  Mage::getModel('icube_invoice/service_split')->invoiceSplitOrder($order);
    }

    private function repString($str){
        return preg_replace("/[^a-zA-Z0-9]+/", " ", $str);
    }

    private function getName($s)
    {
        $max_length = 20;
        if (strlen($s) > $max_length) {
            $offset = ($max_length - 3) - strlen($s);
            $s      = substr($s, 0, strrpos($s, ' ', $offset));
        }
        return $s;
    }
}

?>