<?php
/* 
 * - create empty cms pages
 * - update footer links
 */

$installer = $this;
$installer->startSetup();

/* About Us */
$cmsPage = Mage::getModel('cms/page')->load('about-us', 'identifier');
$content =<<<EOF
<h1>Tentang Ruparupa</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<h2>Lorem Ipsum</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<ul>
<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
</ul>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
<ol>
<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit</li>
</ol>
<h3>Lorem Ipsum</h3>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Tentang Ruparupa')->setIdentifier('about-us');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();

/* Privacy Policy */
$cmsPage = Mage::getModel('cms/page')->load('privacy-policy', 'identifier');
$content =<<<EOF
<h1>Kebijakan Privasi</h1>
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
    $cmsPage->setTitle('Kebijakan Privasi')->setIdentifier('privacy-policy');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();

/* Terms & Confitions */
$cmsPage = Mage::getModel('cms/page')->load('terms-conditions', 'identifier');
$content =<<<EOF
<h1>Syarat & Ketentuan</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Syarat & Ketentuan')->setIdentifier('terms-conditions');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();


/* Career */
$cmsPage = Mage::getModel('cms/page')->load('career', 'identifier');
$content =<<<EOF
<h1>Info Karir</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Info Karir')->setIdentifier('career');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();


/* faq */
$cmsPage = Mage::getModel('cms/page')->load('faq', 'identifier');
$content =<<<EOF
<h1>FAQ</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('FAQ')->setIdentifier('faq');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();

/* Cara Berbelanja */
$cmsPage = Mage::getModel('cms/page')->load('how-to-shop', 'identifier');
$content =<<<EOF
<h1>Cara Berbelanja</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Cara Berbelanja')->setIdentifier('how-to-shop');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();

/* Pembayaran */
$cmsPage = Mage::getModel('cms/page')->load('payment', 'identifier');
$content =<<<EOF
<h1>Pembayaran</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Pembayaran')->setIdentifier('payment');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();

/* Pengiriman & Pengambilan Barang */
$cmsPage = Mage::getModel('cms/page')->load('shipping-pickupstore', 'identifier');
$content =<<<EOF
<h1>Pengiriman & Pengambilan Barang</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Pengiriman & Pengambilan Barang')->setIdentifier('shipping-pickupstore');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save();


/* footer - Layanan Konsumen links */
$cmsBlock = Mage::getModel('cms/block')->load('footer-shopping_info_links', 'identifier');
$content =<<<EOF
<div class="block links">
    <div class="block-title">
        Layanan Konsumen
    </div>
    <div class="block-content">
        <ul>
            <li><a href="{{store url="faq"}}">FAQ</a></li>
            <li><a href="{{store url="how-to-shop"}}">Cara Berbelanja</a></li>
            <li><a href="{{store url="payment"}}">Pembayaran</a></li>
            <li><a href="{{store url="shipping-pickupstore"}}">Pengiriman & Pengambilan Barang</a></li>
            <li><a href="{{store url="return/return/search"}}">Pengembalian</a></li>
        </ul>
    </div>
</div>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setTitle('Footer - Shopping Info Links')->setIdentifier('footer-shopping_info_links');
}

$cmsBlock->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->save();

/* remove default mage pages */
$cmsPage = Mage::getModel('cms/page')->load('about-magento-demo-store', 'identifier');
if($cmsPage->getId()){
    $cmsPage->delete();
}
$cmsPage = Mage::getModel('cms/page')->load('private-sales', 'identifier');
if($cmsPage->getId()){
    $cmsPage->delete();
}
$cmsPage = Mage::getModel('cms/page')->load('rma-order-search', 'identifier');
if($cmsPage->getId()){
    $cmsPage->delete();
}


$installer->endSetup();
