<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
// Bootstrap Magento
require '../app/Mage.php';
Mage::app('admin', 'store');
try{
    $catalogPriceRule = Mage::getModel('catalogrule/rule');
    $catalogPriceRule->applyAll();
    echo "Catalog Price Rule All Applied ";
} catch (Exception $e) {
    die($e);
}