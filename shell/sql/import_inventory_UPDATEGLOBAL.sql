USE ruparupadb;

INSERT INTO advancedinventory_item (product_id, manage_local_stock)
SELECT cp.entity_id, 1 as manage_local_stock
FROM catalog_product_entity cp
	LEFT OUTER JOIN
	advancedinventory_item ai ON cp.entity_id = ai.product_id
WHERE ai.product_id is NULL
;


INSERT INTO advancedinventory_stock (product_id, localstock_id, place_id, manage_stock, quantity_in_stock, backorder_allowed, use_config_setting_for_backorders)

SELECT DISTINCT 
	  ai.product_id
	, ai.id as localstock_id
	, p.place_id
	, 1 as manage_stock
	, 1 as quantity_in_stock
	, NULL as backorder_allowed
	, 1 as use_config_setting_for_backorders

FROM advancedinventory_item ai
	LEFT OUTER JOIN advancedinventory_stock ak ON ai.id = ak.`localstock_id`
, pointofsale p
WHERE ak.`localstock_id` is null
ORDER BY product_id, place_id
;

UPDATE cataloginventory_stock_item ci INNER JOIN 
(
	SELECT SUM(a.quantity_in_stock) as qty, a.product_id
	FROM advancedinventory_stock a
	GROUP BY a.product_id 
) ls ON ci.product_id = ls.product_id
SET ci.qty = ls.qty
;

UPDATE cataloginventory_stock_item
SET is_in_stock = 0
WHERE qty <= 0
;

UPDATE cataloginventory_stock_item
SET is_in_stock = 1
WHERE qty > 0
;

## FIX WYOMIND ISSUE TO MAKE SURE NO PRODUCTS ARE BACKORDER-ABLE
UPDATE 
	cataloginventory_stock_item
SET 	use_config_backorders = 1 
	,	backorders = 0
WHERE backorders = 1
	;

## FIX WEBSITE ID ISSUE, WHEN IMPORT PRODUCTS

TRUNCATE TABLE catalog_product_website;

INSERT INTO catalog_product_website (product_id, website_id)
SELECT entity_id, 1 AS website_id
FROM catalog_product_entity;	
