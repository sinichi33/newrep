<?php 

class Icube_Order_Model_Observer 
{

    public function removeEditButton($event)
    {
      	$block = $event->getBlock();

	    if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View) {
	        $block->removeButton('order_edit');
	    }
    }


    /* 
    * check if the assignation site for each item have stock
    */
    public function validateItemStock(Varien_Event_Observer $observer)
    {

    	$cart = Mage::getModel('checkout/cart')->getQuote();

    	$valid = true;
    	foreach ($cart->getAllItems() as $item) {
		
            $checkStores = Mage::helper('icube_order')->getStoreList($item->getProductId(),$item->getQty(), $item->getStoreCode())->getData();

			if(!$checkStores) {
	            Mage::getSingleton('checkout/session')->addError(sprintf('Item  "%s" is currently not available at your selected store, please choose another store for this item.', Mage::helper('core')->escapeHtml($item->getName())));

	            $valid = false;
	        }

        }

        // redirect to cart page
        if(!$valid) Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
        
        return $this;
    }

}