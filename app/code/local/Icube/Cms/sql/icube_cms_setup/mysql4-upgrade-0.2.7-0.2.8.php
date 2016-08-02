<?php
/*
 * - Payment Bank Transfer Account List
 */
$installer = $this;
$installer->startSetup();

/* Bank Transfer Account List */
$cmsBlock = Mage::getModel('cms/block')->load('payment-bank_transfer_accounts', 'identifier');

$content =<<<EOF
<p>
    <img src="{{skin url="images/sample/checkout/payment-transfer-bca.png"}}" alt="BCA" title="BCA" style="float:left;margin-right:25px;" />
    <span style="float:left">
    No. Rek: 123-123-1345<br/>
    A/N: PT Omni Digitama Internusa
    </span>
</p>
<p>
    <img src="{{skin url="images/sample/checkout/payment-transfer-mandiri.png"}}" alt="Mandiri" title="Mandiri" style="float:left;margin-right:25px;" />
    <span style="float:left;">
    No. Rek: 123-123-1345<br/>
    A/N: PT Omni Digitama Internusa
    </span>
</p>
<p>
    <img src="{{skin url="images/sample/checkout/payment-transfer-bni.png"}}" alt="BNI" title="BNI" style="float:left;margin-right:25px;" />
    <span style="float:left;">
    No. Rek: 123-123-1345<br/>
    A/N: PT Omni Digitama Internusa
    </span>
</p>
<p>
    <img src="{{skin url="images/sample/checkout/payment-transfer-bri.png"}}" alt="BRI" title="BRI" style="float:left;margin-right:25px;" />
    <span style="float:left;">
    No. Rek: 123-123-1345<br/>
    A/N: PT Omni Digitama Internusa
    </span>
</p>
<p>
    <img src="{{skin url="images/sample/checkout/payment-transfer-danamon.png"}}" alt="Danamon" title="Danamon" style="float:left;margin-right:25px;" />
    <span style="float:left;">
    No. Rek: 123-123-1345<br/>
    A/N: PT Omni Digitama Internusa
    </span>
</p>
EOF;

if(!$cmsBlock->getId()){
    $cmsBlock->setTitle('Payment - Bank Transfer Accounts');
}

$cmsBlock->setStores(array(0))
        ->setIdentifier('payment-bank_transfer_accounts')
        ->setContentHeading('Payment - Bank Transfer Accounts')
        ->setContent($content)
        ->setIsActive(1)
        ->save();


$installer->endSetup();