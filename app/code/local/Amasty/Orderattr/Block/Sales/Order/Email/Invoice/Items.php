<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Sales_Order_Email_Invoice_Items extends Mage_Sales_Block_Order_Email_Invoice_Items
{
    protected function _toHtml()
    {
        if (!Mage::getStoreConfig('sales_email/invoice/include')) {
            return parent::_toHtml();
        }
        $emailBlock = $this->getLayout()->createBlock('amorderattr/fields_email');
        $emailBlock->setOrder($this->getOrder());
        return $emailBlock->toHtml() . parent::_toHtml();
    }
}