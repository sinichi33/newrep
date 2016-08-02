<?php
/**
 * Veritrans VT Web Payment Controller
 *
 * @category   Mage
 * @package    Mage_Veritrans_Vtbcaklikpay_PaymentController
 * @author     Kisman Hong (plihplih.com), Ismail Faruqi (@ifaruqi_jpn)
 * This class is used for handle redirection after placing order.
 * function redirectAction -> redirecting to Veritrans VT Web
 * function responseAction -> when payment at Veritrans VT Web is completed or failed, the page will be redirected to this function, 
 * you must set this url in your Veritrans MAP merchant account. http://yoursite.com/vtbcaklikpay/payment/notification
 */

require_once(Mage::getBaseDir('lib') . '/veritrans-php/veritrans_old.php');
require_once(Mage::getBaseDir('lib') . '/veritrans-php/lib/veritrans_notification.php');

class Veritrans_Vtbcaklikpay_PaymentController extends Mage_Core_Controller_Front_Action {
    const VERITRANS_PARAM_ALPHABET_PATTERN = 'a-zA-Z';
    const VERITRANS_PARAM_NUMERIC_PATTERN  = '0-9';
    const VERITRANS_PARAM_SYMBOL_PATTERN = '\-_\'\ \,\.\@';

	/**
   * @return Mage_Checkout_Model_Session
   */
	protected function _getCheckout() {
		return Mage::getSingleton('checkout/session');
	}
	
