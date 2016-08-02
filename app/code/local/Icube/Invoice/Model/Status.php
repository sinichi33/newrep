<?php

class Icube_Invoice_Model_Status extends Mage_Core_Model_Abstract {
    
    protected function _construct() {
        
        parent::_construct();
        $this->_init('icube_invoice/status');
    }
}