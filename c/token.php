<?php
if (!defined('MAGENTO_ROOT')) die('You cannot access this file directly!!');

if ( $_SERVER['REQUEST_URI'] != '/ajaxlogin/ajax/index/'
    && (!stripos($_SERVER['REQUEST_URI'],'kembangan328'))
    && (!stripos($_SERVER['REQUEST_URI'],'vtweb'))
    && (!stripos($_SERVER['REQUEST_URI'],'redeempoint'))
    && (!stripos($_SERVER['REQUEST_URI'],'invoice'))
    && (!stripos($_SERVER['REQUEST_URI'],'return'))
    && (!stripos($_SERVER['REQUEST_URI'],'zendesk'))
) {

    $memberLink = '/welcome/?'.$_SERVER['REQUEST_URI'];

    $cookieName = 'tokenRupaRupa328';
    $expiry = time() + 86400 * 365 * 1;

    $localxml = simplexml_load_file(MAGENTO_ROOT . '/app/etc/local.xml',
        'SimpleXMLElement', LIBXML_NOCDATA);

    $master = $localxml->global->resources->default_setup->connection;

    $conn = mysqli_connect($master->host, $master->username, $master->password);
    mysqli_select_db($conn, $master->dbname);

    if (isset($_GET['rptoken'])) {

        $token = mysqli_escape_string($conn, strtolower(trim($_GET['rptoken'])));
        $ipaddress = $_SERVER['REMOTE_ADDR'];

        $insert_log_query = "INSERT INTO customer_token_history (id,tokenId,ip,visitDate) VALUES (NULL,'$token','$ipaddress',CURRENT_TIMESTAMP) ";

        mysqli_query($conn, $insert_log_query);

        $memberLink = str_replace('rptoken='.$token.'&','',$memberLink);

        if ($_COOKIE[$cookieName] == $token) {
            // existing token in cookie
//            header('location: ' . $memberLink);die;
        }
        elseif ($token != '') {
            // check for token
            if (validateToken($conn, $token)) {
                setcookie($cookieName,$token,$expiry,'/');
//                header('location: '.$memberLink);die;
            }
        }

    }
    elseif (isset($_COOKIE[$cookieName])) {
        $token = strtolower(trim($_COOKIE[$cookieName]));
        // check for token
        if (validateToken($conn, $token)) {
            setcookie($cookieName,$token,$expiry,'/');
        }
    }
    elseif (isset($_COOKIE['frontend'])) {
        // do nothing, continue to serve
        if (!isset($_COOKIE[$cookieName])) {
            // fill with expired cookie value
            // setcookie($cookieName,'18fdeb14',$expiry,'/');
        }
    }
}

function validateToken ($conn, $token) {
    $token = mysqli_escape_string($conn, $token);
    $result = mysqli_query($conn, "SELECT tokenId FROM customer_token WHERE tokenId='$token'");

    $row = mysqli_fetch_assoc($result);

    return $row;

}