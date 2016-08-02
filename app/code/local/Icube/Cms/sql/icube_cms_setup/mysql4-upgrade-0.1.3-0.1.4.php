<?php
/*
 * Since PATCH 6788, we need to register blocks
 */

$installer = $this;
$installer->startSetup();

$installer->getConnection()->insertMultiple(
	$installer->getTable('admin/permission_block'),
	array(
		array('block_name' => 'cms/block', 'is_allowed' => 1),
		array('block_name' => 'catalog/navigation', 'is_allowed' => 1),
		array('block_name' => 'catalog/product', 'is_allowed' => 1),
		array('block_name' => 'core/text_tag', 'is_allowed' => 1),
		array('block_name' => 'customer/account_navigation', 'is_allowed' => 1),
		array('block_name' => 'checkout/cart_minicart', 'is_allowed' => 1),
		array('block_name' => 'checkout/cart_sidebar', 'is_allowed' => 1),
		array('block_name' => 'core/text_list', 'is_allowed' => 1),
		array('block_name' => 'newsletter/subscribe', 'is_allowed' => 1),
		array('block_name' => 'page/html/store', 'is_allowed' => 1),
		array('block_name' => 'core/html_calendar', 'is_allowed' => 1),
		array('block_name' => 'catalog/product_list', 'is_allowed' => 1)
	)
);

$installer->endSetup();