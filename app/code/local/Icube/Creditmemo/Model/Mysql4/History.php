<?php

class Icube_Creditmemo_Model_Mysql4_History extends Mage_Core_Model_Mysql4_Abstract
{  
    public function _construct()
    {        
        $this->_init( 'icube_creditmemo/history', 'id');
    }

}