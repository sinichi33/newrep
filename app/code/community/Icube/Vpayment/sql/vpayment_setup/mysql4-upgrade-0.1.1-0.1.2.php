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

$installer->run("
-- DROP TABLE IF EXISTS `{$this->getTable('promo_program')}`;
CREATE TABLE `{$this->getTable('promo_program')}` (
`promo_code` int(11) NOT NULL auto_increment,
`promo_name` varchar(100) NOT NULL default '',
`program_type` varchar(50) NOT NULL default '',
`validation_value` varchar(255) NOT NULL default '',
PRIMARY KEY (`promo_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
");

$installer->run("
INSERT INTO `{$this->getTable('promo_program')}`(promo_name,program_type,validation_value)
VALUES ('UOB Platinum Discount 10%','bin_filter','41111111, 54264009');
");

$installer->run("
INSERT INTO `{$this->getTable('promo_program')}`(promo_name,program_type,validation_value)
VALUES ('Visa Discount 10%','bin_filter','54233309, 22264009');
");

$installer->endSetup();

//insert dummy data
//$newPromo = Mage::getModel('vpayment/program')
//->setPromoName('UOB Platinum Discount 10%')
//->setPromoType('bin_filter')
//->set('41111111, 54264009')
//->save();
