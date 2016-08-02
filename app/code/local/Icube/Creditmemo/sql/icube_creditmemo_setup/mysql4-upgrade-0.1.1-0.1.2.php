<?php
$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('icube_creditmemo/refundable')} DROP `other_payment_refundable`;
");

$installer->endSetup();

?>