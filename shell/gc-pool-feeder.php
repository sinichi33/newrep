<?php
/**
 * Created by PhpStorm.
 * User: hendywijaya
 * Date: 3/27/16
 * Time: 11:59 PM
 */

define('MAGENTO_ROOT', __DIR__ . '/..');

$localxml = simplexml_load_file(MAGENTO_ROOT . '/app/etc/local.xml',
    'SimpleXMLElement', LIBXML_NOCDATA);

$master = $localxml->global->resources->default_setup->connection;

$conn = mysqli_connect($master->host, $master->username, $master->password);
mysqli_select_db($conn, $master->dbname);

$filename = $argv[1];
$campaign_name = $argv[2];
$code_list = file($filename);

foreach ($code_list as $code) {

    try {
        $code = str_replace("\n","",$code);
        $query = "INSERT INTO enterprise_giftcardaccount_pool VALUES('$code',0,'$campaign_name')";

        $result = mysqli_query($conn,$query);

        echo "feeding $code result: $result \n";

    }
    catch (Exception $e) {
        echo $e->getMessage()."\n";
    }

}
