<?php
/*
 * - Nav - Layanan Konsumen & Info: add title
 * - Fix: CMS Nav position
 */

$installer = $this;
$installer->startSetup();

/* Navigation - Layanan Konsumen */
$cmsBlock = Mage::getModel('cms/block')->load('nav-customercare', 'identifier');

$content =<<<EOF
<div id="cms-nav">
<div class="title">Layanan Konsumen</div>
<div class="block">
    <div class="block-content">
    <ul>
    <li><i class="flaticon-telemarketer1">&nbsp;</i><a href="{{store url="contacts/index"}}">Hubungi Kami</a></li>
    <li><i class="flaticon-cart3">&nbsp;</i><a href="{{store url="how-to-shop"}}">Cara Belanja</a></li>
    <li><i class="flaticon-money146">&nbsp;</i><a href="{{store url="payment"}}">Pembayaran</a></li>
    <li><i class="flaticon-money15">&nbsp;</i><a href="{{store url="faq-pengembalian"}}" data-altpath="return">Pengembalian</a></li>
    <li><i class="flaticon-checked19">&nbsp;</i><a href="{{store url="trackorder/tracking"}}">Status Pesanan</a></li>
    <li><i class="flaticon-speech132">&nbsp;</i><a href="{{store url="faq"}}">F.A.Q.</a></li>
    </div>
</div>
</div>
EOF;

if($cmsBlock->getId()){
    $cmsBlock->setContent($content)
        ->save();
}


/* Navigation - Info rupaurupa */
$cmsBlock = Mage::getModel('cms/block')->load('nav-inforuparupa', 'identifier');

$content =<<<EOF
<div id="cms-nav">
<div class="title">Info Ruparupa</div>
<div class="block">
    <div class="block-content">
    <ul>
    <li><i class="flaticon-signs">&nbsp;</i><a href="{{store url="about-us"}}">Tentang Ruparupa</a></li>
    <li><i class="flaticon-lock">&nbsp;</i><a href="{{store url="privacy-policy"}}">Kebijakan Privasi</a></li>
    <li><i class="flaticon-black">&nbsp;</i><a href="{{store url="terms-conditions"}}">Syarat &amp; Ketentuan</a></li>
    <li><i class="flaticon-web">&nbsp;</i><a href="http://blog.ruparupa.com" target="_blank">Blog</a></li>
    <li><i class="flaticon-fashion">&nbsp;</i><a href="{{store url="career"}}">Info Karir</a></li>
    </div>
</div>
</div>
EOF;

if($cmsBlock->getId()){
    $cmsBlock->setContent($content)
        ->save();
}

/* Fix cms nav position on FAQ pengembalian & update content */
$cmsPage = Mage::getModel('cms/page')->load('faq-pengembalian', 'identifier');

