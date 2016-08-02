<?php
/*
 * - Update Header announcement Block
 */
$installer = $this;
$installer->startSetup();

/* Header - Announcement block*/
$cmsBlock = Mage::getModel('cms/block')->load('header-announcement', 'identifier');
$content =<<<EOF
<div style="background:#525460">
    <div class="header-container">
    <ul>
        <li><a href="#">GRATIS PENGIRIMAN tiap belanja Rp 300.000,-</a></li>
        <li><a href="#">JAMINAN PENGEMBALIAN DAN PENUKARAN BARANG</a></li>
        <li><a href="{{store url="pickup-store/"}}">AMBIL DI TOKO</a></li>
    </ul>
    </div>
</div>

<div style="background:#008ccf">
    <div class="header-container">
    <ul>
        <li>Lorem ipsum dolor sit amet</li>
        <li>Lorem ipsum dolor sit amet</li>
        <li>Lorem ipsum dolor sit amet</li>
    </ul>
    </div>
</div>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Header - Announcement')->setIdentifier('header-announcement');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();