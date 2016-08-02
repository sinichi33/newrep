<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
// Bootstrap Magento
require '../app/Mage.php';
Mage::app('admin', 'store');

echo "GC Generator use with caution!\n";

if ($argc != 8) {
    echo "usage: php gc-generator.php campaign_name value min_order valid_date(Y-m-d) expire_date(Y-m-d) category_exclusion iteration\n";
    die;
}
else {
    $campaign_name = $argv[1];
    $value = $argv[2];
    $min_order = $argv[3];
    $valid_date = $argv[4];
    $expire_date = $argv[5];
    $category_exclusion = $argv[6];
    $iteration = $argv[7];
}


try{
    $resource = Mage::getSingleton('core/resource');
    $readCon = $resource->getConnection('core_read');
    $writeCon = $resource->getConnection('core_write');


    for ($i=0;$i<$iteration;$i++) {

        $gc_code = $readCon->fetchOne("
        SELECT code FROM enterprise_giftcardaccount_pool
        WHERE status = 0 AND campaign_name = '$campaign_name'
        ");

        $gift_card = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');
        $gift_card
            ->setCode($gc_code)
            ->setStatus($gift_card::STATUS_ENABLED)
            ->setDateExpires($expire_date)
            ->setWebsiteId(1)
            ->setState($gift_card::STATE_AVAILABLE)
            ->setIsRedeemable(0)
            ->setCategoryIdsExclusion($category_exclusion)
            ->setMinPurchaseValue($min_order)
            ->setValidFromDate($valid_date)
            ->setValidToDate($expire_date)
            ->setRestrictCombine(1)
            ->setCampaignName($campaign_name)
            ->setBalance($value);
        $writeCon->query("UPDATE enterprise_giftcardaccount_pool SET status = 1 WHERE code = '$gc_code' ");
        $gift_card->save();
        echo "GC generated: $gc_code\n";
    }
} catch (Exception $e) {
    die($e);
}