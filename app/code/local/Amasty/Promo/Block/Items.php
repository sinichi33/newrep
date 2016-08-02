<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */
class Amasty_Promo_Block_Items extends Mage_Catalog_Block_Product_Abstract
{
    public function getFormActionUrl()
    {
        $returnUrl = Mage::getUrl('*/*/*', array('_use_rewrite' => true, '_current' => true));

        $params = array(
            Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => Mage::helper('core')->urlEncode($returnUrl)
        );

        return $this->getUrl('ampromo/cart/update', $params);
    }

    protected function _prepareLayout()
    {
        $products = Mage::helper('ampromo')->getNewItems();
        $this->setNewItems($products);
    }

    protected function _getReferer()
    {
        if ($this->getRequest()->isXmlHttpRequest()){
            $referer = $this->getRequest()->getServer('HTTP_REFERER');
        }
        else {
            $referer = Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true));
        }

        $referer = Mage::helper('core')->urlEncode($referer);

        return $referer;
    }

    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix = '')
    {
        if ($product->getAmpromoShowOrigPrice()){
            $html = parent::getPriceHtml($product, $displayMinimalPrice, $idSuffix);
            if ($product->getSpecialPrice() == 0){
                $html = str_replace('regular-price', 'old-price', $html);
            }
            return $html;
        }

        return "";
    }
}
