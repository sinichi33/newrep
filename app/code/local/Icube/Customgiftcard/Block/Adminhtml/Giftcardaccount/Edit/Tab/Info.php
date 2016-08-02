<?php
class Icube_Customgiftcard_Block_Adminhtml_Giftcardaccount_Edit_Tab_Info extends Enterprise_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Edit_Tab_Info
{

	public function initForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_info');

        $model = Mage::registry('current_giftcardaccount');

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend'=>Mage::helper('enterprise_giftcardaccount')->__('Information'))
        );

        if ($model->getId()){
            $fieldset->addField('code', 'label', array(
                'name'      => 'code',
                'label'     => Mage::helper('enterprise_giftcardaccount')->__('Gift Card Code'),
                'title'     => Mage::helper('enterprise_giftcardaccount')->__('Gift Card Code')
            ));

            $fieldset->addField('state_text', 'label', array(
                'name'      => 'state_text',
                'label'     => Mage::helper('enterprise_giftcardaccount')->__('Status'),
                'title'     => Mage::helper('enterprise_giftcardaccount')->__('Status')
            ));
        }

        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Active'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Active'),
            'name'      => 'status',
            'required'  => true,
            'options'   => array(
                Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_ENABLED =>
                    Mage::helper('enterprise_giftcardaccount')->__('Yes'),
                Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_DISABLED =>
                    Mage::helper('enterprise_giftcardaccount')->__('No'),
            ),
        ));
        if (!$model->getId()) {
            $model->setData('status', Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_ENABLED);
        }

        $fieldset->addField('is_redeemable', 'select', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Redeemable'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Redeemable'),
            'name'      => 'is_redeemable',
            'required'  => true,
            'options'   => array(
                Enterprise_GiftCardAccount_Model_Giftcardaccount::REDEEMABLE =>
                    Mage::helper('enterprise_giftcardaccount')->__('Yes'),
                Enterprise_GiftCardAccount_Model_Giftcardaccount::NOT_REDEEMABLE =>
                    Mage::helper('enterprise_giftcardaccount')->__('No'),
            ),
        ));
        if (!$model->getId()) {
            $model->setData('is_redeemable', Enterprise_GiftCardAccount_Model_Giftcardaccount::REDEEMABLE);
        }

        $field = $fieldset->addField('website_id', 'select', array(
            'name'      => 'website_id',
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Website'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Website'),
            'required'  => true,
            'values'    => Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(true),
        ));
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);

        $fieldset->addType('price', 'Enterprise_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Form_Price');

        $fieldset->addField('balance', 'price', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Balance'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Balance'),
            'name'      => 'balance',
            'class'     => 'validate-number',
            'required'  => true,
            'note'      => '<div id="balance_currency"></div>'
        ));

        $fieldset->addField('date_expires', 'date', array(
            'name'   => 'date_expires',
            'label'  => Mage::helper('enterprise_giftcardaccount')->__('Expiration Date'),
            'title'  => Mage::helper('enterprise_giftcardaccount')->__('Expiration Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        ));

        $fieldset->addField('type', 'select', array(
          'label'     => Mage::helper('enterprise_giftcardaccount')->__('Type'),
          'required'  => true,
          'values'    => Mage::helper('customgiftcard')->typeOptionArray(false),
          'name'      => 'type',
          'disabled'	=> 'disabled'
          ));

        $fieldset->addField('campaign_name', 'text', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Campaign Name'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Campaign Name'),
            'name'      => 'campaign_name'
        ));

        $form->setValues($model->getData());

        $this->setForm($form);
        return $this;
    }

}
			