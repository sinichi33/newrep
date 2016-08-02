<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_invoice ADD COLUMN sap_so_number VARCHAR(255) NULL;
    ALTER TABLE sales_flat_invoice ADD COLUMN awb_number VARCHAR(255) NULL;
    ALTER TABLE sales_flat_invoice_grid ADD COLUMN sap_so_number VARCHAR(255) NULL;
    ALTER TABLE sales_flat_invoice_grid ADD COLUMN awb_number VARCHAR(255) NULL;
");

$installer->endSetup();

?>