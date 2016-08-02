<?php
/*
 * - Newsletter text
 */
$installer = $this;
$installer->startSetup();

/* Newsletter text */
$cmsBlock = Mage::getModel('cms/block')->load('newsletter-text', 'identifier');
$content =<<<EOF
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Newsletter - Text')->setIdentifier('newsletter-text');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();