	// The redirect action is triggered when someone places an order, redirecting to Veritrans payment page.
	public function redirectAction() {
		$orderIncrementId = $this->_getCheckout()->getLastRealOrderId();
		$order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
		$sessionId = Mage::getSingleton("core/session");
        $asAllowedPattern  = '/[^' . self::VERITRANS_PARAM_ALPHABET_PATTERN . self::VERITRANS_PARAM_NUMERIC_PATTERN
            . self::VERITRANS_PARAM_SYMBOL_PATTERN . ']*/';
		
		/* send an order email when redirecting to payment page although payment has not been completed. */
		$order->setState(Mage::getStoreConfig('payment/vtbcaklikpay/'), true, 'New order, waiting for payment.');
		$order->sendNewOrderEmail();
		$order->setEmailSent(true);
		
		$veritrans = new Veritrans;

		// general settings
		$veritrans->api_version = 2;
		// $veritrans->payment_type = ($payment_type == 'vtdirect' ? Veritrans::VT_DIRECT : Veritrans::VT_WEB);
        $veritrans->payment_type = "bca_klikpay";
        $veritrans->bca_klikpay = array("type"=>1, "description"=>"Pembelian Barang");
		$veritrans->environment = (Mage::getStoreConfig('payment/vtbcaklikpay/environment') == 'production' ? Veritrans::ENVIRONMENT_PRODUCTION : Veritrans::ENVIRONMENT_DEVELOPMENT);

		// v1-specific
		$veritrans->merchant_id = Mage::helper('vtbcaklikpay/data')->_getMerchantID();
		$veritrans->merchant_hash_key = Mage::helper('vtbcaklikpay/data')->_getMerchantHashKey();

		// v2-specific
		$veritrans->client_key = Mage::getStoreConfig('payment/vtbcaklikpay/client_key_v2');
		$veritrans->server_key = Mage::getStoreConfig('payment/vtbcaklikpay/server_key_v2');

		// $veritrans->settlement_type = '01'; // unused in the new stack
        $prefix = Mage::getStoreConfig('payment/vtweb/prefix_id');
        $veritrans->order_id = $prefix.$orderIncrementId;
		// $veritrans->session_id = $sessionId->getSessionId(); // unused in the new stack
		// Gross amount must be total of commodities price
		// $veritrans->gross_amount = (int)$order->getBaseGrandTotal(); // no need to set the gross amount in the new library

		$veritrans->required_shipping_address = 1;	
		$veritrans->billing_address_different_with_shipping_address = 1;

		$veritrans->first_name = $order->getBillingAddress()->getFirstname();
		$veritrans->last_name = $order->getBillingAddress()->getLastname();
		$veritrans->email = $order->getBillingAddress()->getEmail();
		$veritrans->address1 = $order->getBillingAddress()->getStreet(1);
		$veritrans->address2 = $order->getBillingAddress()->getStreet(2);
        $city = substr(preg_replace($asAllowedPattern, '',
                preg_replace('/(Kab.[ ]*|Kota[ ]*)/', '',
                    $order->getBillingAddress()->getCity())
            ), 0, 20
        );
		$veritrans->city = $city;
        $country_code = $order->getBillingAddress()->getCountry();
        $country = Mage::getModel('directory/country')->loadByCode($country_code);
		$veritrans->country_code = $country->getIso3Code(); // this is hard coded because magento and veritrans country code is not the same.
		$veritrans->postal_code = $order->getBillingAddress()->getPostcode();
		$veritrans->phone = $order->getBillingAddress()->getTelephone();
		
		$veritrans->shipping_first_name = $order->getShippingAddress()->getFirstname();
		$veritrans->shipping_last_name = $order->getShippingAddress()->getLastname();
		$veritrans->shipping_address1 = $order->getShippingAddress()->getStreet(1);
		$veritrans->shipping_address2 = $order->getShippingAddress()->getStreet(2);
        $city = substr(preg_replace($asAllowedPattern, '',
                preg_replace('/(Kab.[ ]*|Kota[ ]*)/', '',
                    $order->getShippingAddress()->getCity())
            ), 0, 20
        );
		$veritrans->shipping_city = $city;
        $country_code = $order->getBillingAddress()->getCountry();
        $country = Mage::getModel('directory/country')->loadByCode($country_code);
		$veritrans->shipping_country_code = $country->getIso3Code(); // this is hard coded because magento and veritrans country code is not the same.
		$veritrans->shipping_postal_code = $order->getShippingAddress()->getPostcode();
		$veritrans->shipping_phone = $order->getShippingAddress()->getTelephone();
		
		// $bank = Mage::helper('vtbcaklikpay/data')->_getInstallmentBank();
		// $veritrans->installment_banks = array($bank);
		// $terms = explode(',', Mage::helper('vtbcaklikpay/data')->_getInstallmentTerms());
		// $veritrans->installment_terms = json_encode(array($bank => $terms));
	
		$items = $order->getAllItems();		
		$shipping_amount = (int)$order->getShippingAmount();
		$shipping_tax_amount = (int) (int)$order->getShippingTaxAmount();
        $gc_amount = $order->getBaseGiftCardsAmount()*-1;
        $handling_fee = $order->getBaseFoomanSurchargeAmount();
		$commodities =  array();
		
		foreach ($items as $item)
		{
            array_push($commodities,
                array(
                    "item_id" => $item->getProductId(),
                    "price" => $item->getPrice(),
                    "quantity" => $item->getQtyToInvoice(),
                    "item_name1" => $this->repString($this->getName($item->getName())),
                    "item_name2" => $this->repString($this->getName($item->getName())),
                ));
        }

        if ($order->getDiscountAmount() != 0) {
            array_push($commodities,
                array(
                    "item_id" => 'DISCOUNT',
                    'price' => $order->getDiscountAmount(),
                    "quantity" => 1,
                    "item_name1" => 'DISCOUNT',
                    "item_name2" => 'DISCOUNT',
                ));
        }
		
		if($shipping_amount > 0)
		{
			array_push($commodities, 
				array(
					"item_id" => 'SHIPPING', 
					"price" => $shipping_amount, 
					"quantity" => 1, 
					"item_name1" => 'Shipping Cost', 
					"item_name2" => 'Shipping Cost',
					));
		}

        if($shipping_tax_amount > 0)
        {
            array_push($commodities,
                array(
                    "item_id" => 'SHIPPING_TAX',
                    "price" => $shipping_tax_amount,
                    "quantity" => 1,
                    "item_name1" => 'Shipping Tax',
                    "item_name2" => 'Shipping Tax',
                ));
        }

        if ($gc_amount < 0) {
            array_push($commodities,
                array(
                    "item_id" => 'GIFTCARD',
                    "price" => $gc_amount,
                    "quantity" => 1,
                    "item_name1" => 'Gift Card',
                    "item_name2" => 'Gift Card',
                ));
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
		if ($current_currency != 'IDR')
		{
			$idr_exist = in_array('IDR', Mage::app()->getStore()->getAvailableCurrencyCodes());
			if ($idr_exist)
			{
				// attempt to use the built-in currency converter
				$conversion_func = function($non_idr_price) use ($current_currency) { return Mage::helper('directory')->currencyConvert($non_idr_price, $current_currency, 'IDR'); };
			} else
			{
				$conversion_func = function($non_idr_price) { return $non_idr_price * Mage::getStoreConfig('payment/vtbcaklikpay/conversion_rate'); };
			}
			foreach ($commodities as &$item) {
	      $item['price'] = intval(round(call_user_func($conversion_func, $item['price'])));
	    }
		}		

		$veritrans->items = $commodities;
        Mage::log('$veritrans:'.print_r($veritrans,true),null,'$veritrans.log',true);
		$keys = $veritrans->getTokens();
        Mage::log('$keys:'.print_r($keys,true),null,'$keys.log',true);

        // vtbcaklikpay
        if ($keys && $keys['status_code'] == 201)
        {
            $this->_redirectUrl($keys['redirect_url']);
        } else
        {
            Mage::log('$keys:'.print_r($keys,true),null,'vtbcaklikpay.log',true);
        }
	}
	
	// The response action is triggered when your gateway sends back a response after processing the customer's payment, we will not update to success because success is valid when notification (security reason)
	public function responseAction() {
		//var_dump($_POST); use for debugging value.
        $prefix = Mage::getStoreConfig('payment/vtweb/prefix_id');
		if($this->getRequest()->isPost()) {
            Mage::log('POST:'.print_r($_POST,true),null,'responseAction.log',true);
			$orderId = $_POST['order_id']; // Generally sent by gateway
            $orderId = str_replace($prefix,'',$orderId);
			$status = $_POST['status_code'];
			if($status == 'success' && !is_null($orderId) && $orderId != '') {
				// Redirected by Veritrans, if ok
				Mage::getSingleton('checkout/session')->unsQuoteId();				
				Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=>true));
			}
			else {
				// There is a problem in the response we got
				$this->cancelAction();
				Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure'=>true));
			}
		}
        else {
            Mage::log('GET:'.print_r($_GET,true),null,'responseAction.log',true);
            $orderId = $_GET['order_id']; // Generally sent by gateway
            $orderId = str_replace($prefix,'',$orderId);
            $status = $_GET['status_code'];
            if($status == '200' && !is_null($orderId) && $orderId != '') {
                // Redirected by Veritrans, if ok
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
            else {
                // Back to merchant - reorder
                Mage_Core_Controller_Varien_Action::_redirect('vtbcaklikpay/payment/reorder', array('_secure'=>true));
            }
            //			Mage_Core_Controller_Varien_Action::_redirect('');
        }
	}
	
