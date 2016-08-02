<?php
/* based on task: http://teamwork.icubeonline.com/tasks/6366434
 * - Update About Us links
 * - Update Footer - Layanan Konsumen links
 */

$installer = $this;
$installer->startSetup();

/* footer - About Us links */
$cmsBlock = Mage::getModel('cms/block')->load('footer-about_us_links', 'identifier');
$content =<<<EOF
<div class="block links">
    <div class="block-title">
        Info Ruparupa
    </div>
    <div class="block-content">
        <ul>
            <li><a href="{{store url="about-us/"}}">Tentang Ruparupa</a></li>
            <li><a href="{{store url="privacy-policy/"}}">Kebijakan Privasi</a></li>
            <li><a href="{{store url="terms-conditions"}}">Syarat & Ketentuan</a></li>
            <li><a href="http://blog.ruparupa.com">Blog</a></li>
            <li><a href="{{store url="career/"}}">Info Karir</a></li>
        </ul>
    </div>
</div>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Footer - About Us Links')->setIdentifier('footer-about_us_links');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
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
            <li><a href="{{store url="confirmpayment"}}">Pembayaran</a></li>
            <li><a href="{{store url="shipping-delivery-pickupstore"}}">Pengiriman & Pengambilan Barang</a></li>
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

$installer->endSetup();
