<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Block_Adminhtml_Sales_Order_Totals extends Mage_Core_Block_Template
{

    public function initTotals()
    {
        Mage::helper('surcharge')->initSalesOrderTotals($this->getParentBlock());
        return $this;
    }

}