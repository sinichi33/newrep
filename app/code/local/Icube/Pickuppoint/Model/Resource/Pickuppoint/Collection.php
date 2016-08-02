<?php
 class Icube_Pickuppoint_Model_Resource_Pickuppoint_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
 {
     
     public function _construct()
     {
         parent::_construct();
         $this->_init('pickuppoint/pickuppoint');
     }
     
     
 }

?>