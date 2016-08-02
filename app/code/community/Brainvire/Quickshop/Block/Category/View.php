<?php
/** 

 * Quickshop block 

 * 

 * @category Brainvire 

 * @package Brainvire_Quickshop

* @copyright Copyright (c) 2015 Brainvire Infotech Pvt Ltd
 
 * 
 */
class Brainvire_Quickshop_Block_Category_View extends Mage_Catalog_Block_Category_View
{
    public function getProductListHtml()
    {
        return $this->getChildHtml('product_list');
    }
}