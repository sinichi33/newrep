<?php
/*
 * - Update Social Connect on Footer
 */
$installer = $this;
$installer->startSetup();

/* footer - Socmed links */
$cmsBlock = Mage::getModel('cms/block')->load('footer-socmed_links', 'identifier');
$content =<<<EOF
<div class="block socmed">
    <div class="block-title">
        Terhubung Dengan Kami
    </div>
    <div class="block-content">
        <ul>
            <li><a href="https://twitter.com/RupaRupaCom" class="twitter" target="_blank"></a></li>
            <li><a href="https://www.facebook.com/ruparupacom" class="facebook" target="_blank"></a></li>
            <li><a href="https://www.instagram.com/ruparupacom/" class="instagram" target="_blank"></a></li>
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


$installer->endSetup();