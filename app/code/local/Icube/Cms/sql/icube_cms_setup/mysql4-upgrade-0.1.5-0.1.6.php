<?php
/*
 * - Catalog Landing Block
 * - Flash Sale Banner
 */
$installer = $this;
$installer->startSetup();

/* Catalog Landing Block */
$cmsBlock = Mage::getModel('cms/block')->load('catalog-landing', 'identifier');
$content =<<<EOF
{{block type="core/template" template="icube/catalog/landing.phtml"}}
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Catalog - Landing')->setIdentifier('catalog-landing');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* Flash Sale Banner */
$cmsBlock = Mage::getModel('cms/block')->load('banner-flashsale-full', 'identifier');
$content =<<<EOF
<a href="#" title="Flash Sale"><img src="{{skin url="images/sample/catalog/banner-flashsale-full.png"}}" alt="Flash Sale" /></a>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Banner - Flashsale')->setIdentifier('banner-flashsale-full');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();