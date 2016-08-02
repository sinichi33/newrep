<?php

class Icube_Vpaymentins_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function sentReqVtrans($comidity)
    {
        $json       = json_encode($comidity);
        $server_key = Mage::getStoreConfig('payment/vpaymentins/server');
        $server_key = base64_encode($server_key);
//        $server_key = base64_encode($this->getServerKey());
//        $url        = $this->getUrl();
        $url        = Mage::getStoreConfig('payment/vpaymentins/vurl');
        $ch         = curl_init($url);
        //curl_setopt($ch, CURLOPT_USERPWD, $server_key.':');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . $server_key
        ));

        $result = curl_exec($ch);

        //Mage::log($json,null,'VTjson.log',true);
        return json_decode($result);
    }

    public function sentReqVtransAuthorize($comidity)
    {
        $json       = json_encode($comidity);
        $server_key = Mage::getStoreConfig('payment/vpaymentins/server');
        $server_key = base64_encode($server_key);
        $url        = Mage::getStoreConfig('payment/vpaymentins/authorize_url');
        $ch         = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . $server_key
        ));

        $result = curl_exec($ch);

        //Mage::log($json,null,'VTjson.log',true);
        return json_decode($result);
    }

    public function sendCancelTransaction($order_id)
    {
        $server_key = Mage::getStoreConfig('payment/vpaymentins/server');
        $server_key = base64_encode($server_key);
        $url        = Mage::getStoreConfig('payment/vpaymentins/cancel_url');
        $url = str_replace("{id}",$order_id,$url);
//        Mage::log($url,null,'url.log',true);
        $ch         = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . $server_key
        ));

        $result = curl_exec($ch);

//        Mage::log(json_decode($result),null,'sendCancelTransaction.log',true);
        return json_decode($result);
    }

    public function getVeritransJs() {
        $mode = Mage::getStoreConfig('payment/vpaymentins/vt_mode');
        if($mode == Icube_Vpaymentins_Model_Resource_Mode::MODE_DEVELOPMENT) {
            return '<script type="text/javascript" src="'.Mage::getDesign()->getSkinUrl('js/veritrans.min.js').'"></script>';
        } elseif ($mode == Icube_Vpaymentins_Model_Resource_Mode::MODE_PRODUCTION) {
            return '<script type="text/javascript" src="https://api.veritrans.co.id/v2/assets/js/veritrans.min.js"></script>';
        }
    }
}