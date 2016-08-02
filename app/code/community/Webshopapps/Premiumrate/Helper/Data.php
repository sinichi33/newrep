<?php
/**
 * Magento Webshopapps Shipping Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * Shipping MatrixRates
 *
 * @category   Webshopapps
 * @package    Webshopapps_Premiumrate
 * @copyright   Copyright (c) 2013 Zowta Ltd (http://www.WebShopApps.com)
 *              Copyright, 2013, Zowta, LLC - US license
 * @license    http://www.webshopapps.com/license/license.txt
 * @author     Karen Baker <sales@webshopapps.com>
*/

class Webshopapps_Premiumrate_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	
	public function processZipcode($readAdaptor, $customerPostcode,&$twoPhaseFiltering,
		&$zipString, &$shortMatchPostcode, &$longMatchPostcode ) {
			
        $debug = Mage::helper('wsalogger')->isDebug('Webshopapps_Premiumrate');
		//$zipRangeSet = Mage::getStoreConfig("carriers/premiumrate/zip_range"); //TODO sort out for backward compatability
		//$ukFiltering = Mage::getStoreConfig("carriers/premiumrate/uk_postcode"); //TODO sort out for backward compatability
        $postcodeFilter = Mage::getStoreConfig("carriers/premiumrate/postcode_filter");       
        Mage::helper('wsalogger/log')->postDebug('premiumrate','Postcode Filter',$postcodeFilter,$debug);	
        
		$customerPostcode = trim($customerPostcode);
		$twoPhaseFiltering = false;
		if ($postcodeFilter == 'numeric' && is_numeric($customerPostcode)) {			
			$zipString = ' AND '.$customerPostcode.' BETWEEN dest_zip AND dest_zip_to )';
			
		} else if ($postcodeFilter == 'uk' && strlen($customerPostcode)>4) {
			$twoPhaseFiltering = true;
			$longPostcode=substr_replace($customerPostcode,"",-3);
			$longMatchPostcode = trim($longPostcode);
			$shortMatchPostcode = preg_replace('/\d/','', $longMatchPostcode);
			$shortMatchPostcode = $readAdaptor->quoteInto(" AND STRCMP(LOWER(dest_zip),LOWER(?)) = 0)", $shortMatchPostcode);
		}  else if ($postcodeFilter == 'uk_numeric') {
			if(is_numeric($customerPostcode)){
				$zipString = ' AND '.$customerPostcode.' BETWEEN dest_zip AND dest_zip_to )';
			} else {
				$twoPhaseFiltering = true;
				$longPostcode=substr_replace($customerPostcode,"",-3);
				$longMatchPostcode = trim($longPostcode);
				$shortMatchPostcode = preg_replace('/\d/','', $longMatchPostcode);
				$shortMatchPostcode = $readAdaptor->quoteInto(" AND STRCMP(LOWER(dest_zip),LOWER(?)) = 0)", $shortMatchPostcode);
			}
		} else if ($postcodeFilter == 'canada') { 
			// first search complete postcode
			// then search exact match on first 3 chars
			// then search range
			$shortPart = substr($customerPostcode,0,3);
			if (strlen($shortPart) < 3 || !is_numeric($shortPart[1]) || !ctype_alpha($shortPart[2])) {
				$zipString = $readAdaptor->quoteInto(" AND ? LIKE dest_zip )", $customerPostcode);
			} else {
				$suffix = strtoupper($shortPart[2]);
				$zipFromRegExp='^'.$shortPart[0].'[0-'.$shortPart[1].'][A-'.$suffix.']$';
				$zipToRegExp='^'.$shortPart[0].'['.$shortPart[1].'-9]['.$suffix.'-Z]$';
				$shortMatchPostcode = $readAdaptor->quoteInto(" AND dest_zip REGEXP ?", $zipFromRegExp).$readAdaptor->quoteInto(" AND dest_zip_to REGEXP ? )",$zipToRegExp );
				$longMatchPostcode = $customerPostcode;
				$twoPhaseFiltering = true;
			}
		} else if ($postcodeFilter == 'can_numeric') { 
			if (is_numeric($customerPostcode)){
				$zipString = ' AND '.$customerPostcode.' BETWEEN dest_zip AND dest_zip_to )';
			} else {
				// first search complete postcode
				// then search exact match on first 3 chars
				// then search range
				$shortPart = substr($customerPostcode,0,3);
				if (strlen($shortPart) < 3 || !is_numeric($shortPart[1]) || !ctype_alpha($shortPart[2])) {
					$zipString = $readAdaptor->quoteInto(" AND ? LIKE dest_zip )", $customerPostcode);
				} else {
					$suffix = strtoupper($shortPart[2]);
					$zipFromRegExp='^'.$shortPart[0].'[0-'.$shortPart[1].'][A-'.$suffix.']$';
					$zipToRegExp='^'.$shortPart[0].'['.$shortPart[1].'-9]['.$suffix.'-Z]$';
					$shortMatchPostcode = $readAdaptor->quoteInto(" AND dest_zip REGEXP ?", $zipFromRegExp).$readAdaptor->quoteInto(" AND dest_zip_to REGEXP ? )",$zipToRegExp );
					$longMatchPostcode = $customerPostcode;
					$twoPhaseFiltering = true;
				}
			} 
		} else {
			 $zipString = $readAdaptor->quoteInto(" AND ? LIKE dest_zip )", $customerPostcode);
		}
		
		if ($debug) {
        	Mage::helper('wsalogger/log')->postDebug('premiumrate','Postcode Range Search String',$zipString);	
        	if ($twoPhaseFiltering) {
        		Mage::helper('wsalogger/log')->postDebug('premiumrate','Postcode 2 Phase Search String','short match:'.$shortMatchPostcode.
        			', long match:'.$longMatchPostcode);	
        	}
    	}
				
	}
	
	public function getShippingCost($cartItems, $shippingPrice)
	{
		$volTotal = 0;
        $grossWeight = 0;
        foreach ($cartItems as $item) {
            if($item->getDeliveryPickup() != 'pickup'){
                $product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
                switch (strtolower($product->getDimensionUom())) {
                	case 'cm':
                		$volTotal += $product->getDimensionHeight() * $product->getDimensionLength() * $product->getDimensionWidth() * $item->getQty();
                		break;
                	case 'm':
                		$volTotal += ($product->getDimensionHeight()*100) * ($product->getDimensionLength()*100) * ($product->getDimensionWidth()*100) * $item->getQty();
                		break;
                	case 'mm':
                		$volTotal += ($product->getDimensionHeight()/10) * ($product->getDimensionLength()/10) * ($product->getDimensionWidth()/10) * $item->getQty();
                		break;
                	default:
                		$volTotal += $product->getDimensionHeight() * $product->getDimensionLength() * $product->getDimensionWidth() * $item->getQty();
                		break;
                }
                $grossWeight += $item->getWeight();
            }
        }
        $x1 = max($grossWeight, ($volTotal/6000)) >= 0 ? max($grossWeight, ($volTotal/6000)) : 1; //exception for x1 = 0
        $x2 = ($x1 < 10) ? $x1*1.2 : $x1*1.1;
        $x3 = ceil($x2) * $shippingPrice;

        return $x3;
	}
}