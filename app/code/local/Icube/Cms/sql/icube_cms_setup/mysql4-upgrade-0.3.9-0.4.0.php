<?php
/* 
 * - Update footer - About Us links
 * - Update footer - Layanan Konsumen links 
 * - Rename "CMS Nav" to "Navigation - Layanan Konsumen"
 * - Create "Navigation - Info Ruparupa"
 * - About Us
 * - Terms & Conditions
 * - Privacy & Policy
 * - Info Karir
 * - FAQ
 * - Pembayaran
 * - 404
 * - remove unsed block in contacts
 */

$installer = $this;
$installer->startSetup();


/*---------------------------*/
/*Vars*/

/* custom layout update for nav-inforuparupa */
$navInfo = <<<EOT
<reference name="left">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-inforuparupa</block_id></action>
    </block> 
</reference>
EOT;

/* custom layout update for nav-inforuparupa */
$navCustomer = <<<EOT
<reference name="left">
    <block type="cms/block" name="cms_nav">
        <action method="setBlockId"><block_id>nav-customercare</block_id></action>
    </block> 
</reference>
EOT;
/*---------------------------*/

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
            <li><a href="http://blog.ruparupa.com" target="_blank">Blog</a></li>
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
            <li><a href="{{store url="payment"}}">Pembayaran</a></li>
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
    <li><i class="flaticon-money15"></i><a href="{{store url="return/return/search"}}">Pengembalian</a></li>
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


/* Create "Navigation - Info Ruparupa" */
$cmsBlock = Mage::getModel('cms/block')->load('nav-inforuparupa', 'identifier');

$content =<<<EOF
<div id="cms-nav">
<div class="block">
    <div class="block-content">
    <ul>
    <li><i class="flaticon-signs"></i><a href="{{store url="about-us"}}">Tentang Ruparupa</a></li>
    <li><i class="flaticon-lock"></i><a href="{{store url="privacy-policy"}}">Kebijakan Privacy</a></li>
    <li><i class="flaticon-black"></i><a href="{{store url="terms-conditions"}}">Syarat & Ketentuan</a></li>
    <li><i class="flaticon-web"></i><a href="http://blog.ruparupa.com" target="_blank">Blog</a></li>
    <li><i class="flaticon-fashion"></i><a href="{{store url="career"}}">Info Karir</a></li>
    </div>
</div>
</div>
EOF;

$cmsBlock->setStores(array(0))
        ->setTitle('Navigation - Layanan Konsumen')
        ->setIdentifier('nav-inforuparupa')
        ->setContentHeading('Navigation - Layanan Konsumen')
        ->setContent($content)
        ->setIsActive(1)
        ->save();

/* About Us */
$cmsPage = Mage::getModel('cms/page')->load('about-us', 'identifier');
$content =<<<EOF
<h1><img src="{{skin url="images/logo.gif"}}" alt="Tentang Ruparupa" /></h1>
<table>
<tbody>
<tr>
<td style="width:62%">
<p>Gaya hidup modern tentu menghadirkan berbagai inovasi untuk mempermudah hidup manusia, salah satunya dalam berbelanja. Untuk itu, <b>Ruparupa</b> hadir dengan konsep <b>One Stop Online Shopping</b> untuk menjawab kebutuhan masyarakat modern akan kemudahan berbelanja, kapanpun dan dimanapun Anda menginginkannya. Kami berusaha membantu Anda dalam bertransaksi dan menikmati gaya hidup modern yang praktis melalui berbagai produk eksklusif berkualitas premium dengan harga yang kompetitif. <b>Ruparupa </b>berkomitmen untuk memberikan pengalaman belanja online yang aman dan nyaman dengan jaminan orisinalitas untuk semua produk yang kami jual, transaksi dengan proses yang cepat dan mudah, fasilitas penukaran dan pengembalian produk, garansi resmi dari vendor-vendor terkemuka. Untuk kemudahan Anda, <b>Ruparupa</b> juga menghadirkan fitur pengambilan langsung di beberapa pick up point yang berlokasi di berbagai tempat strategis di Jakarta serta beragam pilihan fasilitas pembayaran yang lengkap, mudah dan aman.</p>
<p>Kami menyadari bahwa setiap manusia pasti melewati berbagai momen spesial, entah itu momen pernikahan, memiliki rumah pertama, kelahiran buah hati Anda hingga melihat si kecil masuk ke sekolah untuk pertama kalinya. Untuk itu, kami berusaha memberikan solusi dan kemudahan untuk melengkapi setiap momen penting dalam hidup Anda. <b>Ruparupa</b> memberikan layanan terbaik dalam belanja online dengan berbagai produk untuk Anda dan keluarga, baik itu kebutuhan untuk melengkapi rumah baru, memenuhi kebutuhan liburan serta menyediakan berbagai mainan bagi si buah hati. <b>Ruparupa </b>menyediakan beragam produk berkualitas mulai dari furniture, dekorasi rumah, kebutuhan olahraga, perlengkapan rumah tangga, peralatan dapur, aksesori, perkakas, serta hobi dan gaya hidup. <b>Ruparupa</b> merupakan bagian dari Kawan Lama Group yang telah berpengalaman selama puluhan tahun dalam bisnis retail dan membawahi sejumlah anak perusahaan antara lain Ace Hardware, Informa Furnishings dan Toys Kingdom.</p>
<p>Selamat menikmati pengalaman berbelanja yang menyenangkan hanya di <b>Ruparupa</b>!&nbsp;</p>
<p><img src="{{skin url="images/sample/cms/dot-blue-grey-orange.png"}}"alt="Tentang Ruparupa" /></p>
</td>
<td style="width:38%">
<img src="{{skin url="images/sample/cms/aboutus.jpg"}}" alt="Tentang Ruparupa" style="margin: 0 auto;"/>
</td>
</tr>
</tbody>
</table>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setIdentifier('about-us');
}

