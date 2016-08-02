<?php
/*
 * - Flash sale: set available for all stores
 * - Mobile: footer
 * - Mobile: home banner
 * - Mobile: home - inspiration
 * - Mobile: Home - Company
 */
$installer = $this;
$installer->startSetup();

$store = Mage::getModel('core/store')->load('mobile', 'code');
$model = Mage::getModel('cms/block');

/* Flash sale: set all cms store view as default */
$storeDefault = Mage::getModel('core/store')->load('default', 'code');
$collection = $model->getCollection()
        ->addStoreFilter($storeDefault->getId())
        ->addFieldToFilter('store_id', $storeDefault->getId());

foreach ($collection as $item) {
    // var_dump($item->load()->getStoreId());
    $item->setStores(array(0))->save();
}

/* Mobile Footer */
Mage::getModel('cms/block')->load('m-footer', 'identifier')->delete();
$content =<<<EOF
<div class="call-center">
    <table>
        <tbody>
            <tr>
                <td>
                    <img src="{{skin url="images/sample/footer/logo-callcenter.png"}}" alt="Call Center" />
                </td>
                <td>
                    <p>Call Center<br/>
                    <strong>(021) 5552234</strong></p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="footer-link">
    <ul>
        <li>
            <a href="{{store url="about-us"}}">Info Ruparupa</a>
        </li>
        <li>
            <a href="{{store url="faq"}}">Layanan Konsumen</a>
        </li>
    </ul>
</div>
EOF;

$collection = $model->getCollection()
        ->addFieldToFilter('identifier', 'm-footer')
        ->addStoreFilter($store->getId())
        ->addFieldToFilter('store_id', $store->getId());
$cmsBlock = $collection->getFirstItem();

if (!$cmsBlock || !$cmsBlock->getBlockId()) {
    $cmsBlock->setStores(array($store->getId()))->setIdentifier('m-footer');
}

$cmsBlock->setContent($content)
    ->setStores(array($store->getId()))
    ->setTitle('Footer (Mobile)')
    ->save();


/* Mobile - Home Banner */
$content =<<<EOF
<ul>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-mobile-banner.png"}}" alt="Sample Banner" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-mobile-banner.png"}}" alt="Sample Banner" /></a></li>
<li><a href="#"><img src="{{skin url="images/sample/home/sample-mobile-banner.png"}}" alt="Sample Banner" /></a></li>
</ul>
EOF;

$collection = $model->getCollection()
        ->addFieldToFilter('identifier', 'home-banner')
        ->addStoreFilter($store->getId())
        ->addFieldToFilter('store_id', $store->getId());
$cmsBlock = $collection->getFirstItem();

if (!$cmsBlock || !$cmsBlock->getBlockId()) {
    $cmsBlock->setStores(array($store->getId()))->setIdentifier('home-banner');
}

$cmsBlock->setContent($content)
    ->setStores(array($store->getId()))
    ->setTitle("Home Banner (Mobile)")
    ->save();


/* Mobile - home - inspiration */
$content =<<<EOF
{{block type="catalog/product_list" category_url="inspirations" template="icube/homepage/set-of-inspiration.phtml"}}
EOF;

$collection = $model->getCollection()
        ->addFieldToFilter('identifier', 'home-set_of_inspiration')
        ->addStoreFilter($store->getId())
        ->addFieldToFilter('store_id', $store->getId());
$cmsBlock = $collection->getFirstItem();

if (!$cmsBlock || !$cmsBlock->getBlockId()) {
    $cmsBlock->setStores(array($store->getId()))->setIdentifier('home-set_of_inspiration');
}

$cmsBlock->setContent($content)
    ->setStores(array($store->getId()))
    ->setTitle("Home - Set of Inspiration (Mobile)")
    ->save();


/* Home - Mobile - Company */
$content =<<<EOF
<div class="company">
    <div class="banner">
        <a href="{{store url=""}}/company/ace.html"><img src={{skin url="images/sample/home/mobile-banner-ace.png"}} alt="ACE" /></a>
    </div>
    <div class="products">
        {{block type="catalog/product_list" category_url="ace" template="icube/homepage/company-products.phtml"}}
    </div>
</div>

<div class="company">
    <div class="banner">
        <a href="{{store url=""}}/company/informa.html"><img src={{skin url="images/sample/home/mobile-banner-informa.png"}} alt="Informa" /></a>
    </div>
    <div class="products">
        {{block type="catalog/product_list" category_url="informa" template="icube/homepage/company-products.phtml"}}
    </div>
</div>

<div class="company">
    <div class="banner">
        <a href="{{store url=""}}/company/toys-kingdom.html"><img src={{skin url="images/sample/home/mobile-banner-toyskingdom.png"}} alt="Toys Kingdom" /></a>
    </div>
    <div class="products">
        {{block type="catalog/product_list" category_url="toys-kingdom" template="icube/homepage/company-products.phtml"}}
    </div>
</div>
EOF;

$collection = $model->getCollection()
        ->addFieldToFilter('identifier', 'home-company')
        ->addStoreFilter($store->getId())
        ->addFieldToFilter('store_id', $store->getId());
$cmsBlock = $collection->getFirstItem();

if (!$cmsBlock || !$cmsBlock->getBlockId()) {
    $cmsBlock->setStores(array($store->getId()))->setIdentifier('home-company');
}

$cmsBlock->setContent($content)
    ->setStores(array($store->getId()))
    ->setTitle("Home - Company")
    ->save();


$installer->endSetup();
