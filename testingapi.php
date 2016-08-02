<?php
// custom api soap client example
$mageFilename = 'app/Mage.php';

require_once $mageFilename;

umask(0);

Mage::app();

$awbnumber = "123";
// $rmaId = '100000001';

try {
$soap = new SoapClient('http://www.ruparupa.com/index.php/api/?wsdl');
$sessionId = $soap->login('icube', 'password123');
//echo "Login ID : $sessionId";
$result = $soap->call($sessionId, 'icubeinvoice.updatestatus',array('awbnumber' => $awbnumber,'status' => 'delivered', 'carrier_code' => 'sap'));
// $result = $soap->call($sessionId, 'icuberma.updatestatus',array('rmaId' => $rmaId,'status' => 'authorized'));
echo $result;

}
catch(Exception $e) {
 echo $e->getMessage();
}