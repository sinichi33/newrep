<?php


class Icube_Customgiftcard_Block_Adminhtml_Giftcardaccount_Edit_Tabs extends Enterprise_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Edit_Tabs
{

    protected function _beforeToHtml()
    {
        $this->addTab('info', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Information'),
            'content'   => $this->getLayout()->createBlock('enterprise_giftcardaccount/adminhtml_giftcardaccount_edit_tab_info')->initForm()->toHtml(),
            'active'    => true
        ));

        $this->addTab('send', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Send Gift Card'),
            'content'   => $this->getLayout()->createBlock('enterprise_giftcardaccount/adminhtml_giftcardaccount_edit_tab_send')->initForm()->toHtml(),
        ));

        $model = Mage::registry('current_giftcardaccount');
        if ($model->getId()) {
            $this->addTab('history', array(
                'label'     => Mage::helper('enterprise_giftcardaccount')->__('History'),
                'content'   => $this->getLayout()->createBlock('enterprise_giftcardaccount/adminhtml_giftcardaccount_edit_tab_history')->toHtml(),
            ));
        }

        $this->addTab('conditions', array(
            'label'     => Mage::helper('customgiftcard')->__('Conditions'),
            'content'   => $this->getLayout()->createBlock('customgiftcard/adminhtml_giftcardaccount_edit_tab_conditions')->initForm()->toHtml(),
        ));

        return parent::_beforeToHtml();
    }


}
