<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Block_Adminhtml_Sales_Order_Creditmemo_Create_Surcharge extends
    Mage_Adminhtml_Block_Sales_Order_Creditmemo_Totals
{

    protected $_surchargeAmount = '';
    protected $_description = '';

    public function initTotals()
    {
        $source = $this->getSource();
        if ($source->getFoomanSurchargeAmount() != 0) {
            $parent = $this->getParentBlock();
            if (Mage::helper('surcharge')->displayIncludeTaxSales()) {
                $this->_surchargeAmount
                    = $source->getFoomanSurchargeAmount() + $source->getFoomanSurchargeTaxAmount();
                $this->_description = Mage::helper('surcharge')->__(
                    '%s (Incl. Tax)', $source->getOrder()->getFoomanSurchargeDescription()
                );
            } else {
                $this->_surchargeAmount = $source->getFoomanSurchargeAmount();
                $this->_description = Mage::helper('surcharge')->__(
                    '%s (Excl. Tax)', $source->getOrder()->getFoomanSurchargeDescription()
                );
            }
            $surcharge = new Varien_Object(
                array(
                    'block_name' => $this->getNameInLayout(),
                    'code'       => 'surcharge'
                )
            );

            $parent->addTotal($surcharge);
            $fixHelper = Mage::helper('surcharge/fixes');
            $fixHelper->zeroItemCreditmemo($source, $parent);

        }
        return $this;
    }


    public function getSurchargeAmount()
    {
        return $this->_surchargeAmount;
    }

    public function getSurchargeDescription()
    {
        return Mage::helper('surcharge/compatibility')->escapeHtmlByVersion($this->_description);
    }

}