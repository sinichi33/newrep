<?php

class Icube_Rma_Block_Return_Create extends Enterprise_Rma_Block_Return_Create
{
    protected $_renderBlockTypes    = array();

    public function _construct()
    {
        $order = Mage::registry('current_order');
        $this->setOrder($order);

        $items = Mage::helper('icube_rma')->getOrderItems($order);
        $this->setItems($items);

        $session = Mage::getSingleton('core/session');
        $formData = $session->getRmaFormData(true);
        if (!empty($formData)) {
            $data = new Varien_Object();
            $data->addData($formData);
            $this->setFormData($data);
        }
        $errorKeys = $session->getRmaErrorKeys(true);
        if (!empty($errorKeys)) {
            $data = new Varien_Object();
            $data->addData($errorKeys);
            $this->setErrorKeys($data);
        }
    }

}
