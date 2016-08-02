<?php
class Icube_Order_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getStorePOS()
    {
	    $stores = Mage::getModel('pointofsale/pointofsale')->getPlaces()
	    		->addFieldToSelect(array('store_code', 'name','company_id','pickup_location_code'))
	    		->getData();
				
		return $stores;
    }

    public function getStoreList($productId, $qty, $storeCode = null, $pickupLocationCode = null)
    {
    	$storeCode = ($storeCode) ?: array('neq' => 'DC') ;

	    $stocks = Mage::getModel('advancedinventory/stock')->getCollection()
	    	->addFieldToFilter('product_id', $productId)
	    	->addFieldToFilter('store.store_code', $storeCode)
	    	->addFieldToFilter('quantity_in_stock', array('gteq' => $qty));

	    if($pickupLocationCode) $stocks->addFieldToFilter('store.pickup_location_code', $pickupLocationCode);

	    $stocks->getSelect()
	    		->joinLeft(array('store' => 'pointofsale'), 'main_table.place_id = store.place_id', array('store.name' , 'main_table.quantity_in_stock' , 'store.store_code', 'store.company_id', 'store.pickup_location_code'))
	    		->group('main_table.id');
				
		return $stocks;
    }

	public function getStore($storeCode)
    {
	    $stores = Mage::getModel('pointofsale/pointofsale')->getPlaces()
	    		->addFieldToFilter('store_code', $storeCode)
	    		->getFirstItem();
				
		return $stores;
    }
    
    public function getStoreByPickupLocation($pickupCode, $companyId)
    {
	    $store = Mage::getModel('pointofsale/pointofsale')->getPlaces()
	    		->addFieldToFilter('pickup_location_code', $pickupCode)
	    		->addFieldToFilter('company_id', $companyId)
	    		->getFirstItem();
				
		return $store;
    }

	public function getInventoryList($productId, $qty)
	{
		$stocks = Mage::getModel('advancedinventory/stock')->getCollection()
			->addFieldToFilter('product_id', $productId)
			->addFieldToFilter('quantity_in_stock', array('gteq' => $qty))
		;

		$stocks->getSelect()
			->joinLeft(array('store' => 'pointofsale'), 'main_table.place_id = store.place_id', array('store.name' , 'main_table.quantity_in_stock' , 'store.store_code', 'store.company_id', 'store.pickup_location_code'))
			->group('main_table.id');

		return $stocks;
	}
    
}