	// Veritrans will send notification of the payment status, this is only way we make sure that the payment is successed, if success send the item(s) to customer :p 
    public function notificationAction() {
        $notification = new VeritransNotification();
        if (Mage::getStoreConfig('payment/vtbcaklikpayv2/api_version') == 2)
        {
            if($this->getRequest()->isPost()) {
                $prefix = Mage::getStoreConfig('payment/vtweb/prefix_id');
                $input = file_get_contents('php://input');
                Mage::log('$input:'.$input,null,'$paymentc.log',true);
                $value = json_decode($input);
                $value = (array) $value;
                Mage::log('POST - php://input:'.print_r($value,true),null,'notificationAction.log',true);
                $orderId = $value['order_id']; // Generally sent by gateway
                $orderId = str_replace($prefix,'',$orderId);
                $status = $value['transaction_status'];
                Mage::log('$orderId:'.$orderId,null,'$paymentc.log',true);
                if($status == 'capture' && !is_null($orderId) && $orderId != '') {
                    $order = Mage::getModel('sales/order');
                    $order->loadByIncrementId($orderId);
                    $order->setStatus('pending');

                    $config = Mage::getStoreConfig('payment/vtbcaklikpayv2/ins_email_order_active');
                    if($config == 1) {
                        Mage::log('$status:'.$status,null,'$paymentc.log',true);
                        $paymentc = $order->getPayment()->getMethodInstance()->getCode();
                        Mage::log('$paymentc:'.$paymentc,null,'$paymentc.log',true);
                        $tenor = $order->getInstallmentTenor();
                        Mage::log('$tenor:'.$tenor,null,'$paymentc.log',true);
                        if($paymentc == 'vtbcaklikpayv2' && $tenor!='full') {
                            $installmentDesc = 'Customer Name: '.$order->getBillingAddress()->getFirstname().' '.$order->getBillingAddress()->getLastname().'</br>';
                            $installmentDesc .= 'Acquiring Bank: '.$value['bank'].'</br>';
                            $installmentDesc .= 'CC Number: '.$value['masked_card'].'</br>';
                            $installmentDesc .= 'Tenor: '.'Installment '.$tenor.' months'.'</br>';
                            $installmentDesc .= 'Trx Amount: '.$value['gross_amount'].'</br>';
                            $installmentDesc .= 'Trx Date: '.$value['transaction_time'].'</br>';
                            $installmentDesc .= 'Approval Code: '.$value['approval_code'].'</br>';
                            $order->setVtDesc($installmentDesc);
                            Mage::log('$order->getVtDesc:'.$order->getVtDesc(),null,'$recipients.log',true);

                            $emailInfo = Mage::getModel('core/email_info');
                            $recipients = unserialize(Mage::getStoreConfig('payment/vtbcaklikpayv2/ins_email_order_recipient'));
                            Mage::log('$recipients:'.print_r($recipients,true),null,'$recipients.log',true);
                            foreach($recipients as $recipient) {
                                Mage::log('$recipient[Email]:'.$recipient[Email],null,'$recipients.log',true);
                                $emailInfo->addTo($recipient[Email], $recipient[Name]);
                            }

                            $orderLink = Mage::getBaseUrl().'index.php/admin/sales_order/view/order_id/'.$order->getId();
                            $paymentTitle = $order->getPayment()->getMethodInstance()->getTitle();
    //                        $emailTo = Mage::getStoreConfig('trans_email/ident_custom2/email');
    //                        $emailToName = Mage::getStoreConfig('trans_email/ident_custom2/name');
                            $storeId = Mage::app()->getStore()->getStoreId();
                            $appEmulation = Mage::getSingleton('core/app_emulation');
                            $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);
                            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
                            $mailer = Mage::getModel('core/email_template_mailer');
                            $mailer->addEmailInfo($emailInfo);
                            $mailer->setSender(Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY, $storeId));
                            $mailer->setStoreId($storeId);
                            $mailer->setTemplateId('vtbcaklikpayv2_order_payment_installment_notif');
                            $mailer->setTemplateParams(array(
                                    'order'         => $order,
                                    'order_link'    => $orderLink,
                                    'payment_title' => $paymentTitle,
                                    'vt_desc'       => $installmentDesc
                                )
                            );
                            $mailer->send();
                        }
                    }

                    $order->save();
                } else if($status == 'cancel' && !is_null($orderId) && $orderId != '') {
                    $order = Mage::getModel('sales/order');
                    $order->loadByIncrementId($orderId);
                    if($order->canCancel()) {
                        $order->cancel()->save();
                    }
                    $order->save();
                }
            }
            return true;
        } else
        {
            $orderId = $notification->orderId; // Sent by Veritrans gateway
            $order = Mage::getModel('sales/order');
            $order->loadByIncrementId($orderId);
            $payment = $order->getPayment();
            $tokenMerchant = $payment->getTokenMerchant();

            if($notification->mStatus == 'success' && $tokenMerchant == $notification->TOKEN_MERCHANT) {

                //update status
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway has successed the payment.');
                $order->sendOrderUpdateEmail(true, '<b>Payment Received Successfully!</b>');
                $paymentDueDate = date("Y-m-d H:i:s");
                $payment->setPaymentDueDate($paymentDueDate);
                $order->save();

                Mage::getSingleton('checkout/session')->unsQuoteId();

                return true;
            }
            else
            {
                //do nothing
                return true;
            }
        }
    }

    private function repString($str){
        return preg_replace("/[^a-zA-Z0-9]+/", " ", $str);
    }

    private function getName($s)
    {
        $max_length = 50;
        if (strlen($s) > $max_length) {
            $offset = ($max_length - 3) - strlen($s);
            $s      = substr($s, 0, strrpos($s, ' ', $offset));
        }
        return $s;
    }
	
	// The cancel action is triggered when an order is to be cancelled
	public function cancelAction() {
		if (Mage::getSingleton('checkout/session')->getLastRealOrderId()) {
		    $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
		    if($order->getId()) {
			// Flag the order as 'cancelled' and save it
		        $order->cancel()->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, 'Gateway has declined the payment.')->save();
		    }
		}
	}

    public function reorderAction() {
        $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
        if($order->canCancel()){
            $order->cancel()->save();
        }
        $entity_id = $order->getEntityId();
        $this->_redirect('sales/order/reorder/order_id/'.$entity_id);
    }
}

?>