$cmsPage->setStores(array(0))
        ->setTitle('Tentang Ruparupa')
        ->setContent($content)
        ->setIsActive(1)
        ->setCustomLayoutUpdateXml($navInfo)
        ->setRootTemplate('two_columns_left')
        ->save();

/* Privacy Policy */
$cmsPage = Mage::getModel('cms/page')->load('privacy-policy', 'identifier');
$content =<<<EOF
<h1>Kebijakan Privasi</h1>
<p>Ruparupa sangat menjaga kerahasiaan dan menghormati hal-hal yang berkenaan dengan privasi Anda. Pemberitahuan Privasi ini menjelaskan jenis informasi pribadi yang kami kumpulkan, bagaimana kami menggunakan informasi tersebut, dengan siapa kami membagi informasi tersebut dan pilihan yang dapat Anda buat mengenai pengumpulan serta penggunaan dan pengungkapan informasi tersebut oleh kami.</p>
<p>Kami dapat mengubah Kebijakan Privasi ini dari waktu ke waktu dengan melakukan pengurangan ataupun penambahan ketentuan pada halaman ini. Perubahan terhadap kebijakan ini akan diumumkan melalui situs ini atau melalui alamat dari media lain yang Anda berikan kepada kami. Anda dianjurkan untuk membaca Kebijakan Privasi ini secara berkala agar mengetahui perubahan terbaru.</p>
<h3>Pengumpulan Informasi</h3>
<p>Kami tidak menjual, menyebarkan atau memperdagangkan informasi pribadi milik pelanggan yang didapatkan online dengan kepada pihak ketiga. aplikasi mobile, saat Anda menghubungi atau mengirim email kepada kami atau berkomunikasi dengan kami melalui media sosial, maupun saat Anda berpartisipasi dalam acara atau promosi kami, juga melalui induk, afiliasi dan anak perusahaan kami, serta mitra bisnis dan pihak ketiga lainnya.</p>
<p>Ketika Anda membuat akun Ruparupa, atau memberikan informasi pribadi Anda melalui platform, informasi pribadi yang kami kumpulkan dapat meliputi:</p>
<ul>
<li>Nama</li>
<li>Alamat Pengiriman</li>
<li>Alamat Email</li>
<li>Nomor Telepon</li>
<li>Nomor Ponsel</li>
<li>Tanggal Lahir</li>
<li>Jenis Kelamin</li>
</ul>
<p>Kami beranggapan bahwa informasi yang Anda berikan saat ini dan perubahan-perubahan yang Anda lakukan di masa mendatang adalah akurat dan benar. Apabila informasi dan perubahan-perubahan yang diberikan tersebut ternyata terbukti tidak benar, maka kami tidak bertanggung jawab atas segala akibat yang dapat terjadi sehubungan dengan pemberian informasi dan perubahan-perubahan yang tidak benar tersebut.</p>
<h3>Penggunaan Informasi </h3>
<p>Informasi pribadi yang kami kumpulkan dari Anda dapat digunakan, atau dibagikan dengan pihak ketiga (termasuk perusahaan terkait, penyedia jasa layanan pihak ketiga, dan penjual pihak ketiga), untuk beberapa atau semua tujuan berikut:</p>
<ul>
<li>Memproses pesanan yang Anda kirimkan melalui situs ini, baik produk yang dijual oleh Ruparupa atau penjual pihak ketiga.</li>
<li>Menyediakan produk atau layanan yang Anda minta</li>
<li>Memproses, memvalidasi, mengonfirmasi, memverifikasi, mengirim dan melacak pembelian Anda (termasuk memproses transaksi kartu pembayaran, mengatur pengiriman serta menangani pengembalian produk dan uang, dan menghubungi Anda terkait pemesanan, termasuk menghubungi melalui telepon)</li>
<li>Merekam catatan pembelian Anda di situs kami</li>
<li>Merespons pertanyaan dan komentar Anda, juga menyediakan bantuan pelanggan</li>
<li>Menginformasikan produk, layanan, penawaran, acara dan promosi kami, serta menawarkan produk dan layanan yang kami percaya mungkin menarik bagi Anda</li>
<li>Memungkinkan Anda untuk berkomunikasi dengan kami melalui blog, jejaring sosial dan media interaktif lainnya</li>
<li>Mengelola partisipasi Anda di acara, undian dan promosi kami lainnya</li>
<li>Menyesuaikan produk dan layanan kami dengan minat pribadi Anda ketika pengunjung menggunakan situs, aplikasi dan aset media sosial kami</li>
<li>Mengoperasikan, mengevaluasi dan meningkatkan bisnis serta produk dan layanan yang kami tawarkan</li>
<li>Menganalisis dan meningkatkan komunikasi dan strategi pemasaran kami (termasuk dengan mengidentifikasi apakah email yang dikirimkan ke Anda sudah diterima dan dibaca)</li>
<li>Menganalisis tren dan statistik mengenai penggunaan situs, aplikasi mobile dan aset media sosial oleh pengunjung, serta pembelian yang dilakukan pengunjung di situs kami</li>
<li>Memberikan perlindungan terhadap dan mencegah penipuan, transaksi tidak sah, klaim dan kewajiban lainnya, serta mengelola munculnya risiko, termasuk dengan mengidentifikasi adanya peretas dan pengguna lainnya yang tidak sah</li>
<li>Menegakkan Ketentuan Penggunaan serta Syarat dan Ketentuan Situs Web kami</li>
<li>Mematuhi persyaratan hukum, standar industri dan kebijakan kami yang berlaku</li>
</ul>
<h3>Hubungi Kami</h3>
<p>Jika Anda memiliki pertanyaan atau komentar tentang Kebijakan Privasi ini atau Anda menginginkan kami memperbarui informasi yang kami miliki tentang Anda atau pilihan Anda, kami dengan senang hati menerimanya melalui email di <a href="mailto:hello@ruparupa.com">hello@ruparupa.com</a> .</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Kebijakan Privasi')->setIdentifier('privacy-policy');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setCustomLayoutUpdateXml($navInfo)
        ->setRootTemplate('two_columns_left')
        ->save();

