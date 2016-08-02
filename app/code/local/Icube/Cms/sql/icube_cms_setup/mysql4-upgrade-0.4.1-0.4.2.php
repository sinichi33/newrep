<?php
/*
 * - Update FAQ pengembalian
 */
$installer = $this;
$installer->startSetup();

/* Rename "CMS Nav" to "Navigation - Layanan Konsumen" */
$cmsBlock = Mage::getModel('cms/block')->load('cms-navigation', 'identifier');

if(!$cmsBlock->getId()){
    $cmsBlock = Mage::getModel('cms/block')->load('nav-customercare', 'identifier');
}

$content =<<<EOF
<div id="cms-nav">
<div class="block">
    <div class="block-content">
    <ul>
    <li><i class="flaticon-telemarketer1"></i><a href="{{store url="contacts/index"}}">Hubungi Kami</a></li>
    <li><i class="flaticon-cart3"></i><a href="{{store url="how-to-shop"}}">Cara Belanja</a></li>
    <li><i class="flaticon-money146"></i><a href="{{store url="payment"}}">Pembayaran</a></li>
    <li><i class="flaticon-money15"></i><a href="{{store url="faq-pengembalian"}}" data-altpath="return">Pengembalian</a></li>
    <li><i class="flaticon-checked19"></i><a href="{{store url="trackorder/tracking"}}">Status Pesanan</a></li>
    <li><i class="flaticon-speech132"></i><a href="{{store url="faq"}}">F.A.Q.</a></li>
    </div>
</div>
</div>
EOF;

$cmsBlock->setStores(array(0))
        ->setTitle('Navigation - Layanan Konsumen')
        ->setIdentifier('nav-customercare')
        ->setContentHeading('Navigation - Layanan Konsumen')
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
            <li><a href="{{store url="payment"}}">Pembayaran</a></li>
            <li><a href="{{store url="faq-pengembalian"}}">Pengembalian</a></li>
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


/* FAQ pengembalian */
$cmsPage = Mage::getModel('cms/page')->load('faq-pengembalian', 'identifier');
$content =<<<EOF
<h1>PENGEMBALIAN</h1>

<h3>CARA PENGEMBALIAN BARANG</h3>
<p>Ruparupa.com memberikan kemudahan kepada customer untuk melakukan pengembalian produk.
Berikut langkah-langkahnya
</p>

<table class="progress">
	<tr>
        <td style="padding-top:12px;">
            <img src="{{skin url="images/sample/cms/return-step-1.png"}}" />
        </td>
        <td>
            <span>1</span>
        </td>
        <td>
            Foto produk yang ingin dikembalikan (rusak / tidak sesuai)
        </td>
    </tr>
	<tr>
        <td style="padding-top:12px;">
            <img src="{{skin url="images/sample/cms/return-step-2.png"}}" />
        </td>
        <td>
            <span>2</span>
        </td>
        <td>
            Isi dan Upload foto di form elektronik yang sudah disediakan, tunggu persetujuan dari pihak Ruparupa melalui email/telepon
        </td>
    </tr>
	<tr>
        <td style="padding-top:12px;">
            <img src="{{skin url="images/sample/cms/return-step-3.png"}}" />
        </td>
        <td>
            <span>3</span>
        </td>
        <td>
            Jika sudah disetujui, kemas kembali barang seperti semula, dan kirimkan ke Distribution Center kami dengan alamat : 
            <strong>
            	Distribution Center  PT.Omni Digitama Internusa<br/>
				Komplek Pergudangan Kawan Lama<br/>
				Jl. Industri Selatan Blok PP No. 4 Jababeka II<br/>
				Desa Pasir Sari, Kecamatan Cikarang Selatan –<br/>
				Bekasi 17550<br/>
			</strong>
        </td>
    </tr>
	<tr>
        <td style="padding-top:12px;">
            <img src="{{skin url="images/sample/cms/return-step-4.png"}}" />
        </td>
        <td>
            <span>4</span>
        </td>
        <td>
            Setelah barang sampai di Distribution Center, Ruparupa akan memproses penukaran barang dan pengembalian biaya pengiriman*
        </td>
    </tr>
</table>
<br/>
<h3>Syarat dan Ketentuan pengembalian produk di ruparupa.com :</h3>
<p>
1. Jika Anda tidak puas dengan produk yang dikirimkan oleh ruparupa.com, maka Anda dapat melakukan proses pengembalian produk dalam jangka waktu 14 hari setelah barang diterima. Ketentuan ini hanya berlaku untuk produk yang belum dipakai, bersegel dan masih berada dalam kemasan asli.
</p>

<p>
2. Ketentuan produk rusak 
</p>
<ul>
<li>Produk rusak karena cacat produksi, bukan karena kesalahan penggunaan, seperti : Jatuh, tergores, masuk air, salah instalasi, bencana alam, sudah dibongkar-pasang dan sebagainya</li>
<li>Kerusakan produk terjadi dalam masa pengiriman</li>
</ul>
 
<p>
3. Ketidaksesuaian produk
</p>
<ul>
<li>Jika spesifikasi produk yang diterima tidak sesuai dengan keterangan dan kelengkapan yang tertera pada website ruparupa.com</li>
<li>Barang yang diterima tidak sesuai dengan yang dipesan, meliputi : Kesalahan unit, jumlah, tipe, warna dan ukuran</li>
</ul>

