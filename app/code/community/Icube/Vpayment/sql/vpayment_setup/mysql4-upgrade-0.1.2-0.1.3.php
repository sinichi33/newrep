<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Phoenix
 * @package     Phoenix_Moneybookers
 * @copyright   Copyright (c) 2013 Phoenix Medien GmbH & Co. KG (http://www.phoenix-medien.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

//change field validation_value to varchar(2000)
$this->run("
ALTER TABLE `{$this->getTable('promo_program')}` CHANGE `validation_value` `validation_value` varchar(2000) NOT NULL default ''
");

//add column start_date end_date to promo_program
$tableQuote = $this->getTable('promo_program');
$installer->run("ALTER TABLE ".$tableQuote." ADD COLUMN start_date datetime default NULL ");
$tableQuote = $this->getTable('promo_program');
$installer->run("ALTER TABLE ".$tableQuote." ADD COLUMN end_date datetime default NULL ");

$installer->endSetup();

//insert dummy data
//$newPromo = Mage::getModel('vpayment/program')
//->setPromoName('UOB Platinum Discount 10%')
//->setPromoType('bin_filter')
//->set('41111111, 54264009')
//->save();
