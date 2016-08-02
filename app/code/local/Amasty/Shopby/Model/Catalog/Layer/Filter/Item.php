<?php
/**
 * @copyright   Copyright (c) 2010 Amasty (http://www.amasty.com)
 */ 
class Amasty_Shopby_Model_Catalog_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
    public function getUrl()
    {
        Varien_Profiler::start('amshopby_filter_item_url');

        /** @var Amasty_Shopby_Model_Url_Builder $urlBuilder */
        $urlBuilder = Mage::getModel('amshopby/url_builder');
        $urlBuilder->reset();
        $urlBuilder->clearPagination();

        if ($this->getFilter()->getRequestVar() == 'cat') {
            $cat = Mage::getModel('catalog/category')->load($this->getValue());
            $urlBuilder->category = $cat;
        } else {
            $urlBuilder->changeQuery(array(
                $this->getFilter()->getRequestVar() => $this->getValue(),
            ));
        }

        $url = $urlBuilder->getUrl();

        Varien_Profiler::stop('amshopby_filter_item_url');
        return $url;
    }
    
    
    public function getRemoveUrl()
    {
        Varien_Profiler::start('amshopby_filter_item_url');

        /** @var Amasty_Shopby_Model_Url_Builder $urlBuilder */
        $urlBuilder = Mage::getModel('amshopby/url_builder');
        $urlBuilder->reset();
        $urlBuilder->clearPagination();

        if ($this->getFilter()->getRequestVar() == 'cat') {
            /** @var Mage_Catalog_Model_Category $cat */
            $cat = Mage::getModel('catalog/category')->load($this->getValue());
            $urlBuilder->category = $cat->getParentCategory();
        } else {
            $urlBuilder->changeQuery(array(
                $this->getFilter()->getRequestVar() => $this->getFilter()->getResetValue(),
            ));
        }

        $url = $urlBuilder->getUrl();

        Varien_Profiler::stop('amshopby_filter_item_url');
        return $url;
    }

}