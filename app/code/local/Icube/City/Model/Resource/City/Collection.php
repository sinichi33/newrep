<?php
 class Icube_City_Model_Resource_City_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
 {
     
     public function _construct()
     {
         parent::_construct();
         $this->_init('city/city');
     }
     
     
 }

?>