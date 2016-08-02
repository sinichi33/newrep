<?php

class Icube_Rma_Model_Mysql4_Images_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    
    public function _construct() {
        
        $this->_init('icube_rma/images');
    }
}