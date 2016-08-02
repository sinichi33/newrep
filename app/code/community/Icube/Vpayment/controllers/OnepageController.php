<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Icube_Vpayment_OnepageController extends Mage_Checkout_OnepageController
{
    protected $_payType = '';

    public function saveOrderAction()
    {
        $paymentMethod = Mage::getSingleton('checkout/session')->getQuote()->getPayment()->getMethodInstance()->getCode();
        if ($this->_expireAjax()) {
            return;
        }
        $result = array();
        if ($paymentMethod == 'klikpay') {
            try {
                //save installment options in quote item table 
                if ($installmentData = $this->getRequest()->getPost('installment', false)) {
                    $quote_items = $this->getOnepage()->getQuote()->getAllItems();
                    $item_ids    = array();
                    foreach ($quote_items as $item) {
                        $item_ids[] = $item->getProductId();
                    }

                    $installmentOptionType = Mage::getStoreConfig('payment/klikpay/installment_option');

                    if ($installmentOptionType == 2) { // if installment type is per order
                        if ($installmentData == '') {
                            $result['success']        = false;
                            $result['error']          = true;
                            $result['error_messages'] = $this->__('Please select an installment type before placing the order.');
                            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                            return;
                        }

                        foreach ($quote_items as $item) {
                            $item->setInstallmentType($installmentData);
                            $item->save();
                        }

                        if ($installmentData == Icube_Klikpay_Model_Klikpay::PAYMENT_TYPE_FULL_TRANSACTION) {
                            $this->_payType = Icube_Klikpay_Model_Klikpay::PAYMENT_TYPE_FULL_TRANSACTION;
                        } else {
                            $this->_payType = Icube_Klikpay_Model_Klikpay::PAYMENT_TYPE_INSTALLMENT_TRANSACTION;
                        }
                    } else { //if installment type is per item

                        if (array_diff($item_ids, array_keys($installmentData))) {
                            $result['success']        = false;
                            $result['error']          = true;
                            $result['error_messages'] = $this->__('Please select an installment type before placing the order.');
                            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                            return;
                        }

                        foreach ($quote_items as $item) {
                            foreach ($installmentData as $_productid => $installmentvalue) {
                                if ($item->getProductId() == $_productid) {
                                    $item->setInstallmentType($installmentvalue);
                                    //$item->save();
                                }
                            }
                        }
                        //save pay type 
                        $count      = 0;
                        $arrayCount = count($installmentData);
                        foreach ($installmentData as $value) {
                            if($value==00){
                                $value=01;
                            }
                            if ($value == Icube_Klikpay_Model_Klikpay::PAYMENT_TYPE_FULL_TRANSACTION) {
                                $count++;
                            }
                        }
                        if ($arrayCount == $count) {
                            $this->_payType = Icube_Klikpay_Model_Klikpay::PAYMENT_TYPE_FULL_TRANSACTION;
                        } else if ($count >= 1) {
                            $this->_payType = Icube_Klikpay_Model_Klikpay::PAYMENT_TYPE_COMBINE_TRANSACTION;
                        } else {
                            $this->_payType = Icube_Klikpay_Model_Klikpay::PAYMENT_TYPE_INSTALLMENT_TRANSACTION;
                        }
                        $this->getOnepage()->getQuote()->setPayType($this->_payType)->save();
                    }
                } else {
                    $installmentData = $this->getRequest()->getPost('full', false);
                    $quote_items     = $this->getOnepage()->getQuote()->getAllItems();
                    $item_ids        = array();
                    foreach ($quote_items as $item) {
                        $item_ids[] = $item->getProductId();
                    }
                    foreach ($quote_items as $item) {
                        foreach ($installmentData as $_productid => $installmentvalue) {
                            if ($item->getProductId() == $_productid) {
                                $item->setInstallmentType($installmentvalue);
                                $item->save();
                            }
                        }
                    }
                    $this->_payType = Icube_Klikpay_Model_Klikpay::PAYMENT_TYPE_FULL_TRANSACTION;
                    $this->getOnepage()->getQuote()->setPayType($this->_payType)->save();
                }

                if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                    $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                    if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                        $result['success']        = false;
                        $result['error']          = true;
                        $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                        return;
                    }
                }
                if ($data = $this->getRequest()->getPost('payment', false)) {
                    $this->getOnepage()->getQuote()->getPayment()->importData($data);
                }
                $this->getOnepage()->saveOrder();

                $redirectUrl       = $this->getOnepage()->getCheckout()->getRedirectUrl();
                $result['success'] = true;
                $result['error']   = false;
            }
            catch (Mage_Payment_Model_Info_Exception $e) {
                $message = $e->getMessage();
                if (!empty($message)) {
                    $result['error_messages'] = $message;
                }
                $result['goto_section']   = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }
            catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
                $result['success']        = false;
                $result['error']          = true;
                $result['error_messages'] = $e->getMessage();

                if ($gotoSection = $this->getOnepage()->getCheckout()->getGotoSection()) {
                    $result['goto_section'] = $gotoSection;
                    $this->getOnepage()->getCheckout()->setGotoSection(null);
                }

                if ($updateSection = $this->getOnepage()->getCheckout()->getUpdateSection()) {
                    if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                        $updateSectionFunction    = $this->_sectionUpdateFunctions[$updateSection];
                        $result['update_section'] = array(
                            'name' => $updateSection,
                            'html' => $this->$updateSectionFunction()
                        );
                    }
                    $this->getOnepage()->getCheckout()->setUpdateSection(null);
                }
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
                $result['success']        = false;
                $result['error']          = true;
                $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
            }
            $this->getOnepage()->getQuote()->save();

            if (isset($redirectUrl)) {
                $result['redirect'] = $redirectUrl;
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

        } elseif ($paymentMethod == 'vpayment') {
            $checkout    = Mage::getSingleton('checkout/session');
            $email=$this->getEmailCust($checkout);
            $quote_items = $this->getOnepage()->getQuote()->getAllItems();
            $server_key  = base64_encode($this->getServerKey());
            $items       = $checkout->getQuote()->getAllItems();
            // $customer=$customer = Mage::getSingleton('customer/session')->isLoggedIn();
            //$customerData = Mage::getModel('customer/customer')->load($customer->getId())->getData();
            $countItem   = Mage::helper('checkout/cart')->getItemsCount();
            //Mage::log(var_export(array_keys( $checkout->getQuote()->getShippingAmount() ), TRUE),NULL,'vpayment.log');
            $shipRates   = $checkout->getQuote()->getShippingAddress()->collectShippingRates()->getShippingAmount();
            $qtData      = $checkout->getQuote()->getData();
            $gt          = (int)round($qtData['grand_total']);
            $gc_amount   = ($qtData['base_gift_cards_amount']*-1);
            $handling_fee = ($qtData['base_fooman_surcharge_amount']);

            $dsct=Mage::getSingleton('checkout/session')->getQuote()->getTotals();
            $discount=0;
            $dsc=$dsct['discount'];
            if($dsc!=null){
                $discount=(int)round($dsc->getValue());
            };

            //calculate Giftcard
            /*$giftcards = Mage::helper('aw_giftcard/totals')->getQuoteGiftCards(Mage::getSingleton('checkout/session')->getQuote()->getId());
            $total_giftcard = 0;
            $giftcard_item_balance = 0;
            foreach($giftcards as $giftcard) {
                Mage::log('charge_giftcard:'.print_r($giftcard,true),null,'Giftcard.log',true);
                $total_giftcard += $giftcard->getGiftcardAmount();
            }
            Mage::log("charge_total_giftcard:".$total_giftcard,null,"GT_aj.log",true);
            $base_subtotal = Mage::getSingleton('checkout/session')->getQuote()->getBaseSubtotal();
            Mage::log("charge_base_subtotal:".$base_subtotal,null,"GT_aj.log",true);
            if($total_giftcard > $base_subtotal) {
                $total_giftcard = $base_subtotal;
            }
            Mage::log("charge_total_giftcard:".$total_giftcard,null,"GT_aj.log",true);
            $items_size = 0;
            foreach ($items as $itemId => $item){
                if ($item->getPrice() > 0 && $item->getQty() > 0)
                    $items_size += $item->getQty();
            }
            Mage::log("charge_items_size:".$items_size,null,"GT_aj.log",true);
//        Mage::log('$items->getSize():'.$items_size,null,'$items_size.log',true);
            if($total_giftcard > 0 && $items_size > 0) {
                $giftcard_item_balance = $total_giftcard/$items_size;
            }
            Mage::log("charge_giftcard_item_balance:".$giftcard_item_balance,null,"GT_aj.log",true);*/

            foreach ($items as $item) {
                $price  = $item->getPrice();
                $qty=$item->getQty();
                $calcPrice=(int)$price;
                if($price!=0){
                    //$price -= $giftcard_item_balance;
                    $cart[] = array(
                        'id' => $item->getSku(),
                        'price' => intval($price),
                        'quantity' => $item->getQty(),
                        'name' => $this->repString($this->getName($item->getName()))
                    );
                };
            };
            $merge[]  = array(
                'id' => 1,
                'price' => number_format($shipRates, 0, '.', ''),
                'quantity' => 1,
                'name' => 'Shipping'
            );
            $merge[]  = array(
                'id' => 2,
                'price' => $discount,
                'quantity' => 1,
                'name' => 'Discount'
            );

            if ($gc_amount < 0) {
                $merge[]  = array(
                    'id' => 3,
                    'price' => $gc_amount,
                    'quantity' => 1,
                    'name' => 'Giftcard Amount'
                );
            }

            if ($handling_fee > 0) {
                $merge[]  = array(
                    'id' => 4,
                    'price' => $handling_fee,
                    'quantity' => 1,
                    'name' => 'Handling Fee'
                );
            }


            $order_id=$checkout->getQuote()->getReservedOrderId();
            try {
                $token = $this->getToken();
                $sql = "INSERT INTO veritrans(token_merchant,vtstatus) values('".$token."','new')";
                $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                $connection->query($sql);

                $sql = "SELECT veritrans_id FROM veritrans where token_merchant='".$token."'";
                $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
                $vt_order_id = end($connection->fetchCol($sql));

                Mage::log("Token:".$token." | Insert ID:".$vt_order_id.'|', null, 'logging_INSERT.log', true);
            } catch (Exception $e){
                Mage::log("ERROR:".$e->getMessage(), null, 'logging_INSERT_ERR.log', true);
            }

            $checkout->getQuote()->setReservedOrderId($order_id);
            $arry     = array_merge($cart, $merge);

            $ship_data = $checkout->getQuote()->getShippingAddress()->getData();
            $nameOnCard = $checkout->getQuote()->getPayment()->getCcOwner();
            $result = $this->getCustomerName($nameOnCard);
            $firstname = $result[0];
            $lastname = $result[1];
            $phone = $ship_data['telephone'];

            $cc_arr = array(
                'token_id' => $token
            );
            if(Mage::getStoreConfig('payment/vpayment/authorize_only')) {
                $cc_arr = array(
                    'token_id' => $token,
                    'type' => 'authorize'
                );
            }
            if($this->getOnepage()->getQuote()->getPayment()->getPromoCode()) {
                $program = Mage::getModel('vpayment/program')->getCollection()
                    ->addFieldToFilter('program_type', 'bin_filter')
                    ->addFieldToFilter('promo_code', $this->getOnepage()->getQuote()->getPayment()->getPromoCode())
                    ->getFirstItem();
                $bins_string = $program->getValidationValue();
                $bins_string = str_replace(" ","",$bins_string);
                $bins = explode(",", $bins_string);
                if(count($bins) > 0)
                    $cc_arr['bins'] = $bins;
            }

            $vtid_prefix = Mage::getStoreConfig('payment/vpayment/idprefix');
            $comidity = array(
                'payment_type' => 'credit_card',
                'credit_card' => $cc_arr,
                "transaction_details" => array(
                    "order_id" => $vtid_prefix.$order_id.'-'.$vt_order_id,
                    "gross_amount" => $gt
                ),
                "item_details" => $arry,
                "customer_details" => array(
                    "first_name" => $firstname,
                    "last_name" => $lastname,
                    "email" => $email,
                    "phone" => $phone,
                    "billing_address" => $this->getBillAddr($checkout),
                    "shipping_address" => $this->getShippingAddr($checkout)
                )
            );

            Mage::log("QUOTE DATA:".print_r($qtData,true), null, 'vtdirect_comidity.log', true);
            Mage::log("VT PARAMS:".print_r($comidity,true), null, 'vtdirect_comidity.log', true);
            $json2       = json_encode($comidity);
            Mage::log('$json2:'.print_r($json2,true),null,'$json2.log',true);
            $sentReq  = Mage::helper('vpayment')->sentReqVtrans($comidity);
            Mage::log('sentReq:'.print_r($sentReq,true),null,'sentReq.log',true);
            $codeResp = $sentReq->status_code;
            try {
                if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                    $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                    if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                        $result['success']        = false;
                        $result['error']          = true;
                        $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                        return;
                    }
                }
                //$codeResp = 'VD00';
                switch ($codeResp) {
                    case '200':
                        # code...
                        foreach ($quote_items as $item) {
                            $item->save();
                        }
                        $transaction_id = $sentReq->transaction_id;
                        $this->getOnepage()->getQuote()->setPayType('Veritrans')->save();
                        if ($data = $this->getRequest()->getPost('payment', false)) {
                            $this->getOnepage()->getQuote()->getPayment()->importData($data);
                        }
                        $this->getOnepage()->saveOrder();

                        $sql = '';
                        if(Mage::getStoreConfig('payment/vpayment/authorize_only')) {
                            $sql = "UPDATE veritrans SET order_id = '".$order_id."', transaction_id = '".$transaction_id."', vtstatus = 'authorized' WHERE veritrans_id = '".$vt_order_id."'";
                        } else {
                            $sql = "UPDATE veritrans SET order_id = '".$order_id."', transaction_id = '".$transaction_id."', vtstatus = 'captured' WHERE veritrans_id = '".$vt_order_id."'";
                        }
                        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                        $connection->query($sql);
                        Mage::log('VD00', null, 'logging_INSERT.log', true);

                        //$this->invoiceOrder($order_id);
                        //Icube Update - call Model split invoice
                        $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
                        Mage::getModel('icube_invoice/service_split')->invoiceSplitOrder($order);

                        $result['success'] = true;
                        $result['error']   = false;
                        break;
                    case '201':
                        # code...
                        foreach ($quote_items as $item) {
                            $item->save();
                        }
                        $transaction_id = $sentReq->transaction_id;
                        $this->getOnepage()->getQuote()->setPayType('Veritrans')->save();
                        if ($data = $this->getRequest()->getPost('payment', false)) {
                            $this->getOnepage()->getQuote()->getPayment()->importData($data);
                        }
                        $this->getOnepage()->saveOrder();
                        $sql = "UPDATE veritrans SET order_id = '".$order_id."', transaction_id = '".$transaction_id."', vtstatus = 'challenge' WHERE veritrans_id = '".$vt_order_id."'";

                        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                        $connection->query($sql);
                        Mage::log('VD00', null, 'logging_INSERT.log', true);

                        $new_order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
                        $new_order->setStatus(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW);
                        $new_order->save();

                        //$this->invoiceOrder($order_id);

                        $result['success'] = true;
                        $result['error']   = false;
                        break;
                    default:
                        # code...
                        $vt_message = $sentReq->status_message;
                        $result['success']        = false;
                        $result['error']          = true;
                        $result['error_messages'] = $this->__($vt_message).' ('.$codeResp.','.$order_id.')';
                        $result['goto_section']   = 'payment';
                        break;
                }
            }
            catch (Mage_Payment_Model_Info_Exception $e) {
                $message = $e->getMessage();
                if (!empty($message)) {
                    $result['error_messages'] = $message;
                }
                $result['goto_section']   = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }
            catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
                $result['success']        = false;
                $result['error']          = true;
                $result['error_messages'] = $e->getMessage();

                if ($gotoSection = $this->getOnepage()->getCheckout()->getGotoSection()) {
                    $result['goto_section'] = $gotoSection;
                    $this->getOnepage()->getCheckout()->setGotoSection(null);
                }

                if ($updateSection = $this->getOnepage()->getCheckout()->getUpdateSection()) {
                    if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                        $updateSectionFunction    = $this->_sectionUpdateFunctions[$updateSection];
                        $result['update_section'] = array(
                            'name' => $updateSection,
                            'html' => $this->$updateSectionFunction()
                        );
                    }
                    $this->getOnepage()->getCheckout()->setUpdateSection(null);
                }
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
                $result['success']        = false;
                $result['error']          = true;
                $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
            }
            $this->getOnepage()->getQuote()->save();

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        } else if ($paymentMethod == 'vpaymentins') {

            $checkout    = Mage::getSingleton('checkout/session');
            $email=$this->getEmailCust($checkout);
            $quote_items = $this->getOnepage()->getQuote()->getAllItems();
            $server_key  = base64_encode($this->getServerKey());
            $items       = $checkout->getQuote()->getAllItems();
            // $customer=$customer = Mage::getSingleton('customer/session')->isLoggedIn();
            //$customerData = Mage::getModel('customer/customer')->load($customer->getId())->getData();
            $countItem   = Mage::helper('checkout/cart')->getItemsCount();
            //Mage::log(var_export(array_keys( $checkout->getQuote()->getShippingAmount() ), TRUE),NULL,'vpaymentins.log');
            $shipRates   = $checkout->getQuote()->getShippingAddress()->collectShippingRates()->getShippingAmount();
            $qtData      = $checkout->getQuote()->getData();
            $gt          = (int)round($qtData['grand_total']);
            $gc_amount   = ($qtData['base_gift_cards_amount']*-1);
            $handling_fee = ($qtData['base_fooman_surcharge_amount']);

            $dsct=Mage::getSingleton('checkout/session')->getQuote()->getTotals();
            $discount=0;
            $dsc=$dsct['discount'];
            if($dsc!=null){
                $discount=(int)round($dsc->getValue());
            };

            //calculate Giftcard
            /*$giftcards = Mage::helper('aw_giftcard/totals')->getQuoteGiftCards(Mage::getSingleton('checkout/session')->getQuote()->getId());
            $total_giftcard = 0;
            $giftcard_item_balance = 0;
            foreach($giftcards as $giftcard) {
                Mage::log('charge_giftcard:'.print_r($giftcard,true),null,'Giftcard.log',true);
                $total_giftcard += $giftcard->getGiftcardAmount();
            }
            Mage::log("charge_total_giftcard:".$total_giftcard,null,"GT_aj.log",true);
            $base_subtotal = Mage::getSingleton('checkout/session')->getQuote()->getBaseSubtotal();
            Mage::log("charge_base_subtotal:".$base_subtotal,null,"GT_aj.log",true);
            if($total_giftcard > $base_subtotal) {
                $total_giftcard = $base_subtotal;
            }
            Mage::log("charge_total_giftcard:".$total_giftcard,null,"GT_aj.log",true);
            $items_size = 0;
            foreach ($items as $itemId => $item){
                if ($item->getPrice() > 0 && $item->getQty() > 0)
                    $items_size += $item->getQty();
            }
            Mage::log("charge_items_size:".$items_size,null,"GT_aj.log",true);
//        Mage::log('$items->getSize():'.$items_size,null,'$items_size.log',true);
            if($total_giftcard > 0 && $items_size > 0) {
                $giftcard_item_balance = $total_giftcard/$items_size;
            }
            Mage::log("charge_giftcard_item_balance:".$giftcard_item_balance,null,"GT_aj.log",true);*/

            foreach ($items as $item) {
                $price  = $item->getPrice();
                $qty=$item->getQty();
                $calcPrice=(int)$price;
                if($price!=0){
                    //$price -= $giftcard_item_balance;
                    $cart[] = array(
                        'id' => $item->getSku(),
                        'price' => intval($price),
                        'quantity' => $item->getQty(),
                        'name' => $this->repString($this->getName($item->getName()))
                    );
                };
            };
            $merge[]  = array(
                'id' => 1,
                'price' => number_format($shipRates, 0, '.', ''),
                'quantity' => 1,
                'name' => 'Shipping'
            );
            $merge[]  = array(
                'id' => 2,
                'price' => $discount,
                'quantity' => 1,
                'name' => 'Discount'
            );

            if ($gc_amount < 0) {
                $merge[]  = array(
                    'id' => 3,
                    'price' => $gc_amount,
                    'quantity' => 1,
                    'name' => 'Giftcard Amount'
                );
            }

            if ($handling_fee > 0) {
                $merge[]  = array(
                    'id' => 4,
                    'price' => $handling_fee,
                    'quantity' => 1,
                    'name' => 'Handling Fee'
                );
            }


            $order_id=$checkout->getQuote()->getReservedOrderId();
            try {
                $token = $this->getToken();
                $sql = "INSERT INTO veritrans(token_merchant,vtstatus) values('".$token."','new')";
                $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                $connection->query($sql);

                $sql = "SELECT veritrans_id FROM veritrans where token_merchant='".$token."'";
                $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
                $vt_order_id = end($connection->fetchCol($sql));

                Mage::log("Token:".$token." | Insert ID:".$vt_order_id.'|', null, 'logging_INSERT.log', true);
            } catch (Exception $e){
                Mage::log("ERROR:".$e->getMessage(), null, 'logging_INSERT_ERR.log', true);
            }

            $checkout->getQuote()->setReservedOrderId($order_id);
            $arry     = array_merge($cart, $merge);

            $ship_data = $checkout->getQuote()->getShippingAddress()->getData();
            $nameOnCard = $checkout->getQuote()->getPayment()->getCcOwner();
            $result = $this->getCustomerName($nameOnCard);
            $firstname = $result[0];
            $lastname = $result[1];
            $phone = $ship_data['telephone'];

            $cc_arr = array(
                'token_id' => $token,
                'bank'    => $checkout->getQuote()->getPayment()->getInsbank(),
                'installment_term' => $checkout->getQuote()->getPayment()->getInstenor()
            );
            if(Mage::getStoreConfig('payment/vpaymentins/authorize_only')) {
                $cc_arr = array(
                    'token_id' => $token,
                    'bank'    => $checkout->getQuote()->getPayment()->getInsbank(),
                    'installment_term' => $checkout->getQuote()->getPayment()->getInstenor(),
                    'type' => 'authorize'
                );
            }
            if($this->getOnepage()->getQuote()->getPayment()->getPromoCode()) {
                $program = Mage::getModel('vpayment/program')->getCollection()
                    ->addFieldToFilter('program_type', 'bin_filter')
                    ->addFieldToFilter('promo_code', $this->getOnepage()->getQuote()->getPayment()->getPromoCode())
                    ->getFirstItem();
                $bins_string = $program->getValidationValue();
                $bins_string = str_replace(" ","",$bins_string);
                $bins = explode(",", $bins_string);
                if(count($bins) > 0)
                    $cc_arr['bins'] = $bins;
            }

            $vtid_prefix = Mage::getStoreConfig('payment/vpayment/idprefix');
            $comidity = array(
                'payment_type' => 'credit_card',
                'credit_card' => $cc_arr,
                "transaction_details" => array(
                    "order_id" => $vtid_prefix.$order_id.'-'.$vt_order_id,
                    "gross_amount" => $gt
                ),
                "item_details" => $arry,
                "customer_details" => array(
                    "first_name" => $firstname,
                    "last_name" => $lastname,
                    "email" => $email,
                    "phone" => $phone,
                    "billing_address" => $this->getBillAddr($checkout),
                    "shipping_address" => $this->getShippingAddr($checkout)
                )
            );

            Mage::log("QUOTE DATA:".print_r($qtData,true), null, 'vtdirect_comidity.log', true);
            Mage::log("VT PARAMS:".print_r($comidity,true), null, 'vtdirect_comidity.log', true);
            $json2       = json_encode($comidity);
            Mage::log('$json2:'.print_r($json2,true),null,'$json2.log',true);
            $sentReq  = Mage::helper('vpaymentins')->sentReqVtrans($comidity);
            Mage::log('sentReq:'.print_r($sentReq,true),null,'sentReq.log',true);
            $codeResp = $sentReq->status_code;
            try {
                if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                    $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                    if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                        $result['success']        = false;
                        $result['error']          = true;
                        $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                        return;
                    }
                }
                //$codeResp = 'VD00';
                switch ($codeResp) {
                    case '200':
                        # code...
                        foreach ($quote_items as $item) {
                            $item->save();
                        }
                        $transaction_id = $sentReq->transaction_id;
                        $this->getOnepage()->getQuote()->setPayType('Veritrans')->save();
                        if ($data = $this->getRequest()->getPost('payment', false)) {
                            $this->getOnepage()->getQuote()->getPayment()->importData($data);
                        }
                        $this->getOnepage()->saveOrder();

                        $sql = '';
                        if(Mage::getStoreConfig('payment/vpaymentins/authorize_only')) {
                            $sql = "UPDATE veritrans SET order_id = '".$order_id."', transaction_id = '".$transaction_id."', vtstatus = 'authorized' WHERE veritrans_id = '".$vt_order_id."'";
                        } else {
                            $sql = "UPDATE veritrans SET order_id = '".$order_id."', transaction_id = '".$transaction_id."', vtstatus = 'captured' WHERE veritrans_id = '".$vt_order_id."'";
                        }
                        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                        $connection->query($sql);
                        Mage::log('VD00', null, 'logging_INSERT.log', true);

                        //$this->invoiceOrder($order_id);
                        //Icube Update - call Model split invoice
                        $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
                        Mage::getModel('icube_invoice/service_split')->invoiceSplitOrder($order);

                        $result['success'] = true;
                        $result['error']   = false;
                        break;
                    case '201':
                        # code...
                        foreach ($quote_items as $item) {
                            $item->save();
                        }
                        $transaction_id = $sentReq->transaction_id;
                        $this->getOnepage()->getQuote()->setPayType('Veritrans')->save();
                        if ($data = $this->getRequest()->getPost('payment', false)) {
                            $this->getOnepage()->getQuote()->getPayment()->importData($data);
                        }
                        $this->getOnepage()->saveOrder();
                        $sql = "UPDATE veritrans SET order_id = '".$order_id."', transaction_id = '".$transaction_id."', vtstatus = 'challenge' WHERE veritrans_id = '".$vt_order_id."'";

                        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                        $connection->query($sql);
                        Mage::log('VD00', null, 'logging_INSERT.log', true);

                        $new_order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
                        $new_order->setStatus(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW);
                        $new_order->save();

                        //$this->invoiceOrder($order_id);

                        $result['success'] = true;
                        $result['error']   = false;
                        break;
                    default:
                        # code...
                        $vt_message = $sentReq->status_message;
                        $result['success']        = false;
                        $result['error']          = true;
                        $result['error_messages'] = $this->__($vt_message).' ('.$codeResp.','.$order_id.')';
                        $result['goto_section']   = 'payment';
                        break;
                }
            }
            catch (Mage_Payment_Model_Info_Exception $e) {
                $message = $e->getMessage();
                if (!empty($message)) {
                    $result['error_messages'] = $message;
                }
                $result['goto_section']   = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }
            catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
                $result['success']        = false;
                $result['error']          = true;
                $result['error_messages'] = $e->getMessage();

                if ($gotoSection = $this->getOnepage()->getCheckout()->getGotoSection()) {
                    $result['goto_section'] = $gotoSection;
                    $this->getOnepage()->getCheckout()->setGotoSection(null);
                }

                if ($updateSection = $this->getOnepage()->getCheckout()->getUpdateSection()) {
                    if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                        $updateSectionFunction    = $this->_sectionUpdateFunctions[$updateSection];
                        $result['update_section'] = array(
                            'name' => $updateSection,
                            'html' => $this->$updateSectionFunction()
                        );
                    }
                    $this->getOnepage()->getCheckout()->setUpdateSection(null);
                }
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
                $result['success']        = false;
                $result['error']          = true;
                $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
            }
            $this->getOnepage()->getQuote()->save();

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        } else if ($paymentMethod == 'vpaymentva') {
            $checkout    = Mage::getSingleton('checkout/session');
            $email=$this->getEmailCust($checkout);
            $quote_items = $this->getOnepage()->getQuote()->getAllItems();
            $qtData      = $checkout->getQuote()->getData();
            $gt          = (int)round($qtData['grand_total']);

            $order_id=$checkout->getQuote()->getReservedOrderId();
            try {
                $sql = "INSERT INTO veritrans(vtstatus) values('new')";
                $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                $connection->query($sql);
                $vt_order_id = $connection->lastInsertId();

                Mage::log("Insert ID:".$vt_order_id, null, 'vtdirectva_logging_INSERT.log', true);
            } catch (Exception $e){
                Mage::log("ERROR:".$e->getMessage(), null, 'vtdirectva_logging_INSERT.log', true);
            }

            $checkout->getQuote()->setReservedOrderId($order_id);

            $ship_data = $checkout->getQuote()->getShippingAddress()->getData();
            $firstname = $ship_data['firstname'];
            $lastname = $ship_data['lastname'];
            $phone = $ship_data['telephone'];
            $vabank = $checkout->getQuote()->getPayment()->getVabank();
            $vtid_prefix = Mage::getStoreConfig('payment/vpayment/idprefix');

            if($vabank == Icube_Vpaymentva_Model_Vpaymentva::CODE_MANDIRI_BILLPAYMENT) {
                $shipRates   = $checkout->getQuote()->getShippingAddress()->collectShippingRates()->getShippingAmount();
                $gc_amount   = ($qtData['base_gift_cards_amount']*-1);
                $handling_fee = ($qtData['base_fooman_surcharge_amount']);

                $dsct=Mage::getSingleton('checkout/session')->getQuote()->getTotals();
                $discount=0;
                $dsc=$dsct['discount'];
                if($dsc!=null){
                    $discount=(int)round($dsc->getValue());
                };
                foreach ($quote_items as $item) {
                    $price  = $item->getPrice();
                    if($price!=0){
                        $cart[] = array(
                            'id' => $item->getSku(),
                            'price' => intval($price),
                            'quantity' => $item->getQty(),
                            'name' => $this->repString($this->getName($item->getName()))
                        );
                    };
                };
                $merge[]  = array(
                    'id' => 1,
                    'price' => number_format($shipRates, 0, '.', ''),
                    'quantity' => 1,
                    'name' => 'Shipping'
                );
                $merge[]  = array(
                    'id' => 2,
                    'price' => $discount,
                    'quantity' => 1,
                    'name' => 'Discount'
                );

                if ($gc_amount < 0) {
                    $merge[]  = array(
                        'id' => 3,
                        'price' => $gc_amount,
                        'quantity' => 1,
                        'name' => 'Giftcard Amount'
                    );
                }

                if ($handling_fee > 0) {
                    $merge[]  = array(
                        'id' => 4,
                        'price' => $handling_fee,
                        'quantity' => 1,
                        'name' => 'Handling Fee'
                    );
                }

                $arry     = array_merge($cart, $merge);
                $echannel_arr = array(
                    'bill_info1'    => $this->__('Payment for'),
                    'bill_info2'    => $vtid_prefix.$order_id.'-'.$vt_order_id
                );
                $comidity = array(
                    'payment_type' => Icube_Vpaymentva_Model_Vpaymentva::CODE_MANDIRI_BILLPAYMENT,
                    Icube_Vpaymentva_Model_Vpaymentva::CODE_MANDIRI_BILLPAYMENT => $echannel_arr,
                    "transaction_details" => array(
                        "order_id" => $vtid_prefix.$order_id.'-'.$vt_order_id,
                        "gross_amount" => $gt
                    ),
                    "item_details" => $arry,
                    "customer_details" => array(
                        "first_name" => $firstname,
                        "last_name" => $lastname,
                        "email" => $email,
                        "phone" => $phone,
                        "billing_address" => $this->getBillAddr($checkout),
                        "shipping_address" => $this->getShippingAddr($checkout)
                    )
                );
            } else if($vabank == 'bca'){
                $cc_arr = array(
                    'bank'    => $vabank,
                    'free_text' => array(
                        'inquiry' => array(
                            array('id' => "PASTIKAN NOMOR VIRTUAL ACCOUNT", 'en' => "PLEASE MAKE SURE YOU ENTERED"),
                            array('id' => "DAN JUMLAH PEMBAYARAN ANDA", 'en' => "CORRECT VIRTUAL ACCOUNT"),
                            array('id' => "SESUAI", 'en' => "NUMBER"),
                        ),
                        'payment' => array(
                            array('id' => "TERIMA KASIH UNTUK PEMBAYARAN", 'en' => "THANK YOU FOR YOUR PAYMENT"),
                            array('id' => "ORDER ANDA DI RUPARUPA.COM", 'en' => "WE WILL PROCESS YOUR ORDER"),
                            array('id' => "PESANAN ANDA AKAN KAMI PROSES", 'en' => "ACCORDING TO OUR SHIPMENT"),
                            array('id' => "SESUAI DENGAN WAKTU PENGIRIMAN", 'en' => "POLICY TO YOUR ADDRESS"),
                            array('id' => "ALAMAT ANDA", 'en' => "."),
                        )
                    )
                );
                $comidity = array(
                    'payment_type' => 'bank_transfer',
                    'bank_transfer' => $cc_arr,
                    "transaction_details" => array(
                        "order_id" => $vtid_prefix.$order_id.'-'.$vt_order_id,
                        "gross_amount" => $gt
                    ),
                    "customer_details" => array(
                        "first_name" => $firstname,
                        "last_name" => $lastname,
                        "email" => $email,
                        "phone" => $phone,
                        "billing_address" => $this->getBillAddr($checkout),
                        "shipping_address" => $this->getShippingAddr($checkout)
                    )
                );
            } else {
                $cc_arr = array(
                    'bank'    => $vabank
                );
                $comidity = array(
                    'payment_type' => 'bank_transfer',
                    'bank_transfer' => $cc_arr,
                    "transaction_details" => array(
                        "order_id" => $vtid_prefix.$order_id.'-'.$vt_order_id,
                        "gross_amount" => $gt
                    ),
                    "customer_details" => array(
                        "first_name" => $firstname,
                        "last_name" => $lastname,
                        "email" => $email,
                        "phone" => $phone,
                        "billing_address" => $this->getBillAddr($checkout),
                        "shipping_address" => $this->getShippingAddr($checkout)
                    )
                );
            }

            Mage::log("QUOTE DATA:".print_r($qtData,true), null, 'vtdirectva_comidity.log', true);
            Mage::log("VT PARAMS:".print_r($comidity,true), null, 'vtdirectva_comidity.log', true);
            $json2       = json_encode($comidity);
            Mage::log('$json2:'.print_r($json2,true),null,'$json2.log',true);
            $sentReq  = Mage::helper('vpaymentva')->sentReqVtrans($comidity);
            Mage::log('sentReq:'.print_r($sentReq,true),null,'sentReq.log',true);
            $codeResp = $sentReq->status_code;
            try {
                if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                    $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                    if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                        $result['success']        = false;
                        $result['error']          = true;
                        $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                        return;
                    }
                }
                switch ($codeResp) {
                    case '201':
                        foreach ($quote_items as $item) {
                            $item->save();
                        }
                        $transaction_id = $sentReq->transaction_id;
                        $this->getOnepage()->getQuote()->setPayType('Veritrans')->save();
                        if ($data = $this->getRequest()->getPost('payment', false)) {
                            $this->getOnepage()->getQuote()->getPayment()->importData($data);
                        }
                        if($vabank == Icube_Vpaymentva_Model_Vpaymentva::BANK_PERMATA) {
                            $vanumber = $sentReq->permata_va_number;
                            $this->getOnepage()->getQuote()->getPayment()->setVanumber($vanumber);
                        }
                        else if($vabank == Icube_Vpaymentva_Model_Vpaymentva::CODE_MANDIRI_BILLPAYMENT) {
                            $billercode = $sentReq->biller_code;
                            $billkey = $sentReq->bill_key;
                            $this->getOnepage()->getQuote()->getPayment()->setBillercode($billercode);
                            $this->getOnepage()->getQuote()->getPayment()->setBillkey($billkey);
                        } else {
                            $vanumber = $sentReq->va_numbers;
                            $vanumber = $vanumber[0];
                            $vanumber = $vanumber->va_number;
                            $this->getOnepage()->getQuote()->getPayment()->setVanumber($vanumber);
                        }
                        $this->getOnepage()->getQuote()->getPayment()->save();
                        Mage::log('201', null, 'vtdirectva_logging_INSERT.log', true);
                        $this->getOnepage()->saveOrder();

                        $sql = "UPDATE veritrans SET order_id = '".$order_id."', transaction_id = '".$transaction_id."', vtstatus = 'pending' WHERE veritrans_id = '".$vt_order_id."'";
                        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                        $connection->query($sql);

                        $result['success'] = true;
                        $result['error']   = false;
                        break;
                    default:
                        Mage::log('default', null, 'vtdirectva_logging_INSERT.log', true);
                        $vt_message = $sentReq->status_message;
                        $result['success']        = false;
                        $result['error']          = true;
                        $result['error_messages'] = $this->__($vt_message).' ('.$codeResp.','.$order_id.')';
                        $result['goto_section']   = 'payment';
                        break;
                }
            }
            catch (Mage_Payment_Model_Info_Exception $e) {
                $message = $e->getMessage();
                if (!empty($message)) {
                    $result['error_messages'] = $message;
                }
                Mage::log('exception : '.$message, null, 'vtdirectva_logging_INSERT.log', true);
                $result['goto_section']   = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }
            catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
                $result['success']        = false;
                $result['error']          = true;
                $result['error_messages'] = $e->getMessage();
                Mage::log('exception : '.$e->getMessage(), null, 'vtdirectva_logging_INSERT.log', true);

                if ($gotoSection = $this->getOnepage()->getCheckout()->getGotoSection()) {
                    $result['goto_section'] = $gotoSection;
                    $this->getOnepage()->getCheckout()->setGotoSection(null);
                }

                if ($updateSection = $this->getOnepage()->getCheckout()->getUpdateSection()) {
                    if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                        $updateSectionFunction    = $this->_sectionUpdateFunctions[$updateSection];
                        $result['update_section'] = array(
                            'name' => $updateSection,
                            'html' => $this->$updateSectionFunction()
                        );
                    }
                    $this->getOnepage()->getCheckout()->setUpdateSection(null);
                }
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
                Mage::log('exception : '.$e->getMessage(), null, 'vtdirectva_logging_INSERT.log', true);
                $result['success']        = false;
                $result['error']          = true;
                $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
            }
            $this->getOnepage()->getQuote()->save();

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        } else {
            parent::saveOrderAction();
        }
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

    private function getBillAddr($checkout)
    {
        $getBill = $checkout->getQuote()->getBillingAddress()->getData();
        $paymentCode = $checkout->getQuote()->getPayment()->getMethodInstance()->getCode();
        $firstname = $getBill['firstname'];
        $lastname = $getBill['lastname'];
        if($paymentCode == 'vpayment' || $paymentCode == 'vpaymentins') {
            $nameOnCard = $checkout->getQuote()->getPayment()->getCcOwner();
            $result = $this->getCustomerName($nameOnCard);
            $firstname = $result[0];
            $lastname = $result[1];
        }
        $bill    = array(
            'first_name' => $firstname,
            'last_name' => $lastname,
            'address' => substr($this->repString($getBill['street']), 0, 100),
            'city' => $getBill['city'],
            'postal_code' => $getBill['postcode'],
            'phone' => $getBill['telephone'],
            "country_code" => "IDN"
        );
        return $bill;
    }

    public function getCustomerName($fullname) {
        $nameArr = explode(" ", $fullname);
        $lastname = array_pop($nameArr);
        $firstname = implode(" ", $nameArr);
        if($firstname=='') {
            $firstname = $lastname;
        }
        $firstname = trim($firstname);
        $lastname = trim($lastname);
        $result[0] = $firstname;
        $result[1] = $lastname;
        return $result;
    }
    
    private function getShippingAddr($checkout)
    {
        $getShipping = $checkout->getQuote()->getShippingAddress()->getData();
        $ship        = array(
            'first_name' => $getShipping['firstname'],
            'last_name' => $getShipping['lastname'],
            'address' => substr($this->repString($getShipping['street']), 0, 100),
            'city' => $getShipping['city'],
            'postal_code' => $getShipping['postcode'],
            'phone' => $getShipping['telephone'],
            "country_code" => "IDN"
        );
        return $ship;
    }

    private function repString($str){
        return preg_replace("/[^a-zA-Z0-9]+/", " ", $str);
    }

    private function getEmailCust($checkout){
        $email=$checkout->getQuote()->getShippingAddress()->getData();
        $emailCust=$email['email'];
        if($emailCust==null){
            $emailCust= Mage::getSingleton('customer/session')->getCustomer()->getEmail();
            if($emailCust==null){
                $email=$checkout->getQuote()->getBillingAddress()->getData();
                $emailCust=$email['email'];
            }
        }
        return $emailCust;
    }

    private function getmess($content){
        $mess=str_replace('[', '', $content);
        $mess=str_replace(']', '', $mess);
        return $mess;
    }

    private function getUrl()
    {
        return Mage::getStoreConfig('payment/vpayment/vurl');
    }

    private function getServerKey()
    {
        return Mage::getStoreConfig('payment/vpayment/server');
    }

    private function getToken()
    {
        return Mage::getSingleton('core/session')->getTokenBrowser();
    }

    private function invoiceOrder($orderIncrementId) {
        $order = Mage::getModel("sales/order")->load($orderIncrementId, 'increment_id');
        Mage::log('$order:'.print_r($order,true), null, 'vt_invoiceOrder.log', true);
        try {
            if(!$order->canInvoice()) {
                Mage::log('Cannot create an invoice', null, 'vt_invoiceOrder.log', true);
                Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
            }

            $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
            if (!$invoice->getTotalQty()) {
                Mage::log('Cannot create an invoice without products.', null, 'vt_invoiceOrder.log', true);
                Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
            }
            $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
            $invoice->register();
            $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder());
            $transactionSave->save();
        }
        catch (Mage_Core_Exception $e) {

        }
    }

    public function splitInvoiceAction()
    {
        // .../vpayment/onepage/splitInvoice?id=100000151
        $order = Mage::getModel('sales/order')->loadByIncrementId($this->getRequest()->getParam('id'));
        Mage::getModel('icube_invoice/service_split')->invoiceSplitOrder($order);
    }
}