/* Terms & Confitions */
$cmsPage = Mage::getModel('cms/page')->load('terms-conditions', 'identifier');
$content =<<<EOF
<h1>Syarat &amp; Ketentuan</h1>
<p>Selamat datang di situs <a href="http://www.ruparupa.com/">www.ruparupa.com</a>. <strong>Silakan membaca syarat &amp; ketentuan penggunaan ini dengan seksama</strong>. Dengan mengakses atau menggunakan situs Ruparupa, Pengguna dianggap telah memahami dan menyetujui semua isi dalam syarat dan ketentuan di bawah ini. Syarat dan ketentuan dapat diubah atau diperbaharui sewaktu-waktu tanpa ada pemberitahuan terlebih dahulu. <b>Jika pengguna merasa keberatan terhadap syarat dan ketentuan yang kami ajukan, maka kami anjurkan untuk tidak menggunakan situs ini.</b></p>
<p><strong>Jika Anda berusia di bawah 18 tahun, maka</strong> Anda harus memperoleh persetujuan dari orang tua atau wali Anda, penerimaan atau persetujuan orang tua/wali terhadap Persyaratan Penggunaan ini beserta persetujuan mereka untuk mengambil tanggung jawab untuk tindakan Anda, biaya yang terkait dengan penggunaan setiap layanan atau pembelian produk dan penerimaan dan kepatuhan Anda sesuai dengan Syarat &amp; Ketentuan Penggunaan ini. <strong>Jika Anda tidak memiliki izin dari orang tua atau wali, Anda harus berhenti menggunakan / mengakses dan berhenti menggunakan situs ini.</strong></p>
<h3>1. Ketentuan Penggunaan Situs</h3>
<p>Setiap pengguna wajib untuk mematuhi ketentuan pengguna situs berikut ini: <br/>
1.1 Anda setuju untuk mematuhi setiap dan semua pedoman, pemberitahuan, aturan operasi dan kebijakan dan instruksi yang berkaitan dengan situs ini.<br/>
1.2 Akses situs ini hanya diperkenankan untuk keperluan dan kepentingan berbelanja dan informasi terkait dengan layanan situs ini.<br/>
1.3 Pengguna tidak diperkenankan untuk memuat dan menerbitkan konten yang melanggar hak cipta, paten, merek dagang, merek layanan, rahasia dagang, atau hak kepemilikan lainnya.<br/>
1.4 Pengguna dilarang menggunakan atau mengunggah perangkat lunak atau material yang mengandung / dicurigai mengandung virus, komponen merusak, kode berbahaya atau komponen berbahaya dengan cara apapun yang dapat merusak data atau mengakibatkan kerusakan atau mengganggu pengoperasian komputer pelanggan lain atau perangkat <em>mobile</em> lainnya.<br/>
1.5 Selain itu, kami memantau, menyaring atau mengontrol setiap kegiatan, isi atau materi pada situs ini. Atas kebijakan kami sendiri, kami dapat menyelidiki setiap pelanggaran terhadap syarat dan ketentuan yang tercantum di sini dan dapat mengambil tindakan apapun yang dianggap sesuai atau tepat.</p>
<h3>2. Hak Milik Intelektual</h3>
<p>PT Omni Digitama Internusa adalah pemilik tunggal atau pemegang sah semua hak atas situs dan konten situs <a href="http://www.ruparupa.com/">www.ruparupa.com</a>. Tidak ada bagian-bagian dari situs yang dapat direproduksi, direkayasa, dipisahkan, diubah, didistribusikan, republished, ditampilkan, disiarkan, ditautkan (hyperlinked), direfleksikan (mirrored), disusun (framed), ditransfer atau dikirim dengan cara apapun atau disimpan/dipasang dalam suatu sistem pencarian informasi atau dipasang pada suatu server, sistem atau peralatan, tanpa izin tertulis sebelumnya dari kami atau dari pemilik hak cipta yang bersangkutan. Izin hanya akan diberikan kepada Anda untuk mengunduh, mencetak atau menggunakan konten situs ini untuk penggunaan pribadi dan non-komersial, dengan ketentuan Anda tidak mengubah konten kami atau pemilik hak cipta yang bersangkutan adalah pemegang semua/tiap hak cipta dan hak cipta kepemilikan lainnya yang terkandung dalam situs ini.</p>
<h3>3.Pembelian Produk</h3>
<p>Kami melakukan upaya terbaik untuk memastikan bahwa semua rincian, deskripsi dan harga yang muncul di situs ini adalah akurat, namun ada kasus di mana kesalahan mungkin terjadi. Jika kami menemukan kesalahan dalam harga setiap produk yang telah Anda pesan, kami akan memberitahu Anda dan memberikan pilihan untuk menegaskan kembali pesanan Anda dengan harga yang benar atau membatalkannya. Jika kami tidak dapat menghubungi Anda, maka kami akan melakukan pembatalan pesanan Anda. Jika Anda membatalkan pesanan Anda sebelum kami mengirimkannya kepada Anda, dan Anda telah membayar untuk pesanan tersebut, maka Anda akan menerima pengembalian dana.</p>
<h3>4. Pengiriman</h3>
<p>Kami bertujuan untuk mengantar produk Anda ke tempat pengiriman yang Anda minta sesuai dengan alamat yang tertera di halaman Pesanan Anda dan waktu pengiriman yang ditunjukkan oleh kami pada saat checkout pesanan Anda (diperbarui dalam konfirmasi pesanan) akan disesuaikan dengan waktu yang diperkirakan oleh rekan ekspedisi kami. Kami akan memberitahu Anda jika kami tidak mampu untuk memenuhi tanggal pengiriman seperti yang diperkirakan, tetapi kami tidak bertanggung jawab kepada Anda atas kerugian, kewajiban, biaya kerusakan, atau biaya lainnya yang timbul dari keterlambatan.</p>
<h3>5. Pertanyaan dan Keluhan</h3>
<p>Jika Anda memiliki pertanyaan atau keluhan, silakan hubungi kami melalui dengan memilih/klik "Hubungi Kami"atau dengan menghubungi Customer Service kami, ruparupa akan bekerja sama dengan para vendor untuk menjawab pertanyaan dan keluhan Anda.</p>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Syarat & Ketentuan')->setIdentifier('terms-conditions');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setCustomLayoutUpdateXml($navInfo)
        ->setRootTemplate('two_columns_left')
        ->save();


