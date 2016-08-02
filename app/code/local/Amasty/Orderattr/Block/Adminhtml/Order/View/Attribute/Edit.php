<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Adminhtml_Order_View_Attribute_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'amorderattr';
        $this->_objectId   = 'entity_id';
        $this->_controller = 'adminhtml_order_view_attribute';

        parent::__construct();

        $backUrl = $this->getUrl('adminhtml/sales_order/view', array('order_id' => Mage::app()->getRequest()->getParam('order_id')));
        $this->_updateButton('back', 'onclick', "setLocation('{$backUrl}')");
        $this->_updateButton('save', 'label', Mage::helper('catalog')->__('Save Order Attributes'));
        $this->_removeButton('delete');
        $this->_removeButton('reset');
    }

    public function getHeaderText()
    {
    	$order = Mage::registry('current_order');
        return Mage::helper('catalog')->__('Edit Attributes For The Order #%s', $order->getIncrementId());
    }

    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('_current'=>true, 'back'=>null));
    }
}
