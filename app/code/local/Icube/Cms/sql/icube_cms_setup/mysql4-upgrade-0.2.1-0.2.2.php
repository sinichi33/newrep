<?php
/*
 * - Update Home Banner
 * - Update Header nnouncement Block
 * - Update Footer
 */
$installer = $this;
$installer->startSetup();

/* Update Home Banner */
$cmsBlock = Mage::getModel('cms/block')->load('home-banner', 'identifier');
$content =<<<EOF
<ul>
<li style="background:#E2DCD0;"><a href="#"><img src="{{skin url="images/sample/home/sample-banner-1.png"}}" alt="Sample Banner" /></a></li>
<li style="background:-webkit-linear-gradient(#F4F4F4,#f5f5f5, #FEFEFE);background:-o-linear-gradient(#F4F4F4, #f5f5f5, #FEFEFE);background:-moz-linear-gradient(#F4F4F4, #f5f5f5, #FEFEFE);background:linear-gradient(#F4F4F4, #f5f5f5, #FEFEFE);"><a href="#"><img src="{{skin url="images/sample/home/sample-banner-2.png"}}" alt="Sample Banner" /></a></li>
</ul>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Home - Banner Slider')->setIdentifier('home-banner');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();

/* Header - Announcement block*/
$cmsBlock = Mage::getModel('cms/block')->load('header-announcement', 'identifier');
$content =<<<EOF
<ul>
    <li><a href="#">GRATIS PENGIRIMAN tiap belanja Rp 300.000,-</a></li>
    <li><a href="#">JAMINAN PENGEMBALIAN DAN PENUKARAN BARANG</a></li>
    <li><a href="{{store url="pickup-store/"}}">AMBIL DI TOKO</a></li>
</ul>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Header - Announcement')->setIdentifier('header-announcement');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();

/* footer - About Us links*/
$cmsBlock = Mage::getModel('cms/block')->load('footer-about_us_links', 'identifier');
$content =<<<EOF
<div class="block links">
    <div class="block-title">
        Info Ruparupa
    </div>
    <div class="block-content">
        <ul>
            <li><a href="{{store url="about-us/"}}">Tentang Ruparupa</a></li>
            <li><a href="{{store url="pickup-store/"}}">Ambil di Toko</a></li>
            <li><a href="{{store url="privacy-policy/"}}">Kebijakan Privasi</a></li>
            <li><a href="{{store url="terms-conditions"}}">Syarat & Ketentuan</a></li>
            <li><a href="{{store url="blog/"}}">Blog</a></li>
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
            <li><a href="{{store url="order-status/"}}">Status Pesanan</a></li>
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

/* footer - Cicilan list*/
$cmsBlock = Mage::getModel('cms/block')->load('footer-cicilan_list', 'identifier');
$content =<<<EOF
<div class="block links logo-grid">
    <div class="block-title">
        Cicilan 0%
    </div>
    <div class="block-content">
        <ul>
            <li><img src="{{skin url="images/sample/footer/anz.png"}}" alt="ANZ" /></li>
            <li><img src="{{skin url="images/sample/footer/bca.png"}}" alt="BCA" /></li>
            <li><img src="{{skin url="images/sample/footer/bri.png"}}" alt="Bank BRI" /></li>
            <li><img src="{{skin url="images/sample/footer/hsbc.png"}}" alt="HSBC" /></li>
            <li><img src="{{skin url="images/sample/footer/mandiri.png"}}" alt="Mandiri" /></li>
            <li><img src="{{skin url="images/sample/footer/panin_bank.png"}}" alt="Panin Bank" /></li>
            <li><img src="{{skin url="images/sample/footer/uob.png"}}" alt="UOB" /></li>
            <li><img src="{{skin url="images/sample/footer/permata_bank.png"}}" alt="Permata Bank" /></li>
            <li><img src="{{skin url="images/sample/footer/standard_chartered.png"}}" alt="Standard Chartered" /></li>
            <li><img src="{{skin url="images/sample/footer/danamon.png"}}" alt="Danamon" /></li>
            <li><img src="{{skin url="images/sample/footer/ocbc.png"}}" alt="OCBC" /></li>
            <li><img src="{{skin url="images/sample/footer/citi.png"}}" alt="Citi" /></li>
        </ul>
    </div>