/* Info Karir */
$cmsPage = Mage::getModel('cms/page')->load('career', 'identifier');
$content =<<<EOF
<div class="banner">
<img src="{{skin url="images/sample/cms/infokarir.png"}}" alt="Info Karir" />
</div>

<div class="content-wrapper">
<h1>Info Karir</h1>

<!-- Start: Left Side -->
<div class="left">
<h2>Senior Merchandiser</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Monitoring achievement of sales target and inventory/stock every month</li>
    <li>Responsible for the promotion of department's products and review vendor list</li>
</ul>
<p>Qualifications :</p>
<ul>
    <li>Bachelor Degree in any major</li>
    <li>Have experience min. 4 years as Buyer/Merchandiser, preferably in retail business/buying agent/eCommerce</li>
    <li>Willing to travel</li>
    <li>Fluent in English (able to speak Mandarin is an advantage)</li>
</ul>
<hr/>

<h2>E-Commerce Developer</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Improve and manage E-commerce platform (ie. Magento, Prestashop)</li>
    <li>Conduct performance tuning and customization of E-commerce Solution platform</li>
    <li>Architect and built custom modules or other new functionality using object oriented PHP and E-commerce Solution APIs</li>
    <li>Providing regular development progress feedback and consistently meet the project deadlines</li>
    <li>Monitor day to day activities of the technical delivery team</li>
    <li>Provide regular project status reports to stakeholders and IT management</li>
    <li>Participate in ad-hoc IT/non IT related business project when necessary</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
    <li>Bachelor Degree in Information Technology, Science & Technology, Commerce or equivalent</li>
    <li>Have experience min. 4 years in E-commerce, Linux, PHP development or the related field</li>
    <li>Understanding JSON, JavaScript while XML is advantage</li>
    <li>Strong skill with network programming in PHP</li>
</ul>
<hr/>

<h2>Senior Search Engine Marketing</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Make sure the campaign running well and get the effective result</li>
    <li>Manage campaign expenses, staying on budget, estimating monthly costs and reconciling discrepancies</li>
    <li>Research and implement search engine optimization recommendations</li>
    <li>Responsible to improve/optimizing CPM, CPC, CPS/CPA</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
    <li>Bachelor Degree in any major</li>
    <li>Have experience min. 2 years as SEM, preferably in eCommerce business</li>
    <li>Manage more than 1000 keywords per month with website analytic tools</li>
    <li>Able to generate and maintain high quality keywords</li>
    <li>Have certified google adwords (Search Engine, Google Display Network, Mobile & Youtube)</li>
</ul>
<hr/>

<h2>Digital Marketing & Promotion Strategist</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Responsible for strategy and execution for website content marketing</li>
    <li>Implementation of effective promotional campaigns</li>
    <li>Create and developing monthly promotion for E-commerce</li>
    <li>Responsible for digital advertising of the website</li>
    <li>Provide financial projection</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
    <li>Bachelor Degree in any major</li>
    <li>Have experience min. 3 years in E-marketing, Marketing Promotion in E-commerce/Digital industry</li>
    <li>Experd knowledge about various marketing strategies, affiliate marketing, and online campaign management</li>
</ul>
<hr/>


<h2>Procurement Analyst</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
<li>Follow up purchase order (PO), analyze and price negotiation</li>
<li>Searching vendor and preparing contract documents.</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
<li>Bachelor Degree in any field</li>
<li>Have experience min. 2 years in the related field</li>
<li>Preferably have background in media agency/logistic/civil</li>
<li>Able to negotiate and work independently to meet deadlines</li>
</ul>
<hr/>


<h2>Procurement Analyst</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Handle and follow up the priority customer's complaint by phone</li>
    <li>Maintain good relationship with customer database, especially member</li>
    <li>Managing a team of call center agents, increasing performance and team capabilities</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
    <li>Bachelor Degree in any field</li>
    <li>Have experience min. 1 year as Call Center Team Leader</li>
    <li>Willing to work in shift time and also on public holiday</li>
</ul>

</div>
<!-- End: Left Side -->

<!-- Start: Right Side -->
<div class="right">

<h2>IT Networking Officer</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Implementing, maintaining, monitoring network security</li>
    <li>Installing, maintaining and supporting computer communication networks within the office</li>
    <li>Handle troubleshooting hardware/software, PC and server</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
    <li>Bachelor Degree in Information Technology/Computer Science or equivalent</li>
    <li>Have experience min. 2 years as IT Networking</li>
    <li>Understanding the basic fundamentals on designing network infrastructures and configuring network devices</li>
    <li>Good knowledge of network security (firewall), network protocol, network device (routers and switches), operating system</li>
    <li>Able to configure and maintain email server, troubleshooting hardware and software</li>
</ul>
<hr/>


