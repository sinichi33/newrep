<?php 
class Icube_Pickuppoint_Model_Resource_Pickuppoint extends Mage_Core_Model_Mysql4_Abstract
{
    
	public function _construct()
	{    
	    $this->_init('pickuppoint/pickuppoint','pickup_id');
	}   
    
}