</div>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Footer - Cicilan 0% List')->setIdentifier('footer-cicilan_list');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* footer - Metode Pembayaran List */
$cmsBlock = Mage::getModel('cms/block')->load('footer-metode_pembayaran_list', 'identifier');
$content =<<<EOF
<div class="block links logo-grid">
    <div class="block-title">
        Metode Pembayaran
    </div>
    <div class="block-content">
        <ul>
            <li><img src="{{skin url="images/sample/footer/klik_bca.png"}}" alt="Klik BCA" /></li>
            <li><img src="{{skin url="images/sample/footer/bca_klik_pay.png"}}" alt="BCA Klik Pay" /></li>
            <li><img src="{{skin url="images/sample/footer/debit_btn_online.png"}}" alt="Debit BTN Online" /></li>
            <li><img src="{{skin url="images/sample/footer/cimb_clicks.png"}}" alt="CIMB Clicks" /></li>
            <li><img src="{{skin url="images/sample/footer/mandiri_click_pay.png"}}" alt="Mandiri Click Pay" /></li>
            <li><img src="{{skin url="images/sample/footer/bni_debit_online.png"}}" alt="BNI Debit Online" /></li>
            <li><img src="{{skin url="images/sample/footer/mastercard.png"}}" alt="Master Card" /></li>
            <li><img src="{{skin url="images/sample/footer/visa.png"}}" alt="Visa" /></li>
            <li><img src="{{skin url="images/sample/footer/xl_tunai.png"}}" alt="XL Tunai" /></li>
            <li><img src="{{skin url="images/sample/footer/epay_bri.png"}}" alt="e-Pay BRI" /></li>
            <li><img src="{{skin url="images/sample/footer/debit_mandiri.png"}}" alt="Debit Mandiri" /></li>
            <li><img src="{{skin url="images/sample/footer/u.png"}}" alt="U" /></li>
        </ul>
    </div>
</div>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Footer - Metode Pembayaran List')->setIdentifier('footer-metode_pembayaran_list');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


/* footer - Keamanan Berbelanja List */
$cmsBlock = Mage::getModel('cms/block')->load('footer-keamanan_berbelanja_list', 'identifier');
$content =<<<EOF
<div class="block links logo-grid">
    <div class="block-title">
        Keamanan Berbelanja
    </div>
    <div class="block-content">
        <ul>
            <li><img src="{{skin url="images/sample/footer/mastercard_secure_code.png"}}" alt="Master Card Secure Code" /></li>
            <li><img src="{{skin url="images/sample/footer/verified_by_visa.png"}}" alt="Verified by Visa" /></li>
            <li><img src="{{skin url="images/sample/footer/idea.png"}}" alt="idea" /></li>
            <li><img src="{{skin url="images/sample/footer/norton.png"}}" alt="Norton" /></li>
        </ul>
    </div>
</div>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setTitle('Footer - Keamanan Berbelanja List')->setIdentifier('footer-keamanan_berbelanja_list');
}

$cmsBlock->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->save();


/* footer - Jasa Pengiriman List */
$cmsBlock = Mage::getModel('cms/block')->load('footer-jasa_pengiriman_list', 'identifier');
$content =<<<EOF
<div class="block links logo-grid">
    <div class="block-title">
        Jasa Pengiriman
    </div>
    <div class="block-content">
        <ul>
            <li><img src="{{skin url="images/sample/footer/jne.png"}}" alt="JNE" /></li>
            <li><img src="{{skin url="images/sample/footer/rpx.png"}}" alt="RPX Visa" /></li>
            <li><img src="{{skin url="images/sample/footer/ncs.png"}}" alt="NCS" /></li>
        </ul>
    </div>
</div>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setTitle('Footer - Jasa Pengiriman List')->setIdentifier('footer-jasa_pengiriman_list');
}

$cmsBlock->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->save();

/* footer - Socmed links */
$cmsBlock = Mage::getModel('cms/block')->load('footer-socmed_links', 'identifier');
$content =<<<EOF
<div class="block socmed">
    <div class="block-title">
        Terhubung Dengan Kami
    </div>
    <div class="block-content">
        <ul>
            <li><a href="#" class="twitter"></a></li>
            <li><a href="#" class="facebook"></a></li>
            <li><a href="#" class="instagram"></a></li>
        </ul>
    </div>
</div>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setTitle('Footer - Social Media links')->setIdentifier('footer-socmed_links');
}

$cmsBlock->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->save();


/* footer - Text */
$cmsBlock = Mage::getModel('cms/block')->load('footer-text', 'identifier');
$content =<<<EOF
<p>
Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur.
</p>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setTitle('Footer - Text')->setIdentifier('footer-text');
}

$cmsBlock->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->save();


$installer->endSetup();