<?php
/* 
 * - create another empty cms pages
 * - update cms nav links
 * - Create Menu nav Block on header
 */

$installer = $this;
$installer->startSetup();

/* CMS Nav update */
$cmsBlock = Mage::getModel('cms/block')->load('cms-navigation', 'identifier');

$content =<<<EOF
<div id="cms-nav">
<div class="block">
    <div class="block-content">
    <ul>
    <li><i class="flaticon-telemarketer1"></i><a href="{{store url="customer-care"}}">Layanan Konsumen</a></li>
    <li><i class="flaticon-cart3"></i><a href="{{store url="how-to-shop"}}">Cara Belanja</a></li>
    <li><i class="flaticon-money146"></i><a href="{{store url="payment"}}">Pembayaran</a></li>
    <li><i class="flaticon-shipping"></i><a href="{{store url="shipping-pickupstore"}}">Pengiriman & Pengambilan Barang</a></li>
    <li><i class="flaticon-money15"></i><a href="{{store url="return/return/search"}}">Pengembalian</a></li>
    <li><i class="flaticon-checked19"></i><a href="{{store url="trackorder/tracking"}}">Status Pesanan</a></li>
    <li><i class="flaticon-speech132"></i><a href="{{store url="faq"}}">Pertanyaan</a></li>
    </div>
</div>
</div>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setTitle('CMS Navigation');
}

$cmsBlock->setStores(array(0))
        ->setIdentifier('cms-navigation')
        ->setContentHeading('CMS Navigation')
        ->setContent($content)
        ->setIsActive(1)
        ->save();

/* Customer Care */
$cmsPage = Mage::getModel('cms/page')->load('customer-care', 'identifier');
$content =<<<EOF
<h1>Layanan Konsumen</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Layanan Konsumen')->setIdentifier('customer-care');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();


/* Create Menu nav Block on header */
$cmsBlock = Mage::getModel('cms/block')->load('header-menu', 'identifier');

$content =<<<EOF
<ul>
<li class="flashsale"><a href="#">Flash Sale</a></li>
<li class="storelocator"><a href="http://blog.ruparupa.com" target="_blank">Check Our Blog</a></li>
</ul>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setIdentifier('header-menu');
}

$cmsBlock->setStores(array(0))
        ->setTitle('Header - Menu')
        ->setContentHeading('Header - Menu')
        ->setContent($content)
        ->setIsActive(1)
        ->save();



$installer->endSetup();
