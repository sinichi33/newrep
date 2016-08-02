<?php

/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_Order_Invoice_Total_Surcharge extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{

    /**
     * get surcharge amount for invoice
     *
     * @param Mage_Sales_Model_Order_Invoice $invoice
     *
     * @return $this
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $invoice->setBaseFoomanSurchargeAmount(0);
        $invoice->setFoomanSurchargeAmount(0);
        $invoice->setBaseFoomanSurchargeTaxAmount(0);
        $invoice->setFoomanSurchargeTaxAmount(0);

        $order = $invoice->getOrder();
        $surchargeHelper = Mage::helper('surcharge');

        //work out amounts for current invoice
        $baseAmount = $order->getBaseFoomanSurchargeAmount() - $order->getBaseFoomanSurchargeAmountInvoiced();
        $amount = $order->getFoomanSurchargeAmount() - $order->getFoomanSurchargeAmountInvoiced();
        $baseTaxAmount = $order->getBaseFoomanSurchargeTaxAmount() - $order->getBaseFoomanSurchargeTaxAmountInvoiced();
        $taxAmount = $order->getFoomanSurchargeTaxAmount() - $order->getFoomanSurchargeTaxAmountInvoiced();

        //check if Surcharge has already been added
        if ($baseAmount != 0) {
            $invoice->setTaxAmount($invoice->getTaxAmount() + $taxAmount);
            $invoice->setBaseTaxAmount($invoice->getBaseTaxAmount() + $baseTaxAmount);
            $invoice->setGrandTotal($invoice->getGrandTotal() + $amount + $taxAmount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseAmount + $baseTaxAmount);
            /*if (!$invoice->isLast()) {
                $invoice->setTaxAmount($invoice->getTaxAmount() + $taxAmount);
                $invoice->setBaseTaxAmount($invoice->getBaseTaxAmount() + $baseTaxAmount);
                $invoice->setGrandTotal($invoice->getGrandTotal() + $amount + $taxAmount);
                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseAmount + $baseTaxAmount);
            } else {
                $invoice->setGrandTotal($invoice->getGrandTotal() + $amount);
                $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseAmount);
            }*/
            //if we haven given a discount via Surcharge and we are partially refunding we want to distribute it evenly
            if ($baseAmount < 0 && !$invoice->isLast()) {
                $factor = Mage::helper('surcharge')->surchargeOn($invoice, $invoice->getStoreId())
                    / Mage::helper('surcharge')->surchargeOn($order, $order->getStoreId());
                $baseAmount = Mage::app()->getStore()->roundPrice($baseAmount * $factor);
                $amount = Mage::app()->getStore()->roundPrice($amount * $factor);
                $baseTaxAmount = Mage::app()->getStore()->roundPrice($baseTaxAmount * $factor);
                $taxAmount = Mage::app()->getStore()->roundPrice($taxAmount * $factor);
            }
            //set Invoice Amounts
            $invoice->setBaseFoomanSurchargeAmount($baseAmount);
            $invoice->setFoomanSurchargeAmount($amount);
            $invoice->setBaseFoomanSurchargeTaxAmount($baseTaxAmount);
            $invoice->setFoomanSurchargeTaxAmount($taxAmount);

            Mage::helper('surcharge/fixes')->invoiceSubtotalInclTotal($invoice);
            Mage::helper('surcharge/fixes')->adjustForMissingNegativeTaxAmount($invoice);

            $surchargeHelper->debug('INVOICE $baseSurchargeAmount' . $baseAmount);
            $surchargeHelper->debug('INVOICE $surchargeAmount' . $amount);
            $surchargeHelper->debug('INVOICE $baseSurchargeTaxAmount' . $baseTaxAmount);
            $surchargeHelper->debug('INVOICE $surchargeTaxAmount' . $taxAmount);

// ICUBE Update - Order surcharge amount calculation move to invoice action
            //set Order Amounts
/*
            $order->setBaseFoomanSurchargeAmountInvoiced(
                $order->getBaseFoomanSurchargeAmountInvoiced() + $baseAmount
            );
            $order->setFoomanSurchargeAmountInvoiced($order->getFoomanSurchargeAmountInvoiced() + $amount);
            $order->setBaseFoomanSurchargeTaxAmountInvoiced(
                $order->getBaseFoomanSurchargeTaxAmountInvoiced() + $baseTaxAmount
            );
            $order->setFoomanSurchargeTaxAmountInvoiced(
                $order->getFoomanSurchargeTaxAmountInvoiced() + $taxAmount
            );
*/
        }

        return $this;
    }

}
