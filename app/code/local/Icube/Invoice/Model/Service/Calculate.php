<?php

class Icube_Invoice_Model_Service_Calculate extends Mage_Core_Model_Abstract 
{
	public function calculateHandlingGiftcard($shipInfo, $invoice)
	{
		$order = $invoice->getOrder();
			// Calculate handling Fee
			$surchargeHelper = Mage::helper('surcharge');
	        $amount = $shipInfo['handling_fee_item'];
	        $baseTaxAmount = $order->getBaseFoomanSurchargeTaxAmount() - $order->getBaseFoomanSurchargeTaxAmountInvoiced();
	        $taxAmount = $order->getFoomanSurchargeTaxAmount() - $order->getFoomanSurchargeTaxAmountInvoiced();

            //set Invoice Amounts
            $invoice->setBaseFoomanSurchargeAmount($amount);
            $invoice->setFoomanSurchargeAmount($amount);
            $invoice->setBaseFoomanSurchargeTaxAmount($baseTaxAmount);
            $invoice->setFoomanSurchargeTaxAmount($taxAmount);

            Mage::helper('surcharge/fixes')->invoiceSubtotalInclTotal($invoice);
            Mage::helper('surcharge/fixes')->adjustForMissingNegativeTaxAmount($invoice);

            $surchargeHelper->debug('INVOICE $baseSurchargeAmount' . $amount);
            $surchargeHelper->debug('INVOICE $surchargeAmount' . $amount);
            $surchargeHelper->debug('INVOICE $baseSurchargeTaxAmount' . $baseTaxAmount);
            $surchargeHelper->debug('INVOICE $surchargeTaxAmount' . $taxAmount);

            //set Order Amounts
            $order->setBaseFoomanSurchargeAmountInvoiced(
                $order->getBaseFoomanSurchargeAmountInvoiced() + $amount
            );
            $order->setFoomanSurchargeAmountInvoiced($order->getFoomanSurchargeAmountInvoiced() + $amount);
            $order->setBaseFoomanSurchargeTaxAmountInvoiced(
                $order->getBaseFoomanSurchargeTaxAmountInvoiced() + $baseTaxAmount
            );
            $order->setFoomanSurchargeTaxAmountInvoiced(
                $order->getFoomanSurchargeTaxAmountInvoiced() + $taxAmount
            );
			// END - Calculate handling Fee
			
			// Update Prorate GC - Final

				$orderBaseTotalWithHandling = $order->getBaseSubtotal()
        								+$order->getBaseTaxAmount()
										+$order->getBaseDiscountAmount()
										+$order->getBaseFoomanSurchargeAmount();
				
				$orderTotalWithHandling = $order->getSubtotal()
							+$order->getBaseTaxAmount()
							+$order->getDiscountAmount()
							+$order->getFoomanSurchargeAmount();
				
				$invoiceBaseTotalWithHandling = $invoice->getBaseSubtotal()
									+$invoice->getBaseTaxAmount()
									+$invoice->getBaseDiscountAmount()
									+$invoice->getBaseFoomanSurchargeAmount();
				
				$invoiceTotalWithHandling = $invoice->getSubtotal()
											+$invoice->getTaxAmount()
											+$invoice->getDiscountAmount()
											+$invoice->getFoomanSurchargeAmount();
				
				$invoiceBaseTotalWithHandlingShipping = $invoiceBaseTotalWithHandling + $invoice->getBaseShippingAmount();
				$invoiceTotalWithHandlingShipping = $invoiceTotalWithHandling + $invoice->getShippingAmount();
				
				$gcBase = $order->getBaseGiftCardsAmount();
				$gc = $order->getGiftCardsAmount();
				
				$gcUsageProductBase = ($gcBase <= $orderBaseTotalWithHandling) ? $gcBase : $orderBaseTotalWithHandling;
				$gcUsageProduct = ($gc <= $orderTotalWithHandling) ? $gc : $orderTotalWithHandling;
				
				$gcForShippingBase = ($gcBase - $gcUsageProductBase ) < 0 ? 0 : ($gcBase - $gcUsageProductBase);
				$gcForShipping = ($gc - $gcUsageProduct) < 0 ? 0 : ($gc - $gcUsageProduct);
				
				$prorateGcBase = $invoiceBaseTotalWithHandling / $orderBaseTotalWithHandling * $gcUsageProductBase;
				$prorateGc = $invoiceTotalWithHandling / $orderTotalWithHandling * $gcUsageProduct;
				
				// check if DC
				if($shipInfo['delivery_pickup'] == 'delivery' && $shipInfo['store_code'] == 'DC')
				{
					$prorateGcInvoiceBase = $prorateGcBase + $gcForShippingBase;
					$prorateGcInvoice = $prorateGc +  $gcForShipping;
				}
				else
				{
					$prorateGcInvoiceBase = $prorateGcBase;
					$prorateGcInvoice = $prorateGc;
				}
				
				// Set Invoice GC amount
				$invoice->setBaseGiftCardsAmount($prorateGcInvoiceBase);
				$invoice->setGiftCardsAmount($prorateGcInvoice); 
				
				// Set Invoice Grand Total
				$invoice->setBaseGrandTotal($invoiceBaseTotalWithHandlingShipping - $prorateGcInvoiceBase);
	            $invoice->setGrandTotal($invoiceTotalWithHandlingShipping - $prorateGcInvoice);
	            
			// End of set Prorate GC
			
            $invoice->setDeliveryPickup($shipInfo['delivery_pickup']);
            $invoice->setStoreCode($shipInfo['store_code']);
            $invoice->setCompanyId($shipInfo['company_id']);
            $invoice->setPickupLocationCode($shipInfo['pickup_location']);
            
	}
}