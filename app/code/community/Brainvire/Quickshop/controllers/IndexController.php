<?php
/** 

 * Quickshop block 

 * 

 * @category Brainvire 

 * @package Brainvire_Quickshop

* @copyright Copyright (c) 2015 Brainvire Infotech Pvt Ltd
 
 * 
 */ 
class Brainvire_Quickshop_IndexController extends Mage_Core_Controller_Front_Action
{
    protected function _initProduct()
    {
        Mage::dispatchEvent('catalog_controller_product_init_before', array('controller_action'=>$this));

        $categoryId = (int) $this->getRequest()->getParam('category', false);
			$productId  = (int) $this->getRequest()->getParam('id');
				
			$path  = (string) $this->getRequest()->getParam('path');
			$path	=	str_replace("_!_","/",$path);
			
			$path[0] == "\/" ? $path = substr($path, 1, strlen($path)) : $path;		
			$tableName = Mage::getSingleton('core/resource')->getTableName('enterprise_url_rewrite'); 
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			
			$parts = explode('/', $path);
			$suffix = array_pop($parts);
			if (false !== strrpos($suffix, '.')) {
				$suffix = substr($suffix, 0, strrpos($suffix, '.'));
			}
			$paths = array('request' => "'".$path."'", 'suffix' => "'".$suffix."'");
			if (count($parts)) {
				$paths['whole'] = "'".implode('/', $parts) . '/' . $suffix."'";
			}
			$strPath = implode(',',$paths);

		$query = "select 
				MAIN_TABLE.`value_id`,
				`catalog_url_key`.`entity_id` as 'product_id'
			from 
				`{$tableName}` as MAIN_TABLE 
			INNER JOIN 
				`catalog_product_entity_url_key` AS `catalog_url_key` 
					ON `catalog_url_key`.value_id = MAIN_TABLE.value_id
			where 
				MAIN_TABLE.`request_path` in({$strPath})
			";
			
			$readresult=$write->query($query);
			if ($row = $readresult->fetch() ) {
				$productId=$row['product_id'];
			}		
	

        if (!$productId) {
            return false;
        }

        $product = Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($productId);

        if (!Mage::helper('catalog/product')->canShow($product)) {
            return false;
        }
        if (!in_array(Mage::app()->getStore()->getWebsiteId(), $product->getWebsiteIds())) {
            return false;
        }

        $category = null;
        if ($categoryId) {
            $category = Mage::getModel('catalog/category')->load($categoryId);
            $product->setCategory($category);
            Mage::register('current_category', $category);
        }
        elseif ($categoryId = Mage::getSingleton('catalog/session')->getLastVisitedCategoryId()) {
            if ($product->canBeShowInCategory($categoryId)) {
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $product->setCategory($category);
                Mage::register('current_category', $category);
            }
        }


        Mage::register('current_product', $product);
        Mage::register('product', $product);

        try {
            Mage::dispatchEvent('catalog_controller_product_init', array('product'=>$product));
            Mage::dispatchEvent('catalog_controller_product_init_after', array('product'=>$product, 'controller_action' => $this));
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            return false;
        }

        return $product;
    }
    
    protected function _initProductLayout($product)
    {
        $update = $this->getLayout()->getUpdate();
        $update->addHandle('default');
        
		$this->addActionLayoutHandles();
        
        $update->addHandle('PRODUCT_TYPE_'.$product->getTypeId());
        $update->addHandle('PRODUCT_'.$product->getId());

        $this->loadLayoutUpdates();

        $update->addUpdate($product->getCustomLayoutUpdate());

		$update->merge(strtolower($this->getFullActionName()).'_FINAL');

        $this->generateLayoutXml()->generateLayoutBlocks();


        $currentCategory = Mage::registry('current_category');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('product-'.$product->getUrlKey());
            if ($currentCategory instanceof Mage_Catalog_Model_Category) {
                $root->addBodyClass('categorypath-'.$currentCategory->getUrlPath())
                    ->addBodyClass('category-'.$currentCategory->getUrlKey());
            }
        }
        return $this;
    }
    
   
    public function viewAction()
    {
        if ($product = $this->_initProduct()) {
            Mage::dispatchEvent('catalog_controller_product_view', array('product'=>$product));
            if ($this->getRequest()->getParam('options')) {
                $notice = $product->getTypeInstance(true)->getSpecifyOptionMessage();
                Mage::getSingleton('catalog/session')->addNotice($notice);
            }

            Mage::getSingleton('catalog/session')->setLastViewedProductId($product->getId());
            Mage::getModel('catalog/design')->applyDesign($product, Mage_Catalog_Model_Design::APPLY_FOR_PRODUCT);

            $this->_initProductLayout($product);
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('tag/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();
        }
        else {
            if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
                $this->_redirect('');
            } elseif (!$this->getResponse()->isRedirect()) {
                $this->_forward('noRoute');
            }
        }
    }

    public function galleryAction()
    {
        if (!$this->_initProduct()) {
            if (isset($_GET['store']) && !$this->getResponse()->isRedirect()) {
                $this->_redirect('');
            } elseif (!$this->getResponse()->isRedirect()) {
                $this->_forward('noRoute');
            }
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

 
    public function imageAction()
    {
        $size = (string) $this->getRequest()->getParam('size');
        if ($size) {
            $imageFile = preg_replace("#.*/catalog/product/image/size/[0-9]*x[0-9]*#", '', $this->getRequest()->getRequestUri());
        } else {
            $imageFile = preg_replace("#.*/catalog/product/image#", '', $this->getRequest()->getRequestUri());
        }

        if (!strstr($imageFile, '.')) {
            $this->_forward('noRoute');
            return;
        }

        try {
            $imageModel = Mage::getModel('catalog/product_image');
            $imageModel->setSize($size)
                ->setBaseFile($imageFile)
                ->resize()
                ->setWatermark( Mage::getStoreConfig('catalog/watermark/image') )
                ->saveFile()
                ->push();
        } catch( Exception $e ) {
            $this->_forward('noRoute');
        }
    }
}