<h2>Accounting Officer</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Handle daily administrative and financial task, prepares financial/accounting reports (balance sheet, profit & loss, cash flow, etc.)</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
    <li>Bachelor Degree in Accounting or equivalent</li>
    <li>Have experience min. 1 year as Finance/Accounting/Account Receivable/Account Payable</li>
    <li>Computer literate</li>
</ul>
<hr/>


<h2>Treasury Officer</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Administration of the disbursement files from various business units and corporate departments</li>
    <li>Journal creation for bank account transactions</li>
    <li>Assign GL codes, prepare for review by management, and ensure journals are uploaded into appropriate financial systems</li>
    <li>Perform bank reconciliations - reconciliation of the bank statement against the GL and reconciliation of any assigned clearing accounts</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
<li>Bachelor Degree in Economics, Finance/Accountancy/Banking or equivalent.</li>
<li>Have experience min. 1 year in the related field is required for this position.</li>
<li>GL accounting background</li>
<li>Ability to work in a team environment</li>
</ul>
<hr/>


<h2>Tax Officer</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Prepare monthly tax reporting, including running the electronic tax reporting program (named E-SPT)</li>
    <li>Reconcile all taxes between book and supporting documents on monthly basis</li>
    <li>Prepare/review tax returns (reporting), other accounting and Tax related function</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
<li>Bachelor Degree in Economics, Finance/Accountancy/Banking or equivalent</li>
    <li>Have experience min. 2 years in the related field</li>
    <li>Good knowledge in taxation, having certificate of “Brevet A/B”</li>
    <li>Proficient MS Application skills with some knowledge in SAP</li>
    <li>Familiar with Microsoft Office sofware: word, excell, power point, internet, acc software</li>
</ul>
<hr/>


<h2>Traffic Import Officer</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Responsible for all aspects of import shipments and monitoring document import</li>
    <li>Controlling payment and import cost</li>
    <li>Maintaining a good relationship with forwarder, shipping company and related government department</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
    <li>Bachelor Degree in any major</li>
    <li>Have experience min. 1 year in the related field</li>
    <li>Understanding import and customs regulation</li>
    <li>Fluent in English (able to speak Mandarin is an advantage)</li>
</ul>
<hr/>


<h2>Sales Data Analyst</h2>
<p><strong>Responsibilities :</strong></p>
<ul>
    <li>Create, monitor, and analyze daily, weekly, and monthly data of regional sales report.</li>
</ul>
<p><strong>Qualifications :</strong></p>
<ul>
    <li>Bachelor Degree in Economics, Engineering, Business Management, or equivalent</li>
    <li>Have experience min. 1 year as Data Analyst.</li>
    <li>Interest in administrative duties.</li>
</ul>

</div>
<!-- End: Right Side -->

</div>

<div class="content-wrapper">
    <p style="text-align:center;">
    Send your resume and new photo to: <strong><a href="mailto:recruitment@kawanlamacorp.com" target="_top">recruitment@kawanlamacorp.com</a></strong> <br/>
    or by post to HRD Kawan Lama Group, <br/>
    <strong>Kawan Lama Building 6th floor, Jl. Puri Kencana No 1 Meruya Jakarta Barat 11610</strong>
    </p>
</div>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Info Karir')->setIdentifier('career');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setCustomLayoutUpdateXml($navInfo)
        ->setRootTemplate('two_columns_left')
        ->save();


/* faq */
$cmsPage = Mage::getModel('cms/page')->load('faq', 'identifier');
$content =<<<EOF
<h1>F.A.Q.</h1>
<div class="accordion">

<div class="block">
<div class="block-title">
1. Apakah www.ruparupa.com?
</div>
<div class="block-content">
Ruparupa merupakan toko online pertama dari Kawan Lama Group yang dapat diakses 7 x 24 jam melalui situs. Produk-produk yang dijual disitus ini dapat Anda jumpai di beberapa toko sister company kami, seperti Ace Hardware, Informa, Toys Kingdom.
</div>
</div>
 
<div class="block">
<div class="block-title">
2. Bagaimana cara berbelanja di www.ruparupa.com?
</div>

<div class="block-content">
Untuk mempermudah proses belanja, Anda dapat mendaftarkan diri untuk memiliki akun dengan mengklik “Daftar” pada halaman awal dan data Anda akan disimpan ketika melakukan pembelanjaan ulang, selain itu Anda juga dapat mendapatkan info, diskon dan penawaran spesial dari kami melalui e-newsletter yang akan dikirimkan ke email Anda. Untuk memperbaharui data pada akun, Anda dapat mengakses halaman “Akun Saya” pada situs kami.
</div>
</div>

<div class="block">
<div class="block-title">
3. Bagaimana cara menggunakan gift card?
</div>

<div class="block-content">
Gift Card adalah kode kupon yang dapat digunakan seperti alat pembayaran pada umumnya. Anda bisa mendapatkannya dengan menukarkan ACE/ Informa/ Toys Kingdom point rewards atau sebagai loyalty gift dari Ruparupa.
</div>
</div>

<div class="block">
<div class="block-title">
4. Apakah yang yang dimaksud dengan handling charges?
</div>

<div class="block-content">
Handling charges adalah biaya tambahkan yang akan dibebankan kepada Anda karena adanya perlakuan khusus dalam pengiriman, kemasan, perbedaan harga tiap daerah dan servis yang akan diberikan kepada Anda.
</div>
</div>

<div class="block">
<div class="block-title">
5. Apa saja proses pembayaran yang dapat digunakan di www.ruparupa.com?
</div>

<div class="block-content">
- Kartu kredit (Visa dan Master Card) untuk seluruh Bank
- Cicilan 0%
*masa tenor dan dukungan bank dapat berubah sewaktu-waktu tergantung pada masa promosi
- Bank Transfer dengan virtual account:
a. BCA dan Mandiri: Bebas biaya transfer
b. Bank Lain: Biaya transfer Rp6.500 (sesuai peraturan Bank Indonesia)
</div>
</div>

