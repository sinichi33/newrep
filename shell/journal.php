<?php
/**
 * Created by PhpStorm.
 * User: asep.solehudin
 * Date: 12/04/2016
 * Time: 16:03
 */

require_once 'abstract.php';

class Mage_Shell_Journal extends Mage_Shell_Abstract
{
    /**
     * @param $orderId
     */
    private function reclass($orderId){
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

        if ($order->hasInvoices()) {
            $inv = $order->getInvoiceCollection()->getFirstItem();
            $paymentDate = Mage::getModel('core/date')->date('Ymd', strtotime($inv->getCreatedAt()));
        } else $paymentDate	=	Mage::getModel('core/date')->date('Ymd');

        $giftCardSerialized = $order->getData('gift_cards');

        $params = array(
            'order' => $order,
            'paymentDate' => $paymentDate,
        );

        $createJournal = Mage::getModel('journal/journal')->createReclass($params);
        echo "reclass journal ".$orderId." success";
    }

    /**
     * @param $invoiceId
     */
    private function bopis($invoiceId){
        $invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($invoiceId);
        $createJournal = Mage::getModel('journal/journal')->pickupVoucherJournal($invoice);
        echo "reclass journal ".$invoiceId." success";
    }

    /**
     * @param $creditMemoId
     */
    private function creditmemo($creditMemoId){
        $creditMemo = Mage::getResourceModel('sales/order_creditmemo_collection')->addFieldToFilter('increment_id', $creditMemoId)->getFirstItem();
        $createJournal = Mage::getModel('journal/journal')->createReturn($creditMemo);
        echo "reclass journal ".$creditMemoId." success";
    }

    public function run(){
        if($this->_args['bopis'])
        {
            $this->bopis($this->_args['bopis']);
        }elseif($this->_args['creditmemo'])
        {
            $this->creditmemo($this->_args['creditmemo']);
        }elseif($this->_args['reclass'])
        {
            $this->reclass($this->_args['reclass']);
        }else{
            echo $this->usageHelp();
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f journal.php -- [options]
Becarefull with this!!

  bopis          run bopis (pickup voucher) journal. ex. "php -f journal.php --bopis invoiceid"
  creditmemo     only run if need to create voucher refund. run creditmemo journal. ex. "php -f journal.php --return creditmemoid"
  reclass        run reclass journal ex. "php -f journal.php --reclass orderid"
  help           This help

USAGE;
    }
}

$shell = new Mage_Shell_Journal();
$shell->run();