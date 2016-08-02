<?php
/*
 * - Remove View More link from Featured Product Block on Homepage.
 */
$installer = $this;
$installer->startSetup();

/* Home - Featured Products */
$cmsBlock = Mage::getModel('cms/block')->load('home-featured_products', 'identifier');
$content =<<<EOF
{{block type="catalog/product_list" category_url="cool-things-for-your-home" template="icube/homepage/featured-products.phtml"}}
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - featured_products')->setIdentifier('home-featured_products');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();

$installer->endSetup();
