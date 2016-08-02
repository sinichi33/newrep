<?php
$installer = $this;
$installer->startSetup();

$addresses = Mage::getModel('customer/address')->getCollection()->addAttributeToSelect('*');
foreach($addresses as $address){
    $kodejalur = $address->getData('kodejalur');
    $kodekecamatan = $address->getData('kodekecamatan');
    if(is_null($kodejalur) || is_null($kodekecamatan)){
        $arrayKode = Mage::helper('city')->getKode($address->getData('city'),$address->getData('kecamatan'));
        $address->setData('kodejalur', $arrayKode['kodejalur'])
                ->setData('kodekecamatan', $arrayKode['kodekecamatan'])
                ->save();
    }
}

$installer->endSetup();
