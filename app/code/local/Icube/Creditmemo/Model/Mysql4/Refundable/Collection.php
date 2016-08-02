<?php

class Icube_Creditmemo_Model_Mysql4_Refundable_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{   
    public function _construct()
    {    
        $this->_init('icube_creditmemo/refundable');
    }
}