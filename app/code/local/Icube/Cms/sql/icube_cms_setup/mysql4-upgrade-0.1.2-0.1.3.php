<?php
/*
 * - Homepage
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
		<h2>Flash Sale <span>50+ Products</span></h2>
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


/* Home - Banner */
$cmsBlock = Mage::getModel('cms/block')->load('home-banner', 'identifier');
$content =<<<EOF
<ul>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-banner.png"}}" alt="Sample Banner" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-banner.png"}}" alt="Sample Banner" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-banner.png"}}" alt="Sample Banner" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-banner.png"}}" alt="Sample Banner" /></a></li>
</ul>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - Banner Slider')->setIdentifier('home-banner');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* Home - Flash sale */
/* still dummy */
$cmsBlock = Mage::getModel('cms/block')->load('home-flash_sale', 'identifier');
$content =<<<EOF
<img src="{{skin url="images/sample/home/dummy-flashsale.png"}}" />
<div class="action">
	<a href="#" class="button btn-line-blue see-more">View More</a>
</div>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - Flash Sale')->setIdentifier('home-flash_sale');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* Home - Set of Inspiration */
$cmsBlock = Mage::getModel('cms/block')->load('home-set_of_inspiration', 'identifier');
$content =<<<EOF
<div class="set-of-inspiration">
	<div class="banner">
		<a href="{{store url=""}}inspirations/funky-kids-playroom.html"><img src={{skin url="images/sample/home/inspiration-funky_kids_playroom.png"}} alt="Funky kids Playroom" /></a>
	</div>
	{{block type="catalog/product_list" category_url="funky-kids-playroom" template="icube/homepage/set-of-inspiration.phtml"}}
</div>

<div class="set-of-inspiration">
	<div class="banner">
		<a href="{{store url=""}}inspirations/small-garden.html"><img src={{skin url="images/sample/home/inspiration-small_garden.png"}} alt="Small Garden" /></a>
	</div>
	{{block type="catalog/product_list" category_url="small-garden" template="icube/homepage/set-of-inspiration.phtml"}}
</div>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - Set of Inspiration')->setIdentifier('home-set_of_inspiration');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* Home - Featured Products */
$cmsBlock = Mage::getModel('cms/block')->load('home-featured_products', 'identifier');
$content =<<<EOF
{{block type="catalog/product_list" category_url="cool-things-for-your-home" template="icube/homepage/featured-products.phtml"}}
<div class="action">
	<a href="#" class="button btn-line-blue see-more">View More</a>
</div>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - featured_products')->setIdentifier('home-featured_products');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* Home - Company */
$cmsBlock = Mage::getModel('cms/block')->load('home-company', 'identifier');
$content =<<<EOF
<div class="company">
	<div class="banner">
		<a href="{{store url=""}}/company/ace.html"><img src={{skin url="images/sample/home/mobile-banner-ace.png"}} alt="ACE" /></a>
	</div>
	<div class="products">
		<img src={{skin url="images/sample/home/logo-ace.png"}} alt="ACE" class="company-logo" />
		{{block type="catalog/product_list" category_url="ace" template="icube/homepage/company-products.phtml"}}
	</div>
</div>

<div class="company">
	<div class="banner">
		<a href="{{store url=""}}/company/informa.html"><img src={{skin url="images/sample/home/banner-informa.png"}} alt="Informa" /></a>
	</div>
	<div class="products">
		<img src={{skin url="images/sample/home/logo-informa.png"}} alt="Informa" class="company-logo" />
		{{block type="catalog/product_list" category_url="informa" template="icube/homepage/company-products.phtml"}}
	</div>
</div>

<div class="company">
	<div class="banner">
		<a href="{{store url=""}}/company/toys-kingdom.html"><img src={{skin url="images/sample/home/banner-toyskingdom.png"}} alt="ACE" /></a>
	</div>
	<div class="products">
		<img src={{skin url="images/sample/home/logo-toyskingdom.png"}} alt="Toys Kingdom" class="company-logo" />
		{{block type="catalog/product_list" category_url="toys-kingdom" template="icube/homepage/company-products.phtml"}}
	</div>
</div>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - Company')->setIdentifier('home-company');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* Home - Brands */
$cmsBlock = Mage::getModel('cms/block')->load('home-brands', 'identifier');
$content =<<<EOF
<ul id="brands-slider">
<li>
	<ul>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	<li><img src="{{skin url="images/sample/home/brand-krisbrow.png"}}" alt="Krisbrow"</li>
	<li><img src="{{skin url="images/sample/home/brand-masterbuilt.png"}}" alt="Masterbuilt"</li>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	<li><img src="{{skin url="images/sample/home/brand-krisbrow.png"}}" alt="Krisbrow"</li>
	<li><img src="{{skin url="images/sample/home/brand-masterbuilt.png"}}" alt="Masterbuilt"</li>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	</ul>
</li>
<li>
	<ul>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	<li><img src="{{skin url="images/sample/home/brand-krisbrow.png"}}" alt="Krisbrow"</li>
	<li><img src="{{skin url="images/sample/home/brand-masterbuilt.png"}}" alt="Masterbuilt"</li>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	<li><img src="{{skin url="images/sample/home/brand-krisbrow.png"}}" alt="Krisbrow"</li>
	<li><img src="{{skin url="images/sample/home/brand-masterbuilt.png"}}" alt="Masterbuilt"</li>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	</ul>
</li>
<li>
	<ul>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	<li><img src="{{skin url="images/sample/home/brand-krisbrow.png"}}" alt="Krisbrow"</li>
	<li><img src="{{skin url="images/sample/home/brand-masterbuilt.png"}}" alt="Masterbuilt"</li>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	<li><img src="{{skin url="images/sample/home/brand-krisbrow.png"}}" alt="Krisbrow"</li>
	<li><img src="{{skin url="images/sample/home/brand-masterbuilt.png"}}" alt="Masterbuilt"</li>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	</ul>
</li>
<li>
	<ul>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	<li><img src="{{skin url="images/sample/home/brand-krisbrow.png"}}" alt="Krisbrow"</li>
	<li><img src="{{skin url="images/sample/home/brand-masterbuilt.png"}}" alt="Masterbuilt"</li>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	<li><img src="{{skin url="images/sample/home/brand-krisbrow.png"}}" alt="Krisbrow"</li>
	<li><img src="{{skin url="images/sample/home/brand-masterbuilt.png"}}" alt="Masterbuilt"</li>
	<li><img src="{{skin url="images/sample/home/brand-ferrara.png"}}" alt="Ferrara"</li>
	<li><img src="{{skin url="images/sample/home/brand-greenworks.png"}}" alt="Greenworks"</li>
	</ul>
</li>
</ul>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - Brands Slider')->setIdentifier('home-brands');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();