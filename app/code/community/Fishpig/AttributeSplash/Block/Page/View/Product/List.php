<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Page_View_Product_List extends Mage_Catalog_Block_Product_List
{
	/**
	 * Retrieves the current layer product collection
	 *
	 * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
	 */
	protected function _getProductCollection()
	{
		if (is_null($this->_productCollection)) {
			$splash_id = Mage::getSingleton('attributeSplash/layer')->getSplashId();
			// Mage::log($splash_id, null, 'myVtr.log', true);

			$bu_splash = 0;
			if($splash_id == '1'){
				$category_id = 2989; // ace feature product category
				$bu_splash = 1;
			}elseif($splash_id == '2'){
				$category_id = 2990; // informa feature product category
				$bu_splash = 1;
			}elseif($splash_id == '3'){
				$category_id = 2991; // toys feature product category
				$bu_splash = 1;
			}

			// disabled, because some bug happen, next i will try to fix this
			$bu_splash = 0;

			if($bu_splash) {
				$catagory_model = Mage::getModel('catalog/category')->load($category_id);
				$collection = Mage::getResourceModel('catalog/product_collection');
				$collection->addCategoryFilter($catagory_model); //category filter by category id
				$collection->addAttributeToFilter('status', 1); //only enabled product
				$collection->addAttributeToSelect(array('name', 'url', 'small_image'));
				$collection->addAttributeToSort('position', 'asc');
				$collection->addStoreFilter();
			}

			$this->_productCollection = Mage::getSingleton('attributeSplash/layer')->getProductCollection();
			if ($orders = Mage::getSingleton('catalog/config')->getAttributeUsedForSortByArray()) {
				if (isset($orders['position'])) {
					unset($orders['position']);
				}

				$this->setAvailableOrders($orders);

				if (!$this->getSortBy()) {
					$category = Mage::getModel('catalog/category')->setStoreId(
						Mage::app()->getStore()->getId()
					);

					$this->setSortBy($category->getDefaultSortBy());
				}
			}

			if($bu_splash) {
				if (!empty($collection)) {
					$exclude_arr = array();
					foreach ($collection as $col) {
						$exclude_arr[] = $col->getId();
					}

					$this->_productCollection = Mage::getSingleton('attributeSplash/layer')->getProductCollectionFilter($exclude_arr);

					foreach ($collection as $col) {
						$this->_productCollection->addItem($col);
					}

					if ($orders = Mage::getSingleton('catalog/config')->getAttributeUsedForSortByArray()) {
						if (isset($orders['position'])) {
							unset($orders['position']);
						}

						$this->setAvailableOrders($orders);

						if (!$this->getSortBy()) {
							$category = Mage::getModel('catalog/category')->setStoreId(
								Mage::app()->getStore()->getId()
							);

							$this->setSortBy($category->getDefaultSortBy());
						}
					}
				}
			}
		}

		return $this->_productCollection;
	}
}
