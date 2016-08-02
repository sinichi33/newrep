<?php
 
class Icube_Customcheckout_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getAllDC()
	{
		$dcOnly = true;
		$quote = Mage::getSingleton('checkout/session')->getQuote();
		foreach($quote->getAllVisibleItems() as $item)
		{
			if($item->getStoreCode() != 'DC')
			{ $dcOnly = false; break; }
		}
		return $dcOnly;
	}
	
}