<table class="table-lines">
	<thead>
		<tr>
			<td>No</td>
			<td>Alasan Pengembalian</td>
			<td>Batas Waktu</td>
			<td>Tukar Unit</td>
			<td>Pengembalia<br/>Voucher</td>
			<td>Pengembalian<br/>Uang</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td>Produk Rusak</td>
			<td>14 hari</td>
			<td><img src="{{skin url="images/sample/cms/check.png"}}" style="display:inline-block" /></td>
			<td><img src="{{skin url="images/sample/cms/check.png"}}" style="display:inline-block" /><sup style="top: -0.6em;display: inline-block;font-size: 13px;margin-left: 3px;">*</sup></td>
			<td><img src="{{skin url="images/sample/cms/check.png"}}" style="display:inline-block" /><sup style="top: -0.6em;display: inline-block;font-size: 13px;margin-left: 3px;">*</sup></td>
		</tr>
		<tr>
			<td>2</td>
			<td>Produk Tidak sesuai</td>
			<td>14 hari</td>
			<td><img src="{{skin url="images/sample/cms/check.png"}}" style="display:inline-block"/></td>
			<td><img src="{{skin url="images/sample/cms/check.png"}}" style="display:inline-block"/><sup style="top: -0.6em;display: inline-block;font-size: 13px;margin-left: 3px;">*</sup></td>
			<td><img src="{{skin url="images/sample/cms/check.png"}}" style="display:inline-block"/><sup>*</sup></td>
		</tr>
		<tr>
			<td>3</td>
			<td>Pembatalan transaksi</td>
			<td>14 hari</td>
			<td><img src="{{skin url="images/sample/cms/check.png"}}" style="display:inline-block"/></td>
			<td><img src="{{skin url="images/sample/cms/check.png"}}" style="display:inline-block"/><sup style="top: -0.6em;display: inline-block;font-size: 13px;margin-left: 3px;">*</sup></td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>
<div class="table-note">
*Jika customer sudah melakukan prosedur pengembalian, tetapi unit tidak tersedia maka ruparupa.com akan menawarkan pengembalian dana berupa voucher atau uang<br/>
** Sesuai dengan jumlah pembayaran yang dibayarkan
</div>

<p>
4. Customer tidak dapat melakukan pengajuan pengembalian produk setelah 14 hari, jika terjadi kerusakan, customer dapat menggunakan garansi dari produk yang dipesan (jika ada)

<p>
5. Customer dapat melakukan pengecekkan status pengembalian produk di XXXXXXXXXXXXX<br/>
Proses pergantian dan evaluasi produk akan berlangsung dalam jangka waktu:<br/>
<span style="width:150px;display:inline-block;">Jabodetabek</span><span>:  XX hari </span><br/>
<span style="width:150px;display:inline-block;">Di Luar Jabodetabek</span><span>: XX hari </span><br/>
Kami akan mengirimkan email pemberitahuan jika produk yang Anda tukar sudah kami kirimkan kembali
</p>

<p>
7. Ketentuan Pengajuan Klaim Biaya Pengiriman Pengembalian Produk ruparupa.com*
</p>
<ul>
<li>Scan bukti pengiriman untuk pengembalian biaya pengiriman</li>
<li>Biaya Pengiriman akan ditanggung terlebih dahulu oleh customer ruparupa.com</li>
<li>Customer hanya dapat mengklaim biaya pengiriman pengembalian produk jika produk yang dikirimkan dinyatakan dalam keadaan rusak, spesifikasi dan kelengkapan tidak sesuai. Ruparupa.com akan mengganti seluruh biaya - pengembalian dengan kondisi tersebut.</li>
<li>Ruparupa.com tidak mengganti biaya pengembalian produk karena pembatalan transaksi</li>
<li>Ruparupa.com hanya akan mengganti biaya pengiriman pengembalian produk sesuai dengan tarif JNE</li>
<li>Jika produk yang dikirimkan kembali tidak layang untuk diproses lebih lanjut, maka ruparupa.com tidak akan mengganti biaya pengiriman pengembalian produk</li>
</ul>

<p>
8. Syarat, ketentuan, keputusan dan kebijakan ruparupa.com dalam pengembalian produk  atau uang adalah bersifat mutlak dan tidak dapat diganggu gugat. Ruparupa.com berhak untuk membatalkan pengajuan pengembalian produk jika pengajuan tidak sesuai dengan proses serta syarat dan ketentuan yang berlaku.
</p>

<p>
9. Syarat dan ketentuan dapat berubah sewaktu-waktu tanpa pemberitahuan terlebih 
</p>

<button class="button" style="margin:40px auto 20px;display:block;border-radius:6px;" onclick="window.location.replace('{{store url="return/return/search"}}')"><span><span>FORM PENGEMBALIAN BARANG</span></span></button>
EOF;

$navCustomer = <<<EOT
<reference name="left">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-customercare</block_id></action>
    </block> 
</reference>
EOT;

if(!$cmsPage->getId()){
	$cmsPage->setTitle('Pengembalian - FAQ')->setIdentifier('faq-pengembalian');
}

$cmsPage->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
        ->setCustomLayoutUpdateXml($navCustomer)
        ->setRootTemplate('two_columns_left')
		->save();

$installer->endSetup();
