<?php
/*
 * - Update Flash Sale
 */
$installer = $this;
$installer->startSetup();

/* Home - Flash sale */
$cmsBlock = Mage::getModel('cms/block')->load('home-flash_sale', 'identifier');
$content =<<<EOF
{{block type="catalog/product_list" category_url="flash-sale" template="icube/homepage/flash-sale.phtml"}}
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - Flash Sale')->setIdentifier('home-flash_sale');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();