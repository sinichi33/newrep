<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Helper_Fixes extends Mage_Core_Helper_Abstract
{

    /**
     * if there are zero items in a creditmemo the subtotal incl amount
     * still displays the surcharge tax, fix it here
     * display issue only
     * Magento pre 1.4.1 creditmemos are covered by
     * @see subtotalIncl
     *
     * @param $source
     * @param $parent
     */
    public function zeroItemCreditmemo($source, $parent)
    {
        if (!$source instanceof Mage_Sales_Model_Order_Creditmemo
            || version_compare(Mage::getVersion(), '1.4.1.0', '<')
        ) {
            return;
        }
        $this->_adjustSubtotalInclTax($source, $parent);
    }

    /**
     * need to adjust the subtotal inclusive amount since it only takes into
     * consideration only the "known" taxes shipping and item taxes
     * calculation issue
     *
     * @param $source
     */
    public function invoiceSubtotalInclTotal($source)
    {
        if (version_compare(Mage::getVersion(), '1.4.2.0', '>=')) {
            $itemTaxes = $this->_collectItemTaxes($source);
            $source->setSubtotalInclTax($source->getSubtotal() + $itemTaxes->getTaxAmount());
            $source->setBaseSubtotalInclTax($source->getBaseSubtotal() + $itemTaxes->getBaseTaxAmount());
        }
    }

    public function subtotalIncl($source, $parent)
    {
        if (version_compare(Mage::getVersion(), '1.4.1.0', '<')) {
            $this->_adjustSubtotalInclTax($source, $parent);
        }
    }

    public function adjustForMissingNegativeTaxAmount($source)
    {
        if (version_compare(Mage::getVersion(), '1.9.1.0', '>=') && $source->getBaseFoomanSurchargeTaxAmount() < 0) {
            $source->setTaxAmount($source->getTaxAmount() - $source->getFoomanSurchargeTaxAmount());
            $source->setBaseTaxAmount($source->getBaseTaxAmount() - $source->getBaseFoomanSurchargeTaxAmount());
            $source->setGrandTotal($source->getGrandTotal() - $source->getFoomanSurchargeTaxAmount());
            $source->setBaseGrandTotal($source->getBaseGrandTotal() - $source->getBaseFoomanSurchargeTaxAmount());
        }
    }

    protected function _adjustSubtotalInclTax($source, $parent)
    {
        if ($parent->getTotal('subtotal_incl')
            && $source->getBaseSubtotalInclTax() == 0
            && $parent->getTotal('subtotal_incl')->getBaseValue() > 0
        ) {
            $subtotalIncl = $parent->getTotal('subtotal_incl');
            $subtotalIncl->setValue($subtotalIncl->getValue() - $source->getFoomanSurchargeTaxAmount());
            $subtotalIncl->setBaseValue($subtotalIncl->getBaseValue() - $source->getBaseFoomanSurchargeTaxAmount());
        }
        if (Mage::getStoreConfig(Mage_Tax_Model_Config::XML_PATH_DISPLAY_SALES_SUBTOTAL, $source->getStoreId())
            == Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX
        ) {
            if ($parent->getTotal('subtotal')
                && $source->getBaseSubtotalInclTax() == 0
                && $parent->getTotal('subtotal')->getBaseValue() > 0
            ) {
                $subtotalIncl = $parent->getTotal('subtotal');
                $subtotalIncl->setValue($subtotalIncl->getValue() - $source->getFoomanSurchargeTaxAmount());
                $subtotalIncl->setBaseValue($subtotalIncl->getBaseValue() - $source->getBaseFoomanSurchargeTaxAmount());
            }
        }
    }

    /**
     * The subtotal block Mage_Tax_Block_Checkout_Subtotal only takes into consideration known
     * totals (subtotal and shipping) and their taxes when displaying a tax inclusive total
     * when using Fixed Product Taxes (FPT)
     * fix this for Surcharge Tax in this observer
     * triggered by event core_block_abstract_to_html_before
     *
     * @param $block
     *
     * @return void
     */
    public function adjustCheckoutSubtotal($block)
    {
        $totals = $block->getTotals();
        if (isset($totals['surcharge'])) {
            $store = $totals['surcharge']->getAddress()->getQuote()->getStore();
            if (Mage::getSingleton('tax/config')->displayCartSubtotalInclTax($store)) {
                $block->getTotal()->setValue(
                    $block->getTotal()->getValue() -
                    $totals['surcharge']->getAddress()->getFoomanSurchargeTaxAmount()
                );
            }
            $block->getTotal()->setValueInclTax(
                $block->getTotal()->getValueInclTax() -
                $totals['surcharge']->getAddress()->getFoomanSurchargeTaxAmount()
            );
        }
    }

    /**
     * loop over items to collect tax amount
     * @param $source
     *
     * @return Varien_Object
     */
    protected function _collectItemTaxes($source)
    {
        $tax = 0;
        $baseTax = 0;
        foreach ($source->getAllItems() as $item) {
            $tax += $item->getTaxAmount();
            $baseTax += $item->getBaseTaxAmount();
        }
        $collectedTaxes = new Varien_Object();
        $collectedTaxes->setTaxAmount($tax);
        $collectedTaxes->setBaseTaxAmount($baseTax);
        return $collectedTaxes;
    }

}