$content = <<<EOT
<h1>PENGEMBALIAN</h1>
<h3>CARA PENGEMBALIAN</h3>
<p><span>Kami percaya bahwa kepuasan pelanggan adalah prioritas kami. Jika Anda tidak puas dengan produk yang sudah Anda beli, silakan ikuti langkah-langkah berikut untuk melakukan penukaran/pengembalian produk</span>:</p>
<table class="progress">
<tbody>
<tr>
<td style="padding-top: 12px;"><img alt="" src="{{skin url="images/sample/cms/return-step-1.png"}}" /></td>
<td><span>1</span></td>
<td>Foto produk yang ingin dikembalikan (rusak / tidak sesuai)</td>
</tr>
<tr>
<td style="padding-top: 12px;"><img alt="" src="{{skin url="images/sample/cms/return-step-2.png"}}" /></td>
<td><span>2</span></td>
<td>Isi dan Upload foto di form elektronik yang sudah disediakan, tunggu persetujuan dari pihak Ruparupa melalui email/telepon (Proses persetujuan akan berlangsung 1-3 hari)</td>
</tr>
<tr>
<td style="padding-top: 12px;"><img alt="" src="{{skin url="images/sample/cms/return-step-3.png"}}" /></td>
<td><span>3</span></td>
<td>
<p>Jika sudah disetujui, kemas kembali produk seperti semula, dan kirimkan ke Distribution Center kami dengan alamat :</p>
<p><strong> Distribution Center PT.Omni Digitama Internusa<br /> Komplek Pergudangan Kawan Lama<br /> Jl. Industri Selatan Blok PP No. 4 Jababeka II<br /> Desa Pasir Sari, Kecamatan Cikarang Selatan &ndash;<br /> Bekasi 17550<br /> </strong></p>
</td>
</tr>
<tr>
<td style="padding-top: 12px;"><img alt="" src="{{skin url="images/sample/cms/return-step-4.png"}}" /></td>
<td><span>4</span></td>
<td>Setelah produk sampai di Distribution Center, Ruparupa akan memproses penukaran produk dan pengembalian biaya pengiriman*</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<h3>Syarat dan Ketentuan pengembalian produk di Ruparupa :</h3>
<p>1. Jika Anda tidak puas dengan produk yang dikirimkan oleh Ruparupa, maka Anda dapat melakukan proses pengembalian produk dalam jangka waktu 14 hari setelah produk diterima. Ketentuan ini hanya berlaku untuk produk yang belum dipakai, bersegel dan masih berada dalam kemasan asli.</p>
<p>2. Ketentuan produk rusak</p>
<ul>
<li>Produk rusak karena cacat produksi, bukan karena kesalahan penggunaan, seperti : Jatuh, tergores, masuk air, salah instalasi, bencana alam, sudah dibongkar-pasang dan sebagainya</li>
<li>Kerusakan produk terjadi dalam masa pengiriman</li>
</ul>
<p>3. Ketidaksesuaian produk</p>
<ul>
<li>Jika spesifikasi produk yang diterima tidak sesuai dengan keterangan dan kelengkapan yang tertera pada situs Ruparupa</li>
<li>Produk yang diterima tidak sesuai dengan yang dipesan, meliputi : Kesalahan produk, jumlah, tipe, warna dan ukuran</li>
</ul>
<table class="table-lines" id="return-type-list">
<thead>
<tr>
<td>No</td>
<td>Alasan Pengembalian</td>
<td>Batas Waktu</td>
<td>Tukar Produk</td>
<td>Pengembalian<br />Voucher</td>
<td>Pengembalian<br />Uang</td>
</tr>
</thead>
<tbody>
<tr>
<td data-rwd-label="No">1</td>
<td data-rwd-label="Alasan Pengembalian">Produk Rusak</td>
<td data-rwd-label="Batas Waktu">14 hari</td>
<td data-rwd-label="Tukar Produk"><img style="display: inline-block;" alt="" src="{{skin url="images/sample/cms/check.png"}}" /></td>
<td data-rwd-label="Pengembalian Voucher"><img style="display: inline-block;" alt="" src="{{skin url="images/sample/cms/check.png"}}" /><sup style="top: -0.6em; display: inline-block; font-size: 13px; margin-left: 3px;">*</sup></td>
<td data-rwd-label="Pengembalian Uang"><img style="display: inline-block;" alt="" src="{{skin url="images/sample/cms/check.png"}}" /><sup style="top: -0.6em; display: inline-block; font-size: 13px; margin-left: 3px;">*</sup></td>
</tr>
<tr>
<td data-rwd-label="No">2</td>
<td data-rwd-label="Alasan Pengembalian">Produk Tidak sesuai</td>
<td data-rwd-label="Batas Waktu">14 hari</td>
<td data-rwd-label="Tukar Produk"><img style="display: inline-block;" alt="" src="{{skin url="images/sample/cms/check.png"}}" /></td>
<td data-rwd-label="Pengembalian Voucher"><img style="display: inline-block;" alt="" src="{{skin url="images/sample/cms/check.png"}}" /><sup style="top: -0.6em; display: inline-block; font-size: 13px; margin-left: 3px;">*</sup></td>
<td data-rwd-label="Pengembalian Uang"><img style="display: inline-block;" alt="" src="{{skin url="images/sample/cms/check.png"}}" /><sup style="top: -0.6em; display: inline-block; font-size: 13px; margin-left: 3px;">*</sup></td>
</tr>
<tr>
<td data-rwd-label="No">3</td>
<td data-rwd-label="Alasan Pengembalian">Pembatalan transaksi</td>
<td data-rwd-label="Batas Waktu">14 hari</td>
<td data-rwd-label="Tukar Produk"><img style="display: inline-block;" alt="" src="{{skin url="images/sample/cms/check.png"}}" /></td>
<td data-rwd-label="Pengembalian Voucher"><img style="display: inline-block;" alt="" src="{{skin url="images/sample/cms/check.png"}}" /><sup style="top: -0.6em; display: inline-block; font-size: 13px; margin-left: 3px;">*</sup></td>
<td data-rwd-label="Pengembalian Uang">&nbsp;</td>
</tr>
</tbody>
</table>
<div class="table-note">*Jika pelanggan sudah melakukan prosedur pengembalian, tetapi produk tidak tersedia maka Ruparupa akan menawarkan pengembalian dana berupa voucher atau metode pembayaran diawal.<br /> ** Sesuai dengan jumlah pembayaran yang dibayarkan</div>
<p>4. Pelanggan tidak dapat melakukan pengajuan pengembalian produk setelah 14 hari, jika terjadi kerusakan, pelanggan dapat menggunakan garansi dari produk yang dipesan (jika ada)</p>
<p>5. Proses penggantian dan evaluasi produk akan berlangsung dalam jangka waktu:</p>
<ul>
<li>Jabodetabek : 3-6 hari</li>
<li>Di Luar Jabodetabek : 7-15 hari</li>
</ul>
<p>6. Ketentuan Pengajuan Klaim Biaya Pengiriman Pengembalian Produk Ruparupa*</p>
<ul>
<li>Scan bukti pengiriman untuk pengembalian biaya pengiriman</li>
<li>Biaya Pengiriman akan ditanggung terlebih dahulu oleh pelanggan Ruparupa</li>
<li>Pelanggan hanya dapat mengklaim biaya pengiriman pengembalian produk jika produk yang dikirimkan dinyatakan dalam keadaan rusak, spesifikasi dan kelengkapan tidak sesuai. Ruparupa akan mengganti seluruh biaya - pengembalian dengan kondisi tersebut.</li>
<li>Ruparupa tidak mengganti biaya pengembalian produk karena pembatalan transaksi</li>
<li>Ruparupa hanya akan mengganti biaya pengiriman pengembalian produk sesuai dengan tarif JNE dan ruparupa berhak untuk menolak biaya yang tidak sesuai</li>
<li>Jika produk yang dikirimkan kembali tidak layang untuk diproses lebih lanjut, maka Ruparupa tidak akan mengganti biaya pengiriman pengembalian produk</li>
</ul>
<p>7. Syarat, ketentuan, keputusan dan kebijakan Ruparupa dalam pengembalian produk atau uang adalah bersifat mutlak dan tidak dapat diganggu gugat. Ruparupa berhak untuk membatalkan pengajuan pengembalian produk jika pengajuan tidak sesuai dengan proses serta syarat dan ketentuan yang berlaku.</p>
<p>8. Syarat dan ketentuan dapat berubah sewaktu-waktu tanpa pemberitahuan terlebih dahulu</p>
<p><button class="button" style="margin: 40px auto 20px; display: block; border-radius: 6px;" onclick="window.location.replace('{{store url="return/return/search"}}')"><span><span>FORM PENGEMBALIAN PRODUK</span></span></button></p>
EOT;

