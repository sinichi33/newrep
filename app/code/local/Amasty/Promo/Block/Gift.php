<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */

    class Amasty_Promo_Block_Gift extends Amasty_Promo_Block_Banner
    {
        function getAttributeHeader()
        {
            return Mage::getStoreConfig('ampromo/gift_images/attribute_header');
        }

        function getAttributeDescription()
        {
            return Mage::getStoreConfig('ampromo/gift_images/attribute_description');
        }

        function getProducts()
        {
            $products = array();
            $validRule = $this->_getValidRule();
            $promoSku = $validRule->getPromoSku();
            if (!empty($promoSku)){
                $products = Mage::getResourceModel('catalog/product_collection')
                                ->addFieldToFilter('sku', array('in' => explode(",", $promoSku)))
                                ->addUrlRewrite()
                				->addAttributeToSelect(array('name', 'thumbnail', $this->getAttributeHeader(), $this->getAttributeDescription()));
            }

            return $products;
        }

        function getWidth()
        {
            return Mage::getStoreConfig('ampromo/gift_images/gift_image_width');
        }

        function getHeight()
        {
            return Mage::getStoreConfig('ampromo/gift_images/gift_image_height');
        }
    }
?>