<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_Sales_Pdf_Surcharge extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    /**
     * prepare surcharge for display in the pdfs
     * @return array
     */
    public function getTotalsForDisplay()
    {
        $amount = $this->getOrder()->formatPriceTxt($this->getSource()->getFoomanSurchargeAmount());
        $label = Mage::helper('surcharge/compatibility')->escapeHtmlByVersion(
            $this->getOrder()->getFoomanSurchargeDescription()
        );
        $amountInclTax
            = $this->getSource()->getFoomanSurchargeAmount() + $this->getSource()->getFoomanSurchargeTaxAmount();
        $amountInclTax = $this->getOrder()->formatPriceTxt($amountInclTax);
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;

        if (Mage::helper('surcharge')->displayBothSales()) {
            $totals = array(
                array(
                    'amount'    => $this->getAmountPrefix() . $amount,
                    'label'     => $label . ':',
                    'font_size' => $fontSize
                ),
                array(
                    'amount'    => $this->getAmountPrefix() . $amountInclTax,
                    'label'     => $label . ':',
                    'font_size' => $fontSize
                ),
            );
        } elseif (Mage::helper('surcharge')->displayIncludeTaxSales()) {
            $totals = array(
                array(
                    'amount'    => $this->getAmountPrefix() . $amountInclTax,
                    'label'     => $label . ':',
                    'font_size' => $fontSize
                )
            );
        } else {
            $totals = array(
                array(
                    'amount'    => $this->getAmountPrefix() . $amount,
                    'label'     => $label . ':',
                    'font_size' => $fontSize
                )
            );
        }

        return $totals;
    }

    public function getAmount()
    {
        return $this->getSource()->getFoomanSurchargeAmount();
    }

}
