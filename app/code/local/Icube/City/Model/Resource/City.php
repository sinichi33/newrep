<?php 
class Icube_City_Model_Resource_City extends Mage_Core_Model_Mysql4_Abstract
{
    
	public function _construct()
	{    
	    $this->_init('city/city','id');
	}   
    
}
