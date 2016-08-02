<?php

class Icube_Customgiftcard_Block_List extends Mage_Core_Block_Template
{
    public function getGiftCards($id)
    {
        $giftcards = Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
            ->getCollection()
            ->addFieldToSelect(array('code','type','date_expires','balance','company_id'))
            ->addFieldToFilter('status',1)
            ->addFieldToFilter('customer_id', $id);

        return $giftcards;
    }
}
