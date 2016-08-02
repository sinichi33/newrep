<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Order_Invoice_Total_Surcharge extends Mage_Sales_Model_Order_Total_Abstract
{

    public function collect(Mage_Sales_Model_Order_Order $order)
    {

        $baseAmount = $order->getOrder()->getBaseFoomanSurchargeAmount();
        $amount = $order->getOrder()->getFoomanSurchargeAmount();

        $order->setBaseFoomanSurchargeAmount($baseAmount);
        $order->setFoomanSurchargeAmount($amount);

        //$baseSurchargeTaxAmount = $order->getOrder()->getBaseFoomanSurchargeTaxAmount();
        //$surchargeTaxAmount = $order->getOrder()->getFoomanSurchargeTaxAmount();
        //tax is already included in appliedTaxes - no need to add it twice
        //$order->setTaxAmount($order->getTaxAmount() + $surchargeTaxAmount);
        //$order->setBaseTaxAmount($order->getBaseTaxAmount() + $baseSurchargeTaxAmount);
        //$order->setGrandTotal($order->getGrandTotal() + $surchargeAmount + $surchargeTaxAmount);
        //$order->setBaseGrandTotal($order->getBaseGrandTotal() + $baseSurchargeAmount + $baseSurchargeTaxAmount);

        $order->setGrandTotal($order->getGrandTotal() + $amount);
        $order->setBaseGrandTotal($order->getBaseGrandTotal() + $baseAmount);

        return $this;
    }
}