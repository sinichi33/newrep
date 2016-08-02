<?php
/*
 * - Update Home Banner
 */
$installer = $this;
$installer->startSetup();

/* Update Home Banner */
$cmsBlock = Mage::getModel('cms/block')->load('home-banner', 'identifier');
$content =<<<EOF
<ul>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-banner-higher.png"}}" alt="Sample Banner" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-banner-higher.png"}}" alt="Sample Banner" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-banner-higher.png"}}" alt="Sample Banner" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-banner-higher.png"}}" alt="Sample Banner" /></a></li>
</ul>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - Banner Slider')->setIdentifier('home-banner');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();