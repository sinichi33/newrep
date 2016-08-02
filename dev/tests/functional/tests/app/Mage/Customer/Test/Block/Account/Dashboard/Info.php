<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Tests
 * @package     Tests_Functional
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

namespace Mage\Customer\Test\Block\Account\Dashboard;

use Magento\Mtf\Block\Block;

/**
 * Main block on customer account page.
 */
class Info extends Block
{
    /**
     * Css selector for Contact Information Change Password Link.
     *
     * @var string
     */
    protected $contactInfoChangePasswordLink = '.box-content a';

    /**
     * Click on Contact Information Edit Link.
     *
     * @return void
     */
    public function openChangePassword()
    {
        $this->_rootElement->find($this->contactInfoChangePasswordLink)->click();
    }
}
