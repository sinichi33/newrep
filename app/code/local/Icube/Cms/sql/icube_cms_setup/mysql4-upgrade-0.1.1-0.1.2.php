<?php
/*
 * - Announcement block
 */
$installer = $this;
$installer->startSetup();


/* Header - Announcement block*/
$cmsBlock = Mage::getModel('cms/block')->load('header-announcement', 'identifier');
$content =<<<EOF
<ul>
    <li>FREE DELIVERY on orders Rp 300.000,-</li>
    <li>RETURNS & EXCHANGES 350+ stores</li>
    <li>STORE PICK UP</li>
</ul>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Header - Announcement')->setIdentifier('header-announcement');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();