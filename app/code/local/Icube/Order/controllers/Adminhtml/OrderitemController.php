<?php

class Icube_Order_Adminhtml_OrderitemController extends Mage_Adminhtml_Controller_Action
{
	public function updateDeliveryPickupStoreAjaxAction()
    {
	    $post = $this->getRequest()->getPost();
	    $data = Mage::helper('core')->jsonDecode($post['data']);
		
		Mage::getModel('icube_order/orderitem')->updateDeliveryPickupStore($data);
    }
}