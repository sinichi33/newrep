<?php

class Icube_Creditmemo_Model_History extends Mage_Core_Model_Abstract
{   
    protected function _construct() {
        
        parent::_construct();
        $this->_init('icube_creditmemo/history');
    }
}