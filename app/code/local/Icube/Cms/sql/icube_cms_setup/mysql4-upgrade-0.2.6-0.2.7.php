<?php
/*
 * - CMS Nav update
 * - RMA order search form
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
    <li><i class="flaticon-shipping"></i><a href="{{store url="shipping-delivery-pickupstore"}}">Pengiriman & Pengambilan Barang</a></li>
    <li><i class="flaticon-money15"></i><a href="{{store url="rma-order-search"}}">Pengembalian</a></li>
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


/* RMA order search form */
$cmsPage = Mage::getModel('cms/page')->load('rma-order-search', 'identifier');
$content =<<<EOF
{{block type="core/template" template="icube/rma/order-search.phtml"}}
EOF;

if(!$cmsPage->getId()){
	$cmsPage->setIdentifier('rma-order-search');
}

$layoutUpdateXml = <<<EOT
<reference name="left">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>cms-navigation</block_id></action>
    </block> 
</reference>
EOT;

$cmsPage->setStores(array(0))
        ->setTitle('RMA - Order Search Form')
        ->setContentHeading('Pengembalian')
        ->setContent($content)
        ->setIsActive(1)
        ->setCustomLayoutUpdateXml($layoutUpdateXml)
        ->setRootTemplate('two_columns_left')
        ->save();


$installer->endSetup();