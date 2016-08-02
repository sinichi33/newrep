<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2009 Maison du Logiciel (http://www.maisondulogiciel.com)
 * @author : ALLAIRE Benjamin
 * @mail : benjamin@boostmyshop.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MDN_AutoCancelOrder_Helper_Data extends Mage_Core_Helper_Abstract {

    const DEFAULT_HOURS_IF_CONFIG_IS_EMPTY = 3;
    /**
     * Apply checking for cancel orders via button
     */
    public function apply() {

        $ordersToCancelCollection = $this->getOrdersToCancel();

        //avoid much cron is the et config is not set to avodi to cancell all orders !!!
        if($ordersToCancelCollection != null) {

            foreach ($ordersToCancelCollection as $order) {

                try {

                    //avoid FATAL in cron
                    if ($order->getPayment()) {
                        $paymentMethod = $order->getPayment()->getMethod();
                        $nbHour = $this->getDelayForPaymentMethod($paymentMethod);
                    } else {
                        throw new Exception($this->__("Order #%s doesn't have any payment method", $order->getIncrementId()));
                    }

                    $nbHourOrder = $this->getOrderNbHoursFromNow($order);

                    //check that number of day between order creation date and now > delay
                    if ($nbHourOrder >= $nbHour) {

                        // check if the config allow to unhold orders
                        if (Mage::getStoreConfig('autocancelorder/general/unhold')) {
                            // check if order is on hold
                            if ($order->getstate() == Mage_Sales_Model_Order::STATE_HOLDED && $order->canUnHold()) {
                                $this->unHoldOrder($order);
                            }
                        }

                        if ($order->canCancel()) {
                            $this->cancelOrder($order);
                        } else {
                            $this->addLog($this->__("Order #%s can not be canceled, check config 'unhold before cancel' or hold it", $order->getIncrementId()));
                        }
                    }
                } catch (Exception $ex) {
                    $this->addLog($this->__("An error occurred : %s ", $ex->getTraceAsString()));
                }
            }
        }
    }

    public function getOrderNbHoursFromNow($order){
        $created = $order->getCreatedAt();
        $currentDate = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
        //Rounding keeping decimal enable to prevent to cancel if number of hour is not fully passed
        return round((strtotime($currentDate) - strtotime($created)) / (60 * 60),2);
    }

    /**
     * return an array of orders witch are configured to be canceled (via backoffice)
     */
    public function getOrdersToCancel() {

        $collection = null;

        // orders type with selected status
        $statusOrders = Mage::getStoreConfig('autocancelorder/general/apply_on_orders'); // string(21) "pending,closed,holded" 
        $statusOrders = explode(",", $statusOrders);

        // limit of date for considerate orders
        $dateOrderAfter = Mage::getStoreConfig('autocancelorder/general/consider_order_after'); // string(10) "2013-07-23"
        $dateOrderAfter .= " 00:00:00"; // 2013-07-23 00:00:00

        //security to avoid to let the cron orders when config was not set yet
        if($statusOrders) {
            // get orders with previous filters
            $collection = Mage::getModel('sales/order')
                ->getCollection()
                ->addFieldToFilter('status', $statusOrders);

            if ($dateOrderAfter) {
                $collection->addFieldToFilter('created_at', array('gt' => $dateOrderAfter));
            }
        }

        return $collection;
    }

    /**
     * add a log info in table 'auto_cancel_order_log' for historic
     */
    public function addLog($message) {
        $aco = Mage::getModel('AutoCancelOrder/Log');
        $aco->setaco_date(date("Y-m-d H:i:s"));
        $aco->setaco_message($message);
        $aco->save();
    }

    /**
     * get the number of hours allowed to keep an order health
     * 
     * @param <type> $paymentMethod 
     * @return <type> $nbDay
     */
    public function getDelayForPaymentMethod($paymentMethod) {

        //delay from payment method if set in config
        if($paymentMethod) {
            $nbHour = Mage::getStoreConfig('autocancelorder/delay_cancelation/' . $paymentMethod);
        }

        //default delay config
        if (empty($nbHour)) {
            $nbHour = Mage::getStoreConfig('autocancelorder/delay_cancelation/default');
        }

        //if config is badly set
        if (empty($nbHour)) {
            $nbHour = self::DEFAULT_HOURS_IF_CONFIG_IS_EMPTY;
        }

        return $nbHour;
    }

    /**
     * Unhold order
     * @param <type> $order 
     */
    public function unholdOrder($order) {
        $order->unHold();
        $order->addStatusHistoryComment($this->__("Unhold by auto cancel order extension."));
        $order->save();
        $this->addLog($this->__("Order #%s successfully unhold", $order->getIncrementId()));
    }

    /**
     * Cancel order
     * @param <type> $order
     */
    public function cancelOrder($order) {
        $order->cancel();
        $order->addStatusHistoryComment($this->__("Canceled by auto cancel order extension."));
        $order->save();
        $this->addLog($this->__("Order #%s was canceled successfully", $order->getIncrementId()));
    }


    
    /**
     * get the current order on the log page, when clicking
     * on a log -> get the order.
     *  
     * @param $aocId : the id of autocancelorder entry
     * @return : id of current order
     */
    public function getOrderIdToView($aocId)
    {
        $orderId = null;

        // get the message of log
        $log = Mage::getModel('AutoCancelOrder/Log')->load($aocId);

        // extract increment id
        $incrementId = preg_match("/[0-9]{9,15}/", $log->getaco_message(), $matches);

        if ($incrementId) {
            // load order   
            $order = Mage::getModel('sales/order')->loadbyIncrementId($matches[0]);
            if ($order && $order->getId() > 0) {
                $orderId = $order->getId();
            }
        }
        if (!$orderId) {
            throw new Exception($this->__("Error order incrementId can not be found in log message"));
        }
        
        return $orderId;
    }
    
    
}