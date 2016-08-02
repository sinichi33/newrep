<?php

class Icube_Creditmemo_Block_Adminhtml_Sales_Creditmemo_Create_Refundasgc extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Totals
{
    protected $_source;

    protected function _prepareLayout()
    {

        /**
         * ++ hendywijaya
         * dirty hack to letting the reload function know between Update QTY or Calculate
         */
        $onclick = "$('creditmemo_ajax_action').setValue('calculate');submitAndReloadArea($('creditmemo_item_container'),'".$this->getUpdateUrl()."');
                    $('creditmemo_ajax_action').setValue('update_qty');";

        $this->setChild(
            'calculate_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('sales')->__('Calculate'),
                'class'     => 'button',
                'onclick'   => $onclick,
            ))
        );
        return parent::_prepareLayout();
    }

    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_source  = $parent->getSource();
        $refundasgc = new Varien_Object(array(
            'code'      => 'refundasgc',
            'block_name'=> $this->getNameInLayout()
        ));

        $parent->addTotal($refundasgc);
        return $this;
    }

    public function getSource()
    {
        return $this->_source;
    }

    public function getRefundAndCashAmount()
    {
        $creditmemo = $this->getSource();

        // determine whether this is triggered by "update QTY's" or "Calculate" button
        $request = $this->getRequest();
        $creditMemoAjaxAction = $request->getPost('creditmemo_ajax_action') == null ? 'update_qty' : $request->getPost('creditmemo_ajax_action');

        /** @var Amasty_Orderattr_Model_Sales_Order $order */
        $order = $creditmemo->getOrder();

        $orderGcAmount = $order->getGiftCardsAmount();
        $orderGrandTotal = $order->getBaseSubtotal()
            +$order->getBaseTaxAmount()
            +$order->getBaseDiscountAmount()
            +$order->getBaseShippingAmount()
            +$order->getBaseFoomanSurchargeAmount();

        /**
         * Calculate various helper variables
         */
        $totalPayments = $orderGcAmount + $order->getBaseTotalInvoiced(); // total payment of all MOP
        $totalItemsSalesPrice = $orderGrandTotal - $order->getBaseShippingAmount();

        /**
         * in case where GC payment is equal or less than total item sales price
         */
        if ($orderGcAmount <= $totalItemsSalesPrice) {
            $totalItemsPaidByGc = $orderGcAmount; // all GC allocated to item sales price

            // get outstanding payment, and allocate cash to it (if any, > 0 )
            $totalItemsPaidByCash = ($totalItemsSalesPrice - $orderGcAmount) > 0 ? ($totalItemsSalesPrice - $orderGcAmount) : 0;

            $totalShippingPaidByGc = 0;
            // allocate any remaining cash to shipping
            $totalShippingPaidByCash = $order->getBaseTotalInvoiced() - $totalItemsPaidByCash;

            // raise exception if we fail to allocate remaining cash to shipping
            if ($totalShippingPaidByCash != $order->getBaseShippingAmount()) {
                Mage::throwException(
                    Mage::helper('sales')->__('Error when calculating shipping paid by cash: %s compared to base shipping amount: %s',$totalShippingPaidByCash,$order->getBaseShippingAmount())
                );
            }

        }
        /**
         * in case where GC payment is more than total item sales price
         */
        elseif ($orderGcAmount > $totalItemsSalesPrice) {
            $totalItemsPaidByGc = $totalItemsSalesPrice; // allocate all sales price to be paid by Gc
            $totalItemsPaidByCash = 0; // no cash payment

            $residualGc = $orderGcAmount - $totalItemsPaidByGc;
            $totalShippingPaidByGc = ($order->getBaseShippingAmount() - $residualGc) >= 0 ? $residualGc : 0;
            $totalShippingPaidByCash = $order->getBaseShippingAmount() - $totalShippingPaidByGc;
        }

        $refundAsGc = 0;
        $refundAsCash = 0;

        /**
         * Ajax Action: UPDATE QTY
         */
        if ($creditMemoAjaxAction == 'update_qty') {
            $prorateGc = 0;
            // PRORATE GC based on each item
            /** @var Mage_Sales_Model_Order_Creditmemo_Item $creditmemoItem */
            foreach ($creditmemo->getAllItems() as $creditmemoItem)
            {
                $rowAfterDiscount = $creditmemoItem->getRowTotal() + $creditmemoItem->getHandlingFeeItem() - $creditmemoItem->getDiscountAmount();
                $prorateGc += ( $rowAfterDiscount / $totalItemsSalesPrice ) * $totalItemsPaidByGc;
            }
            $refundAsGc = round($prorateGc);
        }
        /**
         * Ajax Action: CALCULATE
         */
        elseif ($creditMemoAjaxAction == 'calculate') {
            // if ajax action is calculate, no need to calculate any prorated
            $refundAsGc = (int)$creditmemo->getRefundAsGc(); // get from input instead

                        // validate refund shipping
                        if ($creditmemo->getShippingAmount() < 0 ) {
                            $creditmemo->setShippingAmount(0);
                            Mage::throwException(
                                Mage::helper('sales')->__('Refund shipping must be positive number')
                            );
                        }
            /* disable validation for now
                                    // validate customer penalty
                                    if ($creditmemo->getAdjustmentNegative() > $creditmemo->getBaseGrandTotal()) {
                                        $creditmemo->setAdjustmentNegative(0);
                                        Mage::throwException(
                                            Mage::helper('sales')->__('Maximum customer penalty (%s) cannot be over Credit Memo total (%)', $creditmemo->getAdjustmentNegative(),$creditmemo->getBaseGrandTotal())
                                        );
                                    }*/

        }

        // validate refund as gc
        if ($refundAsGc > $creditmemo->getGrandTotal()) {
            $refundAsGc = $creditmemo->getGrandTotal();
        }

        // calculate any refund as cash
        if ($creditmemo->getGrandTotal() > $refundAsGc) {
            if ($totalItemsPaidByCash > 0) {
                $refundAsCash = $creditmemo->getGrandTotal() - $refundAsGc;
            }
            // if there's shipping cash payment
            elseif ($creditmemo->getShippingAmount() > 0 && $totalShippingPaidByCash > 0) {
                $refundAsCash = ($creditmemo->getShippingAmount() / $order->getBaseShippingAmount()) * $totalShippingPaidByCash;

                if ($creditmemo->getShippingAmount() - $refundAsCash > 0) {
                    // allocate to refund as gc
                    $refundAsGc += ($creditmemo->getShippingAmount() - $refundAsCash);
                }

            }
            // when refund shipping is applied, and there's no cash payment
            elseif ($creditmemo->getShippingAmount() > 0 && ($order->getBaseTotalInvoiced() <= 0)) {
                $refundAsGc += ($creditmemo->getShippingAmount() - $creditmemo->getAdjustmentNegative());
            }
        }

        if ($refundAsGc < 0) {
            $refundAsGc = (int)$creditmemo->getRefundAsGc();
            Mage::throwException(
                Mage::helper('sales')->__('Refund as GC cannot be less than zero')
            );
        }

        // round the numbers
        $refundAsGc = round($refundAsGc);
        $refundAsCash = round($refundAsCash);

        $result = array('refundasgc'=>$refundAsGc,'cash'=>$refundAsCash);
        return $result;
    }

    public function getUpdateUrl()
    {
        return $this->getUrl('*/*/updateQty', array(
            'order_id'=>$this->getCreditmemo()->getOrderId()
        ));
    }

}