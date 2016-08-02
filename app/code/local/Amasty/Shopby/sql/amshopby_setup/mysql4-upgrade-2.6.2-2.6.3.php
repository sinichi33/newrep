<?php
/**
 * @copyright Amasty.
 */
$this->startSetup();

$this->run("
ALTER TABLE `{$this->getTable('amshopby/value')}`
ADD `cms_block_id` int(11) DEFAULT NULL,
ADD `cms_block_bottom_id` int(11) DEFAULT NULL");

$this->run("
UPDATE `{$this->getTable('amshopby/value')}` v,`{$this->getTable('cms/block')}` b
SET v.`cms_block_id` = b.`block_id`
WHERE b.`identifier` = v.`cms_block`
");

$this->run("
UPDATE `{$this->getTable('amshopby/value')}` v,`{$this->getTable('cms/block')}` b
SET v.`cms_block_bottom_id` = b.`block_id`
WHERE b.`identifier` = v.`cms_block_bottom`
");

$this->run("
ALTER TABLE `{$this->getTable('amshopby/value')}`
DROP `cms_block`,
DROP `cms_block_bottom`;
");


$this->endSetup();
