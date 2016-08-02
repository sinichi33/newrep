<?php
class Icube_Vpayment_Model_Observer_Veritrans
{
    public function captureVT(Varien_Event_Observer $observer) {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
        $payment_method = $order->getPayment()->getMethodInstance()->getCode();
        Mage::log("payment_method:".$payment_method, null, 'payment_method.log', true);
        if($payment_method === 'vpayment') {
            $order_id = $order->getIncrementId();
            try {
                $sql = "SELECT transaction_id,vtstatus FROM veritrans where order_id='".$order_id."'";
                $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
                $query_result = $connection->fetchAll($sql);
                $transaction_id = current($query_result[0]);
                $vt_status = next($query_result[0]);
//                Mage::log("query_result:".print_r($query_result,true), null, 'transaction_id.log', true);
//                Mage::log("order id:".$order_id."|transaction_id:".$transaction_id."|vt_status:".$vt_status, null, 'transaction_id.log', true);
            } catch (Exception $e){
                Mage::log("ERROR:".$e->getMessage(), null, 'transaction_id_ERR.log', true);
            }
            if(Mage::getStoreConfig('payment/vpayment/authorize_only') || $vt_status == 'challenge') {
                //Mage::log('captureVT:'.print_r($invoice,true),null,'captureVT.log',true);
                $base_grand_total = $invoice->getBaseGrandTotal();

                $comidity = array(
                    'transaction_id' => $transaction_id,
                    'gross_amount' => $base_grand_total
                );
                $json2       = json_encode($comidity);
                Mage::log('capture json2:'.print_r($json2,true),null,'cap_json2.log',true);
                $sentReq  = Mage::helper('vpayment')->sentReqVtransAuthorize($comidity);
                Mage::log('capture sentReq:'.print_r($sentReq,true),null,'cap_sentReq.log',true);
                $codeResp = $sentReq->status_code;

                try {
//                    if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
//                        $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
//                        if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
//                            $result['success']        = false;
//                            $result['error']          = true;
//                            $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
//                            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
//                            return;
//                        }
//                    }
                        //$codeResp = 'VD00';
                        switch ($codeResp) {
                            case '200':
                                $sql = "UPDATE veritrans SET vtstatus = 'captured' WHERE transaction_id = '".$transaction_id."'";
                                $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                                $connection->query($sql);
                                Mage::log('succ:'.$transaction_id, null, 'capture succss_INSERT.log', true);

                                $result['success'] = true;
                                $result['error']   = false;
                                break;
                            case 'VD01':
                                break;
                            case 'VD02':
                                # code...
//                                $result['success']        = false;
//                                $result['error']          = true;
//                                $result['error_messages'] = $this->__('Ooops, something wrong with our system, please contact our support');
//                                Mage::log('VD02', null, 'logging_INSERT.log', true);
                                break;
                            default:
                                # code...
//                                $result['success']        = false;
//                                $result['error']          = true;
//                                $result['error_messages'] = $this->__('Can not contact payment gateway, please try again later');
//                                Mage::log('VD06', null, 'logging_INSERT.log', true);
                                break;
                        }
                }
                catch (Exception $e) {
                    Mage::logException($e);
                    Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
                    $result['success']        = false;
                    $result['error']          = true;
                    $result['error_messages'] = $this->__('There was an error authorizing invoice order. Please contact us or try again later.');
                }
            }
        }
    }

    public function checkAuthorizeOrder(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() == 'vpayment') {
            if(Mage::getStoreConfig('payment/vpayment/authorize_only')) {
                $order->setStatus(Icube_Vpayment_Model_Vpayment::STATE_PENDING);
                $order->save();
            }
        }
        return $this;
    }

    public function cancelOrder(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $order_item = $event->getItem();
        $order = $order_item->getOrder();
        if ($order->getPayment()->getMethodInstance()->getCode() == 'vpayment') {
//            Mage::log('ORDER:'.print_r($order,true),null,'order.log',true);
            $order_increment_id = $order->getIncrementId();
            $sql = "SELECT transaction_id FROM veritrans where order_id='".$order_increment_id."'";
            $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
            $transaction_id = end($connection->fetchCol($sql));
//            Mage::log("transaction_id:".$transaction_id, null, 'transaction_id.log', true);
            $result = Mage::helper('vpayment')->sendCancelTransaction($transaction_id);

            if($result->status_code == '200') {
                $transaction_id = $result->transaction_id;
                $sql = "UPDATE veritrans SET vtstatus = 'cancel' WHERE transaction_id = '".$transaction_id."'";
                $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                $connection->query($sql);
            }
        }
        return $this;
    }
}
?>