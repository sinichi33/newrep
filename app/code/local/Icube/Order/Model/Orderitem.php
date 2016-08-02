<?php

class Icube_Order_Model_Orderitem extends Mage_Core_Model_Abstract 
{    
    protected function _construct() {
        
        parent::_construct();
        $this->_init('icube_order/orderitem');
    }
    
    public function updateDeliveryPickupStore($data)
    {
    	$resource = Mage::getSingleton('core/resource');
		$writeConnection = $resource->getConnection('core_write');
		$table = "sales_flat_quote_item";
		
		foreach ($data as $key => $value)
		{
			$query .= "UPDATE ".$table." SET delivery_pickup = '".$value['delivery']."' , store_code = '".$value['store']."'";
			$query .= ", company_id = '".$value['company']."'";
			$query .= ", pickup_location_code = '".$value['pickuplocation']."'";			
			$query .= " WHERE item_id = '".$key."';";
		}

		$writeConnection->query($query);
		
    }
}