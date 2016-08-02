<?php
class Icube_RedeemPoint_Helper_Data extends Mage_Core_Helper_Abstract 
{
	public function getCompanyListName()
    {
        $company = array(
	        'AHI' => 'ACE',
	        'TGI' => 'TOYS KINGDOM',
	        'HCI' => 'INFORMA'
        );

        return $company;
    }	
}