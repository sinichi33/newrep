<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Order_Creditmemo_Total_Surcharge extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{

    /**
     * get surcharge amount for creditmemo
     *
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     *
     * @return $this
     */
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {

        $order = $creditmemo->getOrder();
        $invoice = $creditmemo->getInvoice();
        $totalhandlingfeeitem = 0;
		
		/* Icube Update - calculate Handling fee per item */
        foreach ($creditmemo->getAllItems() as $item) {
            $orderItem = $item->getOrderItem();

            if ($orderItem->isDummy()) {
                continue;
            }

            $handlingQtyOrdered = $orderItem->getHandlingFeeItem() / $orderItem->getQtyOrdered();
            $handlingfeeitem	= $handlingQtyOrdered*$item->getQty();
            $totalhandlingfeeitem += $handlingfeeitem;

			$item->setHandlingFeeItem($handlingfeeitem);
        }
		
		//work out amounts for current creditmemo
        if ($invoice) {
            $baseTaxAmount = $invoice->getBaseFoomanSurchargeTaxAmount();
            $taxAmount = $invoice->getFoomanSurchargeTaxAmount();
        } else {
            $baseTaxAmount = $order->getBaseFoomanSurchargeTaxAmount() - $order->getBaseFoomanSurchargeTaxAmountRefunded();
            $taxAmount = $order->getFoomanSurchargeTaxAmount() - $order->getFoomanSurchargeTaxAmountRefunded();
        }
        
        //set Creditmemo Amounts
        $creditmemo->setBaseFoomanSurchargeAmount($totalhandlingfeeitem);
        $creditmemo->setFoomanSurchargeAmount($totalhandlingfeeitem);
        $creditmemo->setBaseFoomanSurchargeTaxAmount($baseTaxAmount);
        $creditmemo->setFoomanSurchargeTaxAmount($taxAmount);
		
		$creditmemo->setTaxAmount($creditmemo->getTaxAmount() + $taxAmount);
        $creditmemo->setBaseTaxAmount($creditmemo->getBaseTaxAmount() + $baseTaxAmount);
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $totalhandlingfeeitem + $taxAmount);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $totalhandlingfeeitem + $baseTaxAmount);
        
        $order->setBaseFoomanSurchargeAmountRefunded(
            $order->getBaseFoomanSurchargeAmountRefunded() + $totalhandlingfeeitem
        );
        $order->setFoomanSurchargeAmountRefunded($order->getFoomanSurchargeAmountRefunded() + $totalhandlingfeeitem);
        $order->setBaseFoomanSurchargeTaxAmountRefunded(
            $order->getBaseFoomanSurchargeTaxAmountRefunded() + $baseTaxAmount
        );
        $order->setFoomanSurchargeTaxAmountRefunded(
            $order->getFoomanSurchargeTaxAmountRefunded() + $taxAmount
        );		
		
// 		Original Code
/*
        //work out amounts for current creditmemo
        if ($invoice) {
            $baseAmount = $invoice->getBaseFoomanSurchargeAmount();
            $amount = $invoice->getFoomanSurchargeAmount();
            $baseTaxAmount = $invoice->getBaseFoomanSurchargeTaxAmount();
            $taxAmount = $invoice->getFoomanSurchargeTaxAmount();
        } else {
            $baseAmount
                = $order->getBaseFoomanSurchargeAmount() - $order->getBaseFoomanSurchargeAmountRefunded();
            $amount = $order->getFoomanSurchargeAmount() - $order->getFoomanSurchargeAmountRefunded();
            $baseTaxAmount
                = $order->getBaseFoomanSurchargeTaxAmount() - $order->getBaseFoomanSurchargeTaxAmountRefunded();
            $taxAmount = $order->getFoomanSurchargeTaxAmount() - $order->getFoomanSurchargeTaxAmountRefunded();
        }

        //if we haven given a discount via Surcharge and we are partially refunding we want to distribute it evenly
        if ($baseAmount < 0
            && ($creditmemo->getBaseSubtotal() < ($order->getBaseSubtotal() - $order->getBaseSubtotalRefunded()))
        ) {
            $factor = Mage::helper('surcharge')->surchargeOn($creditmemo, $creditmemo->getStoreId())
                / Mage::helper('surcharge')->surchargeOn($order, $order->getStoreId());
            $baseAmount = Mage::app()->getStore()->roundPrice($baseAmount * $factor);
            $amount = Mage::app()->getStore()->roundPrice($amount * $factor);
            $baseTaxAmount = Mage::app()->getStore()->roundPrice($baseTaxAmount * $factor);
            $taxAmount = Mage::app()->getStore()->roundPrice($taxAmount * $factor);
        }

        if ($amount) {
            $creditmemo->setTaxAmount($creditmemo->getTaxAmount() + $taxAmount);
            $creditmemo->setBaseTaxAmount($creditmemo->getBaseTaxAmount() + $baseTaxAmount);
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $amount + $taxAmount);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseAmount + $baseTaxAmount);

            //set Creditmemo Amounts
            $creditmemo->setBaseFoomanSurchargeAmount($baseAmount);
            $creditmemo->setFoomanSurchargeAmount($amount);
            $creditmemo->setBaseFoomanSurchargeTaxAmount($baseTaxAmount);
            $creditmemo->setFoomanSurchargeTaxAmount($taxAmount);

            //set Order Amounts
            $order->setBaseFoomanSurchargeAmountRefunded(
                $order->getBaseFoomanSurchargeAmountRefunded() + $baseAmount
            );
            $order->setFoomanSurchargeAmountRefunded($order->getFoomanSurchargeAmountRefunded() + $amount);
            $order->setBaseFoomanSurchargeTaxAmountRefunded(
                $order->getBaseFoomanSurchargeTaxAmountRefunded() + $baseTaxAmount
            );
            $order->setFoomanSurchargeTaxAmountRefunded(
                $order->getFoomanSurchargeTaxAmountRefunded() + $taxAmount
            );
        }
*/

        Mage::helper('surcharge/fixes')->invoiceSubtotalInclTotal($creditmemo);
        Mage::helper('surcharge/fixes')->adjustForMissingNegativeTaxAmount($creditmemo);

        return $this;
    }
}
