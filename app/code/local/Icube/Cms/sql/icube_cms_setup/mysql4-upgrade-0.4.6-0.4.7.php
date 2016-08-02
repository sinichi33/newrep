<?php
/* 
 * - update 404
 */

$installer = $this;
$installer->startSetup();

/* 404 page */
$cmsPage = Mage::getModel('cms/page')->load('no-route', 'identifier');
$content =<<<EOF
<h1>SORRY</h1>
<h4 class="subtitle">There's Nothing Here</h4>
<p><img style="margin-bottom: 5px;" alt="" src="{{skin url="images/sample/cms/dot-blue-grey-orange.png"}}" /></p>
<p>Halaman yang Anda cari tidak dapat ditemukan.<br /> <span class="mob-hide">Bila Anda ingin mencari produk, silakan gunakan kolom pencarian kami.</span></p>
<p>{{block type="core/template" template="catalogsearch/form.mini.phtml"}}</p>
<p class="mob-hide">Atau klik <a onclick="window.history.back()" href="javascript:void(0)">disini</a> untuk kembali ke halaman sebelumnya</p>
<p>{{block type="core/template" template="icube/cms/404_page/category-list.phtml"}}</p>
<p><a onclick="window.location.href('{{store url}}')" href="javascript:void(0)" class="mob-only button">Kembali ke Halaman Utama</a></p>
EOF;
if(!$cmsPage->getId()){
    $cmsPage->setTitle('404 Not Found 1')->setIdentifier('no-route');
}
$cmsPage->setStores(array(0))
        ->setContent($content)
        ->setIsActive(1)
        ->setRootTemplate('one_column')
        ->save(); 

$installer->endSetup();
