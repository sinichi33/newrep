<?php

class Fooman_Surcharge_Helper_Tax extends Mage_Tax_Helper_Data
{
    /**
     * Magento disconnects applied taxes and sales_order_tax_item
     * re-add surcharge tax here
     *
     * @param $source
     *
     * @return array
     */
    public function getCalculatedTaxes($source)
    {

        $taxesItemsOnly = parent::getCalculatedTaxes($source);
        if ($source->getFoomanSurchargeTaxAmount() != 0
            && ($this->_getFromRegistry('current_invoice')
                || $this->_getFromRegistry('current_creditmemo'))
        ) {

            $taxesItemsOnly[] = array(
                'tax_amount' => $source->getFoomanSurchargeTaxAmount(),
                'base_tax_amount' => $source->getBaseFoomanSurchargeTaxAmount(),
                'title' => $this->__('Surcharge Tax'),
                'percent' => null
            );
        }

        return $taxesItemsOnly;
    }
}
