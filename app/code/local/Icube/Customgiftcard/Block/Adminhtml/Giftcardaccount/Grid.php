<?php

class Icube_Customgiftcard_Block_Adminhtml_Giftcardaccount_Grid extends Enterprise_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Grid
{
 
    

    /**
     * Define grid columns
     */
    protected function _prepareColumns()
    {
        $this->addColumn('giftcardaccount_id',
            array(
                'header'=> Mage::helper('enterprise_giftcardaccount')->__('ID'),
                'width' => 30,
                'type'  => 'number',
                'index' => 'giftcardaccount_id',
        ));

        $this->addColumn('code',
            array(
                'header'=> Mage::helper('enterprise_giftcardaccount')->__('Code'),
                'index' => 'code',
        ));

        $this->addColumn('website',
            array(
                'header'    => Mage::helper('enterprise_giftcardaccount')->__('Website'),
                'width'     => 100,
                'index'     => 'website_id',
                'type'      => 'options',
                'options'   => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(),
        ));

        $this->addColumn('date_created',
            array(
                'header'=> Mage::helper('enterprise_giftcardaccount')->__('Date Created'),
                'width' => 120,
                'type'  => 'date',
                'index' => 'date_created',
        ));

        $this->addColumn('date_expires',
            array(
                'header'  => Mage::helper('enterprise_giftcardaccount')->__('Expiration Date'),
                'width'   => 120,
                'type'    => 'date',
                'index'   => 'date_expires',
                'default' => '--',
        ));

        $this->addColumn('status',
            array(
                'header'    => Mage::helper('enterprise_giftcardaccount')->__('Active'),
                'width'     => 50,
                'align'     => 'center',
                'index'     => 'status',
                'type'      => 'options',
                'options'   => array(
                    Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_ENABLED =>
                        Mage::helper('enterprise_giftcardaccount')->__('Yes'),
                    Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_DISABLED =>
                        Mage::helper('enterprise_giftcardaccount')->__('No'),
                ),
        ));

        $this->addColumn('state',
            array(
                'header'    => Mage::helper('enterprise_giftcardaccount')->__('Status'),
                'width'     => 100,
                'align'     => 'center',
                'index'     => 'state',
                'type'      => 'options',
                'options'   => Mage::getModel('enterprise_giftcardaccount/giftcardaccount')->getStatesAsOptionList(),
        ));

        $this->addColumn('balance',
            array(
                'header'        => Mage::helper('enterprise_giftcardaccount')->__('Balance'),
                'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),
                'type'          => 'number',
                'renderer'      => 'enterprise_giftcardaccount/adminhtml_widget_grid_column_renderer_currency',
                'index'         => 'balance',
        ));


        $this->addColumn('type',
            array(
                'header'    => Mage::helper('enterprise_giftcardaccount')->__('Type'),
                'width'     => 100,
                'align'     => 'center',
                'index'     => 'type',
                'type'      => 'options',
                'options'   => Mage::helper('customgiftcard')->typeOptions(),
        ));

        return parent::_prepareColumns();
    }

}
