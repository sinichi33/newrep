<?php
class Icube_Invoice_Helper_Data extends Mage_Core_Helper_Abstract {
    
    /**
     * Collect list of shipping type
     *
     */
    public function shippingOptions()
    {
        return array(
                    'delivery'=> Mage::helper('customgiftcard')->__('Delivery'),
                    'pickup'=> Mage::helper('customgiftcard')->__('Pickup'),
                );
    }

}