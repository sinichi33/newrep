USE ruparupadb;

TRUNCATE TABLE temp_ReservedItems;

INSERT INTO temp_ReservedItems(sku, qty, store_code)

SELECT
		a.sku
	,	SUM(a.qty) as qty
	,	a.store_code
	
FROM 

(

## 1. Order without invoice
SELECT
		soi.`sku`
	,	soi.`qty_ordered` as qty
	,	soi.`store_code`
FROM 			`sales_flat_order` so  
	INNER JOIN	`sales_flat_order_item` soi ON so.`entity_id` = soi.`order_id`
	LEFT OUTER JOIN `sales_flat_invoice` si ON so.`entity_id` = si.`order_id`
WHERE si.`entity_id` is NULL 
	AND (so.`status` != 'fraud' AND so.`status` != 'cancelled' AND so.`status` != 'expire') 

UNION 

## 2. Order with invoice but SO SAP is empty and it is DC
SELECT
		sfii.`sku`
	,	sfii.`qty`
	,	sfi.`store_code`
FROM sales_flat_invoice sfi 
	INNER JOIN sales_flat_invoice_item sfii ON sfii.parent_id = sfi.entity_id
WHERE
	sfi.`sap_so_number` is null  AND sfi.`store_code` = 'DC'
	

UNION

## 3. BOPIS Order with invoice but not pickedup or cancel or ready for pickup
SELECT
		sfii.`sku`
	,	sfii.`qty`
	,	sfi.`store_code`
FROM sales_flat_invoice sfi 
	INNER JOIN sales_flat_invoice_item sfii ON sfii.parent_id = sfi.entity_id
WHERE
		sfi.`store_code` != 'DC'
	AND 
	(
		sfi.`invoice_status` is NULL
		OR	
		(		sfi.`invoice_status` != 'CANCELLED'
			AND sfi.`invoice_status` != 'READY FOR PICKUP'
			AND sfi.`invoice_status` != 'PICKED UP BY CUSTOMER'
		)
	)
) a

GROUP BY a.sku, a.store_code
;


