<?php
/*
 * - Inspiration: bottom banners
 */
$installer = $this;
$installer->startSetup();


/* Home - Set of Inspiration */
$cmsBlock = Mage::getModel('cms/block')->load('inspiration-bottom_banners', 'identifier');
$content =<<<EOF
<ul>
<li><a href="#"><img src="{{skin url="images/sample/catalog/inspiration-small-funky_kids_playroom1.png"}}" title="Funky Kids Playroom" alt="Funky Kids Playroom" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/catalog/inspiration-small-funky_kids_playroom2.png"}}" title="Funky Kids Playroom" alt="Funky Kids Playroom" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/catalog/inspiration-small-funky_kids_playroom3.png"}}" title="Funky Kids Playroom" alt="Funky Kids Playroom" /></a></li>
</ul>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Inspiration - Bottom Banners')->setIdentifier('inspiration-bottom_banners');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();

$installer->endSetup();