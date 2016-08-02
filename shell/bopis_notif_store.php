<?php
/**
 * Created by PhpStorm.
 * User: asep.solehudin
 * Date: 01/04/2016
 * Time: 13:29
 */

define('MAGENTO_ROOT', __DIR__ . '/..');

$localxml = simplexml_load_file(MAGENTO_ROOT . '/app/etc/local.xml',
    'SimpleXMLElement', LIBXML_NOCDATA);

$master = $localxml->global->resources->default_setup->connection;

$conn = mysqli_connect($master->host, $master->username, $master->password);
mysqli_select_db($conn, $master->dbname);

$query = 'SELECT sfig.entity_id,sfig.order_increment_id,sfig.increment_id,p.manager_email,p.store_code FROM'
    .' sales_flat_invoice_grid AS sfig'
    .' INNER JOIN sales_flat_order AS sfo ON sfo.entity_id=sfig.order_id'
    .' INNER JOIN pointofsale AS p ON p.store_code=sfig.store_code'
    .' WHERE '
    .' p.store_code <> "DC"'
    .' AND sfo.status="processing"'
    .' AND sfig.invoice_status="PENDING"'
    .' AND sfig.created_at < NOW()'
    .' order by p.store_code,sfig.order_increment_id asc';
$result = mysqli_query($conn,$query);

$data = [];

while($row = $result->fetch_assoc()){
//    if($row['store_code'] == 'A322')
//        $data[$row['store_code']]['email'] = 'asep@ruparupa.com';
//    if($row['store_code'] == 'H327')
//        $data[$row['store_code']]['email'] = 'tsuleehyudin@gmail.com';
//    if($row['store_code'] == 'T305')
//        $data[$row['store_code']]['email'] = 'adinvi32i@yahoo.com';

    if(empty($row['manager_email'])) continue;
    $data[$row['store_code']]['email'] = $row['manager_email'];
    $data[$row['store_code']]['data'][$row['entity_id']]['order_id'] = $row['order_increment_id'];
    $data[$row['store_code']]['data'][$row['entity_id']]['invoice_id'] = $row['increment_id'];

    $querySku = 'SELECT sku,name,qty FROM sales_flat_invoice_item WHERE parent_id='.$row['entity_id'];
    $resultSku = mysqli_query($conn,$querySku);
    while($rowSku = $resultSku->fetch_assoc()){
        $data[$row['store_code']]['data'][$row['entity_id']]['data'][] = 'SKU : '.$rowSku['sku'].', Qty : '.intval($rowSku['qty']).', Nama : '.$rowSku['name'];
    }
}

mysqli_close($conn);

if(count($data) > 0)
{
    $header = 'From: help@ruparupa.com' . "\r\n" .
        'Reply-To: help@ruparupa.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    foreach ($data as $store) {
        $str = "Berikut ini order ruparupa yang pending, mohon disiapkan barangnya : \r\n";
        foreach ($store['data'] as $d) {
            $str .= "ORDER ID : " . $d['order_id'] . "\r\n";
            $str .= "INVOICE ID : " . $d['invoice_id'] . "\r\n";
            if (count($d['data']) > 0) {
                foreach ($d['data'] as $i) {
                    $str .= "- " . $i . "\r\n";
                }
            }
            $str .= "\r\n\r\n";
        }
        $str .= "\r\n\r\n Terima Kasih.";
        mail($store['email'],'RUPARUPA : BOPIS ORDER PENDING',$str,$header);
    }
}


