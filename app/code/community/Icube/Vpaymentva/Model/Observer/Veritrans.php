<?php
class Icube_Vpaymentva_Model_Observer_Veritrans
{
    public function cancelOrder(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $order_item = $event->getItem();
        $order = $order_item->getOrder();
        if ($order->getPayment()->getMethodInstance()->getCode() == 'vpaymentva') {
//            Mage::log('ORDER:'.print_r($order,true),null,'order.log',true);
            $order_increment_id = $order->getIncrementId();
            $sql = "SELECT transaction_id FROM veritrans where order_id='".$order_increment_id."'";
            $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
            $transaction_id = end($connection->fetchCol($sql));
//            Mage::log("transaction_id:".$transaction_id, null, 'transaction_id.log', true);
            $result = Mage::helper('vpaymentva')->sendCancelTransaction($transaction_id);

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