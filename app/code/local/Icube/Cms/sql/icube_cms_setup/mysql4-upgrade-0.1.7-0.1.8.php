<?php
/*
 * - Flash Sale Banner
 */
$installer = $this;
$installer->startSetup();

/* Flash Sale Banner */
$cmsBlock = Mage::getModel('cms/block')->load('banner-flashsale-sidebar', 'identifier');
$content =<<<EOF
<a href="#" title="Flash Sale"><img src="{{skin url="images/sample/catalog/banner-flashsale-sidebar.png"}}" alt="Flash Sale" /></a>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Banner - Flashsale Sidebar')->setIdentifier('banner-flashsale-sidebar');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();