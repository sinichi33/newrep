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

namespace Mage\Customer\Test\Constraint;

use Mage\Customer\Test\Page\Adminhtml\CustomerGroupNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that customer group already exist error message is displayed after customer group save.
 */
class AssertCustomerGroupAlreadyExists extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Customer group already exists error message.
     */
    const ERROR_MESSAGE = 'Customer Group already exists.';

    /**
     * Assert that customer group already exist error message is displayed after customer group save.
     *
     * @param CustomerGroupNew $customerGroupNew
     * @return void
     */
    public function processAssert(CustomerGroupNew $customerGroupNew)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::ERROR_MESSAGE,
            $customerGroupNew->getMessagesBlock()->getErrorMessages()
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer group already exist error message is displayed.';
    }
}
