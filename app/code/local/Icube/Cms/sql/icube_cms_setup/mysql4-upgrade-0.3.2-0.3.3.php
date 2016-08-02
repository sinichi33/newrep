<?php
/*
 * - Update Footer : logos
 */
$installer = $this;
$installer->startSetup();

/* footer - Cicilan list*/
$cmsBlock = Mage::getModel('cms/block')->load('footer-cicilan_list', 'identifier');
$content =<<<EOF
<div class="block links logo-grid">
    <div class="block-title">
        Cicilan 0%
    </div>
    <div class="block-content">
        <ul>
            <li><img src="{{skin url="images/sample/footer/bni.png"}}" alt="BNI" /></li>
            <li><img src="{{skin url="images/sample/footer/mandiri.png"}}" alt="Mandiri" /></li>
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
            <li><img src="{{skin url="images/sample/footer/visa.png"}}" alt="Visa" /></li>
            <li><img src="{{skin url="images/sample/footer/mastercard.png"}}" alt="Master Card" /></li>
            <li><img src="{{skin url="images/sample/footer/permata.png"}}" alt="Permata" /></li>
            <li><img src="{{skin url="images/sample/footer/bca.png"}}" alt="BCA" /></li>
            <li><img src="{{skin url="images/sample/footer/mandiri.png"}}" alt="Mandiri" /></li>
            <li><img src="{{skin url="images/sample/footer/atm_bersama.png"}}" alt="ATM Bersama" /></li>
            <li><img src="{{skin url="images/sample/footer/bank_transfer.png"}}" alt="Bank Transfer" /></li>
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
            <li><img src="{{skin url="images/sample/footer/sap.png"}}" alt="SAP Express" /></li>
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


$installer->endSetup();