<div class="block">
<div class="block-title">
6. Bagaimana cara konfirmasi pembayaran?
</div>

<div class="block-content">
Untuk kenyamanan Anda, konfirmasi pembayaran tidak dilakukan secara manual, tetapi Ruparupa yang akan mengkonfirmasi status pembayaran Anda.
</div>
</div>

<div class="block">
<div class="block-title">
7. Berapa lama estimasi pengiriman pesanan saya?
</div>

<div class="block-content">
Produk yang Anda pesan akan sampai dalam waktu 2-5 hari untuk wilayah Jabodetabek dan 4-14 hari untuk wilayah di luar Jabodetabek.
</div>
</div>

<div class="block">
<div class="block-title">
8.Bagaimana cakupan layanan pengiriman www.ruparupa.com?
</div>

<div class="block-content">
Ruparupa melayani pengiriman ke seluruh wilayah Indonesia.
</div>
</div>

<div class="block">
<div class="block-title">
9.Bagaimana cara melakukan pengecekkan status pesanan saya?
</div>

<div class="block-content">
<ul>
<li>Anda dapat melakukan pengecekan status pesanan pada kanan atas dari situs atau dari menu “Akun Saya” dengan memasukkan nomor pesanan.</li>
<li>Ruparupa akan mengirimkan email pemberitahuan dari update pesanan Anda</li>
</ul>
</div>
</div>

<div class="block">
<div class="block-title">
10. Bagaimana jika produk yang saya terima tidak sesuai dengan pesanan saya?
</div>

<div class="block-content">
Jika produk yang diterima tidak sesuai, maka customer dapat melakukan pengajuan retur. Berikut langkah yang harus dilakukan :
<ol>
<li>Foto produk yang tidak sesuai dengan pesanan</li>
<li>Upload di form elektronik yang sudah disediakan</li>
<li>Tunggu persetujuan dari pihak Ruparupa melalui email/telepon</li>
<li>Jika sudah disetujui, kemas kembali barang seperti semula, dan kirimkan ke Distribution Center kami dengan alamat :
    <address>
    Distribution Center  PT.Omni Digitama Internusa<br/>
    Komplek Pergudangan Kawan Lama<br/>
    Jl. Industri Selatan Blok PP No. 4 Jababeka II<br/>
    Desa pasir Sari, Kecamatan Cikarang Selatan –<br/>
    Bekasi 17550
    </address>
</li>
<li>5. Scan bukti pengiriman untuk pengembalian biaya pengiriman <br/>
    * Biaya yang ditanggung sesuai dengan rate JNE<br/>
    * Untuk pengembalian barang karena keputusan customer, pihak Ruparupa tidak wajib untuk melakukan penggantian biaya kirim kembali.
</li>
<li>6. Setelah barang sampai di Distribution Center, Ruparupa akan memproses penukaran barang dan pengembalian biaya pengiriman</li>
</ol>
</div>
</div>

<div class="block">
<div class="block-title">
11. Apakah produk yang sudah dibeli dapat ditukar atau dikembalikan?
</div>

<div class="block-content">
Barang yang sudah dibeli dapat ditukar dan dikembalikan dengan syarat dan ketentuan yang berlaku yang selengkapnya dapat dibaca pada laman Pengembalian Barang
</div>
</div>

<div class="block">
<div class="block-title">
12. Apa layanan Garansi dan servis yang ditawarkan oleh www.ruparupa.com?
</div>

<div class="block-content">
Layanan raransi dan servis, sesuai dengan syarat dan ketentuan dari penyedia barang, dan dapat berbeda-beda untuk tiap barang. Informasi ini kami cantumkan dalam halaman produk bagian spesifikasi dan pada halaman detail order setelah Anda bertransaksi.
</div>
</div>

<div class="block">
<div class="block-title">
13. Bagaimana saya dapat menghubungi customer service www.ruparupa.com?
</div>

<div class="block-content">
Anda dapat menghubungi customer service Ruparupa pada :<br/>
Senin - Jumat (kecuali hari libur nasional)<br/>
Pukul 09:00 - 17:00 WIB<br/>
<br/>
Live Chat pada situs www.ruparupa.com<br/>
No. telp. : 021 - 582 9191<br/>
email : <a href="mailto:help@www.ruparupa.com">help@www.ruparupa.com</a><br/>
</div>
</div>

</div>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('FAQ')->setIdentifier('faq');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setCustomLayoutUpdateXml($navCustomer)
        ->setRootTemplate('two_columns_left')
        ->save();

