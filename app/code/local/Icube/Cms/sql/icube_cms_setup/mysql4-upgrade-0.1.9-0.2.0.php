<?php
/*
 * - Contact page
 */
$installer = $this;
$installer->startSetup();

/* Contact map */
$cmsBlock = Mage::getModel('cms/block')->load('contact-map', 'identifier');
$content =<<<EOF
<iframe src="https://maps.google.com/maps?q=-8.6402933,115.1963132&hl=es;z=14&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Contact - Map')->setIdentifier('contact-map');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();

/* Contact detail */
$cmsBlock = Mage::getModel('cms/block')->load('contact-detail', 'identifier');
$content =<<<EOF
<ul>
<li><img src="{{skin url="images/sample/contact/icon-contact-phone.png"}}" alt="phone"/> (021) 203 2032 </li>
<li><img src="{{skin url="images/sample/contact/icon-contact-email.png"}}" alt="email"/> rupa@rupa.com </li>
</ul>
EOF;

if(!$cmsBlock->getId()){
	$cmsBlock->setTitle('Contact - Detail')->setIdentifier('contact-detail');
}

$cmsBlock->setStores(array(0))
		->setContent($content)
		->setIsActive(1)
		->save();


$installer->endSetup();