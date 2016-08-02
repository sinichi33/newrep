USE ruparupadb;
TRUNCATE TABLE temp_SAPInventoryImport;
LOAD DATA LOCAL INFILE '/tmp/temp_SAPInventoryImport.txt' INTO TABLE temp_SAPInventoryImport
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

UPDATE `temp_SAPInventoryImport` SET `sku` = TRIM(LEADING '0' FROM `sku` ) WHERE `sku` NOT LIKE '%-%';

UPDATE
        advancedinventory_stock ast
        INNER JOIN pointofsale pos ON ast.place_id = pos.place_id
        INNER JOIN catalog_product_entity cp ON ast.`product_id` = cp.`entity_id`
		INNER JOIN catalog_product_entity_int cv ON cp.entity_id = cv.entity_id
		INNER JOIN eav_attribute ev ON cv.attribute_id = ev.attribute_id AND ev.attribute_code = 'sister_company' 
		INNER JOIN eav_attribute_option eao ON eao.`attribute_id` = ev.`attribute_id` AND eao.option_id = cv.value
		INNER JOIN eav_attribute_option_value eaov ON eao.`option_id` = eaov.`option_id` AND eaov.value = 'R110'
        INNER JOIN temp_SAPInventoryImport ti ON cp.sku = ti.sku
		LEFT OUTER JOIN temp_ReservedItems tri ON ti.sku = tri.sku AND tri.`store_code` = 'H384'
SET ast.`quantity_in_stock` = ti.`qty` - IFNULL(tri.qty,0)
WHERE pos.store_code = 'H384'

;

