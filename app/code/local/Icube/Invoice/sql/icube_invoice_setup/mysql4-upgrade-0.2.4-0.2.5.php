<?php
$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE sales_flat_quote_item ADD COLUMN pickup_location_code VARCHAR(50);
	ALTER TABLE sales_flat_order_item ADD COLUMN pickup_location_code VARCHAR(50);
	ALTER TABLE sales_flat_invoice ADD COLUMN pickup_location_code VARCHAR(50);
    ALTER TABLE sales_flat_invoice ADD COLUMN receipt_id VARCHAR(255);
    ALTER TABLE sales_flat_invoice_grid ADD COLUMN pickup_location_code VARCHAR(50) NULL;
    ALTER TABLE sales_flat_invoice_item ADD COLUMN pickup_location_code VARCHAR(50);
    ");

$installer->run("UPDATE pointofsale SET pickup_location_code = 'pickup_simpanglima' WHERE store_code = 'A332';
	UPDATE pointofsale SET pickup_location_code = 'pickup_lenmarchsurabaya' WHERE store_code = 'A422';
	UPDATE pointofsale SET pickup_location_code = 'pickup_ibccbandung' WHERE store_code = 'A319';
	UPDATE pointofsale SET pickup_location_code = 'pickup_ibccmakasar' WHERE store_code = 'A433';
	UPDATE pointofsale SET pickup_location_code = 'pickup_centrepoint' WHERE store_code = 'A327';
	UPDATE pointofsale SET pickup_location_code = 'pickup_livingworld' WHERE store_code = 'A322';
	UPDATE pointofsale SET pickup_location_code = 'pickup_arthagading' WHERE store_code = 'A314';
	UPDATE pointofsale SET pickup_location_code = 'pickup_livingplaza' WHERE store_code = 'A336';
	UPDATE pointofsale SET pickup_location_code = 'pickup_gandariacity' WHERE store_code = 'A346';
	UPDATE pointofsale SET pickup_location_code = 'pickup_simpanglima' WHERE store_code = 'H328';
	UPDATE pointofsale SET pickup_location_code = 'pickup_lenmarchsurabaya' WHERE store_code = 'H374';
	UPDATE pointofsale SET pickup_location_code = 'pickup_ibccbandung' WHERE store_code = 'H305';
	UPDATE pointofsale SET pickup_location_code = 'pickup_ibccmakasar' WHERE store_code = 'H384';
	UPDATE pointofsale SET pickup_location_code = 'pickup_centrepoint' WHERE store_code = 'H312';
	UPDATE pointofsale SET pickup_location_code = 'pickup_livingworld' WHERE store_code = 'H327';
	UPDATE pointofsale SET pickup_location_code = 'pickup_arthagading' WHERE store_code = 'H302';
	UPDATE pointofsale SET pickup_location_code = 'pickup_livingplaza' WHERE store_code = 'H308';
	UPDATE pointofsale SET pickup_location_code = 'pickup_gandariacity' WHERE store_code = 'H326';
	UPDATE pointofsale SET pickup_location_code = 'pickup_simpanglima' WHERE store_code = 'T304';
	UPDATE pointofsale SET pickup_location_code = 'pickup_lenmarchsurabaya' WHERE store_code = 'T322';
	UPDATE pointofsale SET pickup_location_code = 'pickup_ibccbandung' WHERE store_code = 'T314';
	UPDATE pointofsale SET pickup_location_code = 'pickup_ibccmakasar' WHERE store_code = 'T324';
	UPDATE pointofsale SET pickup_location_code = 'pickup_centrepoint' WHERE store_code = 'T318';
	UPDATE pointofsale SET pickup_location_code = 'pickup_livingworld' WHERE store_code = 'T305';
	UPDATE pointofsale SET pickup_location_code = 'pickup_arthagading' WHERE store_code = 'T317';
	UPDATE pointofsale SET pickup_location_code = 'pickup_livingplaza' WHERE store_code = 'T302';
	UPDATE pointofsale SET pickup_location_code = 'pickup_gandariacity' WHERE store_code = 'T303';
");


$installer->endSetup();