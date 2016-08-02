<?php
 
class Icube_Customgiftcard_Block_Adminhtml_Giftcardaccount_Edit_Tab_Conditions extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_info');

        $model = Mage::registry('current_giftcardaccount');

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend'=>Mage::helper('enterprise_giftcardaccount')->__('Conditions'))
        );

        $fieldset->addField('promo_items', 'select', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Allow for Promo Items'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Allow for Promo Items'),
            'name'      => 'promo_items',
            'class' => 'required-entry',
            'required'  => true,
            'options'   => array(
                Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_ENABLED =>
                    Mage::helper('enterprise_giftcardaccount')->__('Yes'),
                Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_DISABLED =>
                    Mage::helper('enterprise_giftcardaccount')->__('No'),
            ),
        ));
        if (!$model->getId()) {
            $model->setData('promo_items', Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_ENABLED);
        }

        $fieldset->addField('restrict_combine', 'select', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Restrict Combine Usage'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Restrict Combine Usage'),
            'name'      => 'restrict_combine',
            'class' => 'required-entry',
            'required'  => true,
            'options'   => array(
                Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_ENABLED =>
                    Mage::helper('enterprise_giftcardaccount')->__('Yes'),
                Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_DISABLED =>
                    Mage::helper('enterprise_giftcardaccount')->__('No'),
            ),
        ));

        if (!$model->getId()) {
            $model->setData('restrict_combine', Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_ENABLED);
        }

        $fieldset->addField('product_skus', 'textarea', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('List of the SKUs'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('List of the SKUs'),
            'name'      => 'product_skus',
            'note'      => Mage::helper('enterprise_giftcardaccount')->__('separated by comma, ex : SKU1, SKU2, SKU3'),
        ));

        $fieldset->addField('product_skus_exclusion', 'textarea', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('List of the Restricted SKUs'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('List of the Restricted SKUs'),
            'name'      => 'product_skus_exclusion',
            'note'      => Mage::helper('enterprise_giftcardaccount')->__('separated by comma, ex : SKU1, SKU2, SKU3'),
        ));

        $fieldset->addField('category_ids', 'textarea', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('List of the Category Ids'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('List of the Category Ids'),
            'name'      => 'category_ids',
            'note'      => Mage::helper('enterprise_giftcardaccount')->__('separated by comma, ex : 3,16,129'),
        ));

        $fieldset->addField('category_ids_exclusion', 'textarea', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('List of the Restricted Category Ids'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('List of the Restricted Category Ids'),
            'name'      => 'category_ids_exclusion',
            'note'      => Mage::helper('enterprise_giftcardaccount')->__('separated by comma, ex : 3,16,129'),
        ));

        $fieldset->addType('price', 'Enterprise_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Form_Price');

        $fieldset->addField('min_purchase_value', 'price', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Minimum Purchase Value'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Minimum Purchase Value'),
            'name'      => 'min_purchase_value',
            'class'     => 'validate-number',
            'note'      => '<div id="balance_currency"><b>[IDR]</b></div>'
        ));

        $fieldset->addField('valid_from_date', 'date', array(
            'name'   => 'valid_from_date',
            'label'  => Mage::helper('enterprise_giftcardaccount')->__('Valid Purchase Date from'),
            'title'  => Mage::helper('enterprise_giftcardaccount')->__('Valid Purchase Date from'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        ));

        $fieldset->addField('valid_to_date', 'date', array(
            'name'   => 'valid_to_date',
            'label'  => Mage::helper('enterprise_giftcardaccount')->__('Valid Purchase Date to'),
            'title'  => Mage::helper('enterprise_giftcardaccount')->__('Valid Purchase Date to'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        ));

        $form->setValues($model->getData());

        $this->setForm($form);
        return $this;
    }

    public function getCurrencyJson()
    {
        $result = array();
        $websites = Mage::getSingleton('adminhtml/system_store')->getWebsiteCollection();
        foreach ($websites as $id=>$website) {
            $result[$id] = $website->getBaseCurrencyCode();
        }

        return Mage::helper('core')->jsonEncode($result);
    }
}