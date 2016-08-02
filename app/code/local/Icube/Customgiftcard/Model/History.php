<?php

class Icube_Customgiftcard_Model_History extends Enterprise_GiftCardAccount_Model_History
{
    
    protected function _getUsedAdditionalInfo()
    {
        if ($this->getGiftcardaccount()->getOrder()) {
            $orderId = $this->getGiftcardaccount()->getOrder()->getIncrementId();
            return Mage::helper('enterprise_giftcardaccount')->__('Order #%s.', $orderId);
        } elseif (Mage::getSingleton('core/session')->getForfeitBalance()) {
            return Mage::helper('enterprise_giftcardaccount')->__('Forfeit Balance');
        }

        return '';
    }

}