/* Cara Berbelanja */
$cmsPage = Mage::getModel('cms/page')->load('how-to-shop', 'identifier');
$content =<<<EOF
<h1>Cara Berbelanja</h1>
<table>
<tbody>
    <tr>
        <td>
            <img src="{{skin url="images/sample/cms/how-to-shop-1.png"}}" />
        </td>
        <td>
            <span>1</span>
        </td>
        <td>
            <h3>Belanja</h3>
            <ol>
                <li>Pilih produk yang Anda inginkan, berikut dengan warna, kuantitas, dan ukuran.</li>
                <li>Pilih tombol “Add to Cart” untuk memasukkan produk yang Anda inginkan ke troli belanja.</li>
                <li>Jika sudah selesai berbelanja, klik tombol “Check Out” pada troli belanja.</li>
                <li>Periksa rincian pada keranjang belanja Anda, lalu lanjutkan ke pembayaran</li>
            </ol>
        </td>
    </tr>
    <tr>
        <td>
            <img src="{{skin url="images/sample/cms/how-to-shop-2.png"}}" />
        </td>
        <td>
            <span>2</span>
        </td>
        <td>
            <h3>Pembayaran</h3>
            <ol>
                <li>Pilih produk yang Anda inginkan, berikut dengan warna, kuantitas, dan ukuran.</li>
                <li>Pilih pilihan Anda: </li>
                    <ul>
                        <li>Check out as guest</li>
                        <li>Register & Check out</li>
                    </ul>
                <li>Isi data diri Anda disertai alamat pengiriman</li>
                <li>Pilih metode pembayaran yang diinginkan</li>
                    <ul>
                        <li>Credit card (Visa / Master Card)</li>
                        <li>Transfer Virtual Account melalui BCA, Mandiri, Bank Permata/Alto</li>
                        <li>Cicilan 0%</li>
                    </ul>
                <li>Lakukan pembayaran sesuai dengan metode yang dipilih.</li>
            </ol>
        </td>
    </tr>
    <tr>
        <td>
            <img src="{{skin url="images/sample/cms/how-to-shop-3.png"}}" />
        </td>
        <td>
            <span>3</span>
        </td>
        <td>
            <h3>Terima Produk</h3>
            <ol>
                <li>Pesanan Anda akan tiba dalam waktu 2-5 hari untuk wilayah Jabodetabek dan 4-14 hari untuk wilayah luar Jabodetabek</li>
            </ol>
        </td>
    </tr>
</tbody>
</table>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Cara Berbelanja')->setIdentifier('how-to-shop');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setCustomLayoutUpdateXml($navCustomer)
        ->setRootTemplate('two_columns_left')
        ->save();

/* Pembayaran */
$cmsPage = Mage::getModel('cms/page')->load('payment', 'identifier');
$content =<<<EOF
<h1>Pembayaran</h1>

<h3>1. Kartu Kredit</h3>
<p>Pembayaran dengan kartu kredit berlaku untuk semua kartu kredit berlogo visa dan mastercard.</p>
<p><i>Langkah-langkah pembayaran dengan kartu kredit:</i></p>
<ol>
<li>Pilih pembayaran melalui kartu kredit.</li>
<li>Masukkan nomor kartu kredit, expiration date &amp; CVV dari kartu kredit yang Anda gunakan. (CVV adalah 3 digit nomor yang tercantum di belakang kartu kredit Anda)</li>
<li>Pastikan detail pembayaran Anda benar &amp; lanjutkan ke &ldquo;<b>Proses Pembayaran&rdquo;</b>.</li>
<li>Jika ditentukan oleh Bank Penerbit, One Time Password (OTP) akan dikirimkan oleh Bank Penerbit kartu kredit Anda ke nomor ponsel Anda yang teregistrasi dengan kartu kredit yang Anda gunakan.</li>
<li>Masukkan OTP yang Anda dapat ke halaman 3D Secure.</li>
<li>Pembayaran Anda dengan kartu kredit selesai.</li>
<li>Catatan: Kami memproses pembayaran kartu kredit secara aman dan terpercaya melalui Veritrans, kami tidak menyimpan informasi apapun berkenaan dengan kartu kredit Anda.</li>
</ol>

<h2>2. Virtual Account</h2>
<p>Pembayaran dengan virtual account dapat dilakukan dengan cara transfer ke nomor rekening virtual yang telah ditentukan. Cara pembayaran ini berlaku untuk nasabah Bank BCA dan Mandiri. Sedangkan untuk nasabah bank lainnya bisa melakukan transfer ke nomor virtual account Bank Permata dengan dikenakan biaya Rp6.500.</p>
<p><b><i>a. Pembayaran Melalui Virtual Account BCA</i></b></p>
<ol>
<li>Pada menu utama, pilih &ldquo;<b>Transaksi Lainnya&rdquo;</b></li>
<li>Pilih &ldquo;<b>Transfer&rdquo;</b></li>
<li>Masukkan jumlah tagihan yang akan Anda bayar secara lengkap (Pembayaran dengan jumlah tidak sesuai akan otomatis ditolak)</li>
<li>Masukkan 16 digit no. rekening pembayaran lalu tekan &ldquo;<b>Benar&rdquo;</b></li>
<li>Pada halaman konfirmasi transfer akan muncul jumlah yang dibayarkan, nomor rekening, dan nama merchant. Jika informasi telah sesuai tekan &ldquo;<b>Benar&rdquo;</b></li>
</ol>
<p><b><i>b. Pembayaran Melalui Virtual Account MANDIRI</i></b></p>

<div class="accordion">
    <div class="block">
        <div class="block-title">
            <b>Pembayaran melalui ATM Mandiri</b>
        </div>
        <div class="block-content">
        <ol>
            <li>Masukkan PIN Anda</li>
            <li>Pada menu utama pilih menu &ldquo;<b>Pembayaran&rdquo;</b> kemudian pilih menu &ldquo;<b>Multi Payment&rdquo;</b></li>
            <li>Masukkan kode perusahaan, dalam hal ini adalah <b>70012</b> kemudian tekan tombol &ldquo;<b>Benar&rdquo;</b></li>
            <li>Masukkan kode pembayaran Anda, dalam hal ini adalah <b>xxxxx (tercantum waktu Checkout) </b>kemudian Anda akan mendapatkan detail pembayaran Anda</li>
            <li>Konfirmasi pembayaran Anda</li>
        </ol>
        </div>
    </div>
    <div class="block">
        <div class="block-title">
            <b>Cara membayar melalui Internet Banking Mandiri</b>
        </div>
        <div class="block-content">
            <ol>
                <li>Login ke Internet Banking Mandiri <a href="https://ib.bankmandiri.co.id/">https://ib.bankmandiri.co.id/</a></li>
                <li>Di Menu Utama silakan pilih <b>&ldquo;Bayar&rdquo;</b> kemudian pilih <b>&ldquo;Multi Payment&rdquo;</b></li>
                <li>Pilih akun Anda di <b>&ldquo;Dari Rekening&rdquo;</b>, kemudian di <b>&ldquo;Penyedia Jasa&rdquo;</b> pilih Veritrans</li>
                <li>Masukkan kode pembayaran Anda, dalam hal ini adalah <b>xxxxx (tercantum waktu Checkout) </b>dan klik <b>&ldquo;Lanjutkan&rdquo;</b></li>
                <li>Konfirmasi pembayaran Anda menggunakan Mandiri Token.</li>
            </ol>
        </div>
    </div>
