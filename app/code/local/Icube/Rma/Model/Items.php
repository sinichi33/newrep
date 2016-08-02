<?php

class Icube_Rma_Model_Items extends Mage_Core_Model_Abstract {
    
    protected function _construct() {
        
        parent::_construct();
        $this->_init('icube_rma/items');
    }
}