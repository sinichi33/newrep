<?php
/*
 * - Update Homepage: remove title of flash sale
 */
$installer = $this;
$installer->startSetup();


/* Home page */
$cmsPage = Mage::getModel('cms/page')->load('home', 'identifier');
$content =<<<EOF
<div id="section-banner-slider">
	{{block type="cms/block" block_id="home-banner"}}
</div>
<div class="home-content-wrapper">
	<div id="section-flashsale">
		{{block type="cms/block" block_id="home-flash_sale"}}
	</div>
	<div id="section-inspirations">
		{{block type="cms/block" block_id="home-set_of_inspiration"}}
	</div>
	<div id="section-featured_products">
		<h2>Cool Things For Your Home</h2>
		{{block type="cms/block" block_id="home-featured_products"}}
	</div>
	<div id="section-company">
		{{block type="cms/block" block_id="home-company"}}
	</div>
	<div id="section-brands">
		<h3>Discover More of Our Amazing Brands</h3>
		{{block type="cms/block" block_id="home-brands"}}
	</div>
</div>
EOF;

if(!$cmsPage->getId()){
	$cmsPage->setTitle('Home Page')->setIdentifier('home');
}

$cmsPage->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
        ->setRootTemplate('one_column')
		->save();

$installer->endSetup();
