<?php

class Icube_Invoice_Model_Mysql4_Status extends Mage_Core_Model_Mysql4_Abstract {
    
    public function _construct() {
        
        $this->_init( 'icube_invoice/status', 'id');
    }

}