$navCustomer = <<<EOT
<reference name="left_first">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-customercare</block_id></action>
    </block> 
</reference>
EOT;

if($cmsPage->getId()){
    $cmsPage->setCustomLayoutUpdateXml($navCustomer)
        ->setContent($content)
        ->save();
}

/* Fix cms nav position on How to Shop */
$cmsPage = Mage::getModel('cms/page')->load('how-to-shop', 'identifier');

$navCustomer = <<<EOT
<reference name="left_first">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-customercare</block_id></action>
    </block> 
</reference>
EOT;

if($cmsPage->getId()){
    $cmsPage->setCustomLayoutUpdateXml($navCustomer)
        ->save();
}

/* Fix cms nav position on Pembayaran */
$cmsPage = Mage::getModel('cms/page')->load('payment', 'identifier');

$navCustomer = <<<EOT
<reference name="left_first">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-customercare</block_id></action>
    </block> 
</reference>
EOT;

if($cmsPage->getId()){
    $cmsPage->setCustomLayoutUpdateXml($navCustomer)
        ->save();
}

/* Fix cms nav position on FAQ */
$cmsPage = Mage::getModel('cms/page')->load('faq', 'identifier');

$navCustomer = <<<EOT
<reference name="left_first">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-customercare</block_id></action>
    </block> 
</reference>
EOT;

if($cmsPage->getId()){
    $cmsPage->setCustomLayoutUpdateXml($navCustomer)
        ->save();
}




/* Fix cms nav position on About us */
$cmsPage = Mage::getModel('cms/page')->load('about-us', 'identifier');

$navCustomer = <<<EOT
<reference name="left_first">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-inforuparupa</block_id></action>
    </block> 
</reference>
EOT;

if($cmsPage->getId()){
    $cmsPage->setCustomLayoutUpdateXml($navCustomer)
        ->save();
}


/* Fix cms nav position on Kebijakan Privasi */
$cmsPage = Mage::getModel('cms/page')->load('privacy-policy', 'identifier');

$navCustomer = <<<EOT
<reference name="left_first">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-inforuparupa</block_id></action>
    </block> 
</reference>
EOT;

if($cmsPage->getId()){
    $cmsPage->setCustomLayoutUpdateXml($navCustomer)
        ->save();
}



/* Fix cms nav position on Syarat & Ketentuan */
$cmsPage = Mage::getModel('cms/page')->load('terms-conditions', 'identifier');

$navCustomer = <<<EOT
<reference name="left_first">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-inforuparupa</block_id></action>
    </block> 
</reference>
EOT;

if($cmsPage->getId()){
    $cmsPage->setCustomLayoutUpdateXml($navCustomer)
        ->save();
}



/* Fix cms nav position on Info Karir */
$cmsPage = Mage::getModel('cms/page')->load('career', 'identifier');

$navCustomer = <<<EOT
<reference name="left_first">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-inforuparupa</block_id></action>
    </block> 
</reference>
EOT;

if($cmsPage->getId()){
    $cmsPage->setCustomLayoutUpdateXml($navCustomer)
        ->save();
}

$installer->endSetup();
