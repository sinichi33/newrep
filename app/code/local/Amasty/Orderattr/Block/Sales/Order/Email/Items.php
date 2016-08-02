<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Sales_Order_Email_Items extends Mage_Sales_Block_Order_Email_Items
{
    protected function _toHtml()
    {
        if (!Mage::getStoreConfig('sales_email/order/include')) {
            return parent::_toHtml();
        }
        if ($this->getOrder()) {
            $order = $this->getOrder();
        } else {
            $order = Mage::registry('current_order');
        }
        $emailBlock = $this->getLayout()->createBlock('amorderattr/fields_email');
        $emailBlock->setOrder($order);
        return $emailBlock->toHtml() . parent::_toHtml();
    }
}