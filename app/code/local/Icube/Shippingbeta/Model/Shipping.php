<?php 

class Icube_Shippingbeta_Model_Shipping extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {

	protected $_code = 'sap';
	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
		if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active')) {
			return false;
		}
		
		if ($request->getPackageWeight() == 0) {
			return false;
		}	
	
		$rate = Mage::getModel('shipping/rate_result_method');
		$rate->setCarrier($this->_code);
		$rate->setCarrierTitle($this->getConfigData('title'));
		
		$rate->setMethod('sap');
		$rate->setMethodTitle($this->getConfigData('method_name'));
		$rate->setPrice(0);
		$rate->setCost(0);
		
		$result = Mage::getModel('shipping/rate_result');
		$result->append($rate);			
		
		return $result;
		
	}
	
	public function isTrackingAvailable()
	{
		return true;
	}
	
	public function getAllowedMethods()
	{
		 return array('sap'=>$this->getConfigData('method_name'));
	}

}