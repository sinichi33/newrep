<?php
/*
 * - Banners for sister company landing pages
 */
$installer = $this;
$installer->startSetup();

/* Sister Company Banner - ACE */
$cmsBlock = Mage::getModel('cms/block')->load('company-banner-ace', 'identifier');
$content =<<<EOF
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-ace-1.png"}}" alt="Otomotif" style="margin-bottom:15px" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-ace-2.png"}}" alt="Rumah Tangga" style="margin-bottom:15px" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-ace-3.png"}}" alt="Frame" style="float:left;width:32%;" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-ace-4.png"}}" alt="Rumah Tangga" style="float:left;width:32%;margin-left:2%;" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-ace-5.png"}}" alt="Perlengkapan Rumah" style="float:left;width:32%;margin-left:2%;" /></a>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Company Banner - ACE')->setIdentifier('company-banner-ace');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* Sister Company Banner - Informa */
$cmsBlock = Mage::getModel('cms/block')->load('company-banner-informa', 'identifier');
$content =<<<EOF
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-informa-1.png"}}" alt="Bed & Bath" style="margin-bottom:15px" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-informa-2.png"}}" alt="Furniture" style="margin-bottom:15px" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-informa-3.png"}}" alt="Hobi & Gaya Hidup" style="float:left;width:32%;" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-informa-4.png"}}" alt="Rumah Tangga" style="float:left;width:32%;margin-left:2%;" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-informa-5.png"}}" alt="Perlengkapan Rumah" style="float:left;width:32%;margin-left:2%;" /></a>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Company Banner - Informa')->setIdentifier('company-banner-informa');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* Sister Company Banner - Toys */
$cmsBlock = Mage::getModel('cms/block')->load('company-banner-toys', 'identifier');
$content =<<<EOF
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-toys-1.png"}}" alt="Crossbow Challange" style="margin-bottom:15px" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-toys-2.png"}}" alt="Funtastic Sale" style="margin-bottom:15px" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-toys-3.png"}}" alt="Games" style="float:left;width:32%;" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-toys-4.png"}}" alt="Baby" style="float:left;width:32%;margin-left:2%;" /></a>
	<a href="#"><img src="{{skin url="images/sample/landing/company-banner-toys-5.png"}}" alt="Mainan Edukasi" style="float:left;width:32%;margin-left:2%;" /></a>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Company Banner - Toys Kingdom')->setIdentifier('company-banner-toys');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();