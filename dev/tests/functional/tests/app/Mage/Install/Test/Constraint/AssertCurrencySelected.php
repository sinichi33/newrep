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

namespace Mage\Install\Test\Constraint;

use Mage\Adminhtml\Test\Page\Adminhtml\Dashboard;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that selected currency symbol displays in admin panel.
 */
class AssertCurrencySelected extends AbstractConstraint
{
    /**
     * Assert that selected currency symbol displays on dashboard.
     *
     * @param Dashboard $dashboard
     * @param string $currencySymbol
     * @return void
     */
    public function processAssert(Dashboard $dashboard, $currencySymbol)
    {
        $dashboard->open();
        \PHPUnit_Framework_Assert::assertTrue(
            strpos($dashboard->getMainBlock()->getRevenuePrice(), $currencySymbol) !== false,
            'Selected currency symbol not displays on dashboard.'
        );
    }

    /**
     * Returns a string representation of successful assertion.
     *
     * @return string
     */
    public function toString()
    {
        return 'Selected language currently displays on frontend.';
    }
}