</div>

<p><b><i>c. Pembayaran Melalui Virtual Account Bank Permata dan ATM Bersama</i></b></p>
<div class="accordion">
    <div class="block">
        <div class="block-title">
            <b>Pembayaran Melalui Jaringan BCA/ ATM PRIMA</b>
        </div>
        <div class="block-content">
            <ol>
            <li>Pada menu utama, pilih &ldquo;<b>Transaksi Lainnya&rdquo;</b></li>
            <li>Pilih &ldquo;<b>Transfer&rdquo;</b></li>
            <li>Pilih &ldquo;<b>Rekening Bank Lain&rdquo;</b></li>
            <li>Masukkan nomor&nbsp;<b>013</b>&nbsp;(kode Bank Permata) lalu tekan &ldquo;<b>Benar&rdquo;</b></li>
            <li>Masukkan jumlah tagihan yang akan Anda bayar secara lengkap&nbsp;<b>(Pembayaran dengan jumlah tidak sesuai akan otomatis ditolak)</b></li>
            <li>Masukkan 16 digit No.Rekening pembayaran lalu tekan &ldquo;<b>Benar&rdquo;</b></li>
            <li>Pada halaman konfirmasi transfer akan muncul jumlah yang dibayarkan, nomor rekening, dan nama merchant. Jika informasi telah sesuai tekan &ldquo;<b>Benar&rdquo;</b></li>
            </ol>
        </div>
    </div>

    <div class="block">
        <div class="block-title">
            <b>Pembayaran Melalui Jaringan Permata/Alto</b>
        </div>
        <div class="block-content">
            <ol>
            <li>Pada menu utama, pilih &ldquo;<b>Transaksi Lainnya&rdquo;</b></li>
            <li>Pilih &ldquo;<b>Transaksi Pembayaran&rdquo;</b></li>
            <li>Pilih &ldquo;<b>Lain-lain&rdquo;</b></li>
            <li>Pilih &ldquo;<b>Pembayaran Virtual Account&rdquo;</b></li>
            <li>Masukkan 16 digit no.rekening pembayaran lalu tekan &ldquo;<b>Benar&rdquo;</b></li>
            <li>Pada halaman konfirmasi transfer akan muncul jumlah yang dibayarkan, nomor rekening, dan nama merchant. Jika informasi telah sesuai tekan &ldquo;<b>Benar&rdquo;</b></li>
            <li>Pilih rekening pembayaran Anda dan tekan &ldquo;<b>Benar&rdquo;</b></li>
            </ol>
        </div>
    </div>

    <div class="block">
        <div class="block-title">
            <b>Pembayaran melalui ATM Bersama</b>
        </div>
        <div class="block-content">
            <ol>
            <li>Pada menu utama, pilih &ldquo;<b>Transaksi Lainnya&rdquo;</b></li>
            <li>Pilih &ldquo;<b>Transfer&rdquo;</b></li>
            <li>Pilih &ldquo;<b>Antar Bank Online&rdquo;</b></li>
            <li>Masukkan nomor 013 dan 16 digit No.Rekening</li>
            <li>Masukkan jumlah tagihan yang akan Anda bayar secara lengkap <b>(pembayaran dengan jumlah tidak sesuai akan otomatis ditolak)</b></li>
            <li>Kosongkan No.Referensi, lalu tekan <b>&ldquo;Benar&rdquo;</b></li>
            <li>Pada halaman konfirmasi transfer akan muncul jumlah yang dibayarkan, nomor rekening, dan nama merchant. Jika informasi telah sesuai tekan &ldquo;<b>Benar&rdquo;</b></li>
            </ol>
        </div>
    </div>
</div>
EOF;

if(!$cmsPage->getId()){
    $cmsPage->setTitle('Pembayaran')->setIdentifier('payment');
}

$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setCustomLayoutUpdateXml($navCustomer)
        ->setRootTemplate('two_columns_left')
        ->save();


/* 404 page */
$cmsPage = Mage::getModel('cms/page')->load('no-route', 'identifier');
$content =<<<EOF
<h1>SORRY</h1>
<h4 class="subtitle">There's Nothing Here</h4>
<img src="{{skin url="images/sample/cms/dot-blue-grey-orange.png"}}" style="margin-bottom:5px;"/>

<p>Halaman yang Anda cari tidak dapat ditemukan.<br/>
Bila Anda ingin mencari produk, silakan gunakan kolom pencarian kami.</p>

{{block type="core/template" template="catalogsearch/form.mini.phtml"}}

<p>Atau klik <a href="javascript:void(0)" onclick="window.history.back()">disini</a> untuk kembali ke halaman sebelumnya</p>

{{block type="core/template" template="icube/cms/404_page/category-list.phtml"}}
EOF;
if(!$cmsPage->getId()){
    $cmsPage->setTitle('404 Not Found 1')->setIdentifier('no-route');
}
$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save(); 

/* remove unsed block in contacts */
$cmsPage = Mage::getModel('cms/page')->load('contact-map', 'identifier');
if($cmsPage->getId()){
    $cmsPage->delete();
}
$cmsPage = Mage::getModel('cms/page')->load('contact-detail', 'identifier');
if($cmsPage->getId()){
    $cmsPage->delete();
}

$installer->endSetup();
