<?php
/*
 * - Update Footer
 */
$installer = $this;
$installer->startSetup();

/* footer - Layanan Konsumen links */
$cmsBlock = Mage::getModel('cms/block')->load('footer-shopping_info_links', 'identifier');
$content =<<<EOF
<div class="block links">
    <div class="block-title">
        Layanan Konsumen
    </div>
    <div class="block-content">
        <ul>
            <li><a href="{{store url="customer-care"}}">Layanan Konsumen</a></li>
            <li><a href="{{store url="how-to-shop"}}">Cara Berbelanja</a></li>
            <li><a href="{{store url="confirmpayment"}}">Pembayaran</a></li>
            <li><a href="{{store url="shipping-delivery-pickupstore"}}">Pengiriman & Pengambilan Barang</a></li>
            <li><a href="{{store url="return/return/search"}}">Pengembalian</a></li>
            <li><a href="{{store url="rackorder/tracking/"}}">Status Pesanan</a></li>
            <li><a href="{{store url="faq/"}}">Pertanyaan</a></li>
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