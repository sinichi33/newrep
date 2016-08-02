<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-06-15T14:15:07+02:00
 * File:          app/code/local/Xtento/OrderExport/Block/Adminhtml/Profile/Edit.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Block_Adminhtml_Profile_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'xtento_orderexport';
        $this->_controller = 'adminhtml_profile';

        if (Mage::registry('order_export_profile')->getId()) {
            $this->_addButton('duplicate_button', array(
                'label' => Mage::helper('xtento_orderexport')->__('Duplicate Profile'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/duplicate', array('_current' => true)) . '\')',
                'class' => 'add',
            ), 0);

            $this->_addButton('export_button', array(
                'label' => Mage::helper('xtento_orderexport')->__('Export Profile'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/orderexport_manual/index', array('profile_id' => Mage::registry('order_export_profile')->getId())) . '\')',
                'class' => 'go',
            ), 0);

            $this->_updateButton('save', 'label', Mage::helper('xtento_orderexport')->__('Save Profile'));
            $this->_updateButton('delete', 'label', Mage::helper('xtento_orderexport')->__('Delete Profile'));
            $this->_removeButton('reset');
        } else {
            $this->_removeButton('delete');
            $this->_removeButton('save');
        }

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('xtento_orderexport')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                if (editForm && editForm.validator.validate()) {
                    Element.show('loading-mask');
                    setLoaderPosition();
                    var tabsIdValue = profile_tabsJsTabs.activeTab.id;
                    var tabsBlockPrefix = 'profile_tabs_';
                    if (tabsIdValue.startsWith(tabsBlockPrefix)) {
                        tabsIdValue = tabsIdValue.substr(tabsBlockPrefix.length)
                    }
                }
                if (!$('edit_form').action.match(/\/key\//)) {
                    editForm.submit($('edit_form').action+'continue/edit/active_tab/'+tabsIdValue);
                } else {
                    editForm.submit($('edit_form').action.replace(/\/key\//, '/continue/edit/active_tab/'+tabsIdValue+'/key/')); // key must be last parameter
                }
            }
            varienGlobalEvents.attachEventHandler('formSubmit', function(){
                if (editForm && editForm.validator.validate()) {
                    Element.show('loading-mask');
                    setLoaderPosition();
                }
            });
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('order_export_profile')->getId()) {
            return Mage::helper('xtento_orderexport')->__('Edit %s Export Profile \'%s\'', Mage::helper('xtento_orderexport/entity')->getEntityName(Mage::registry('order_export_profile')->getEntity()), Mage::helper('xtcore/core')->escapeHtml(Mage::registry('order_export_profile')->getName()));
        } else {
            return Mage::helper('xtento_orderexport')->__('New Profile');
        }
    }

    protected function _toHtml()
    {
        return $this->getLayout()->createBlock('xtento_orderexport/adminhtml_widget_menu')->setShowWarning(1)->toHtml() . parent::_toHtml();
    }
}