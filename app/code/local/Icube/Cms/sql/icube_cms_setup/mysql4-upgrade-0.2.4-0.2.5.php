<?php
/**
 * Author: iCube
 *
 * Description:
 * - Update Order Status link on footer
 */
?>
<?php
$installer = $this;
$installer->startSetup();

/* footer - Shipping Info links */
$cmsBlock = Mage::getModel('cms/block')->load('footer-shopping_info_links', 'identifier');
$content =<<<EOF
<div class="block links">
    <div class="block-title">
        Layanan Konsumen
    </div>
    <div class="block-content">
        <ul>
            <li><a href="{{store url="customer-care/"}}">Layanan Konsumen</a></li>
            <li><a href="{{store url="how-to-shop/"}}">Cara Berbelanja</a></li>
            <li><a href="{{store url="payment/"}}">Pembayaran</a></li>
            <li><a href="{{store url="shipping-delivery-pickupstore/"}}">Pengiriman & Pengambilan Barang</a></li>
            <li><a href="{{store url="refund/"}}">Pengembalian</a></li>
            <li><a href="{{store url="trackorder/tracking/"}}">Status Pesanan</a></li>
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
