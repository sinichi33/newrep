<?php
/*
 * - Payment Bank Transfer Account List
 * - CMS Nav update
 */
$installer = $this;
$installer->startSetup();

/* Bank Transfer Account List */
$cmsBlock = Mage::getModel('cms/block')->load('rma-upload-image-instruction', 'identifier');

$content =<<<EOF
<h4>UNGGAH FOTO PRODUK YANG INGIN ANDA KEMBALIKAN MELALUI EKSPEDISI</h4>
<p>
Petunjuk foto barang yang ingin dikembalikan :
<ol type="1">
<li>Produk rusak : Foto kemasan dan bagian produk yang rusak</li>
<li>Produk tidak sesuai deskripsi : Foto kemasan dan foto produk dari sisi depan, samping dan belakang</li>
<li>Bagian produk ada yang hilang : Foto kemasan dan foto produk dari sisi depan, samping dan belakang</li>
<li>Salah kirim : Foto kemasan produk</li>
<li>Berubah pikiran : Foto kemasan produk.</li>
</ol>
</p>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setTitle('RMA - Upload Image Instruction');
}

$cmsBlock->setStores(array(0))
        ->setIdentifier('rma-upload-image-instruction')
        ->setContentHeading('RMA - Upload Image Instruction')
        ->setContent($content)
        ->setIsActive(1)
        ->save();


/* CMS Nav update */
$cmsBlock = Mage::getModel('cms/block')->load('cms-navigation', 'identifier');

$content =<<<EOF
<div id="cms-nav">
<div class="block">
    <div class="block-content">
    <ul>
    <li><i class="flaticon-telemarketer1"></i><a href="{{store url="customer-care"}}">Layanan Konsumen</a></li>
    <li><i class="flaticon-cart3"></i><a href="{{store url="how-to-shop"}}">Cara Belanja</a></li>
    <li><i class="flaticon-money146"></i><a href="{{store url="confirmpayment"}}">Pembayaran</a></li>
    <li><i class="flaticon-shipping"></i><a href="{{store url="shipping-delivery-pickupstore"}}">Pengiriman & Pengambilan Barang</a></li>
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


$installer->endSetup();