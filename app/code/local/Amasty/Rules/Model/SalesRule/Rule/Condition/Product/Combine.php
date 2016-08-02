<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Rules
 */
class Amasty_Rules_Model_SalesRule_Rule_Condition_Product_Combine extends Mage_SalesRule_Model_Rule_Condition_Product_Combine
{
    public function validate(Varien_Object $object)
    {
        // for optimization if we no conditions
        if (!$this->getConditions()) {
            return true;
        }

        //remember original product
        $origProduct = $object->getProduct();
        $origSku     = $object->getSku();

        $action = $this->getRule()->getSimpleAction();

        if ( strpos( $action , "buy_x_get_" ) !== false ){

            $promoSku = explode(",", $this->getRule()->getPromoSku() );
            $promoCats = explode(",",$this->getRule()->getPromoCats() );

            $itemSku = $object->getSku();
            $itemCats = $object->getCategoryIds();

            if (!$itemCats) $itemCats = $object->getProduct()->getCategoryIds();

            $parent = $object->getParentItem();

            if ($this->isConfigurablePromoItem($object,$promoSku)) return true;

            if ($parent) {
                $parentType = $parent->getProductType();
                if ($parentType == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
                    $itemSku = $object->getParentItem()->getProduct()->getSku();
                    $itemCats = $object->getParentItem()->getProduct()->getCategoryIds();
                }
            }

            if ( in_array( $itemSku,$promoSku )  ){
                return true;
            }

            if (  !is_null($itemCats)  && array_intersect( $promoCats, $itemCats ) ){
                return true;
            }

        }

        if ($object->getHasChildren() && $object->getProductType() == 'configurable'){
            foreach ($object->getChildren() as $child) { 
                // only one itereation.
                $categoryIds = array_merge($child->getProduct()->getCategoryIds(),$origProduct->getCategoryIds());
                $categoryIds = array_unique($categoryIds);
                $object->setProduct($child->getProduct());
                $object->setSku($child->getSku());
                $object->getProduct()->setCategoryIds($categoryIds);
            }
        }
        $result = @Mage_Rule_Model_Condition_Combine::validate($object);
        if ($origProduct){
            // restore original product
            $object->setProduct($origProduct);    
            $object->setSku($origSku);    
        }        

        return $result;       
    }

    public function isConfigurablePromoItem($object ,$promoSku ){
        $productType  = $object->getProductType();

        if ($productType==Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE ){
            foreach( $promoSku as $sku ){
                $clearSku = str_replace('-amconfigurable','',$sku);
                if ( strpos($sku,'-amconfigurable')!==false && strpos( $object->getProduct()->getSku() , $clearSku )!==false ) return true;
            }
        }
        return false;
    }

}
