<?php
/*
 * - Payment iBanking & e-money
 */
$installer = $this;
$installer->startSetup();

/* Bank Transfer Account List */
$cmsBlock = Mage::getModel('cms/block')->load('payment-ibanking_emoney', 'identifier');

$content =<<<EOF
<p>
    <img src="{{skin url="images/sample/checkout/payment-cimb_click.png"}}" alt="CIMB Click" title="CIMB Click" />
    <img src="{{skin url="images/sample/checkout/payment-mandiri_clickpay.png"}}" alt="Mandiri Clickpay" title="Mandiri Clickpay" />
    <img src="{{skin url="images/sample/checkout/payment-telkomsel_cash.png"}}" alt="Telkomsel Cash" title="Telkomsel Cash" />
    <img src="{{skin url="images/sample/checkout/payment-xl_tunai.png"}}" alt="XL Tunai" title="XL Tunai" />
    <img src="{{skin url="images/sample/checkout/payment-mandiri_ecash.png"}}" alt="Mandiri E-Cash" title="Mandiri E-Cash" />
</p>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setTitle('Payment - Internet Banking & E-Money');
}

$cmsBlock->setStores(array(0))
        ->setIdentifier('payment-ibanking_emoney')
        ->setContentHeading('Payment - Internet Banking & E-Money')
        ->setContent($content)
        ->setIsActive(1)
        ->save();


$installer->endSetup();