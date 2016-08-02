<?php

class Icube_Vpaymentva_Block_Info_Vpaymentva extends Mage_Payment_Block_Info
{
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $transport = new Varien_Object(array());
        $transport = parent::_prepareSpecificInformation($transport);
        if($this->getInfo()->getVanumber()) {
            $transport->addData(array(
                Mage::helper('vpaymentva')->__('Bank') => $this->getInfo()->getVabank(),
                Mage::helper('vpaymentva')->__('VA Account Number') => $this->getInfo()->getVanumber(),
            ));
        }
        if($this->getInfo()->getBillercode()) {
            $transport->addData(array(
                Mage::helper('vpaymentva')->__('Bank') => $this->getInfo()->getVabank(),
                Mage::helper('vpaymentva')->__('Biller code') => $this->getInfo()->getBillercode(),
                Mage::helper('vpaymentva')->__('Bill key') => $this->getInfo()->getBillkey(),
            ));
        }
        return $transport;
    }
}
