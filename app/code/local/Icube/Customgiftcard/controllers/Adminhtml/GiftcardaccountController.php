<?php

/*
*   Overriding Enterprise_GiftCardAccount_Adminhtml_GiftcardaccountController
*
*/
require_once(Mage::getModuleDir('controllers','Enterprise_GiftCardAccount').DS.'Adminhtml'.DS.'GiftcardaccountController.php');

class Icube_Customgiftcard_Adminhtml_GiftcardaccountController extends Enterprise_GiftCardAccount_Adminhtml_GiftcardaccountController
{
    

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('date_expires', 'valid_from_date', 'valid_to_date'));

        return $data;
    }

    
}
