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

namespace Mage\Sales\Test\Constraint;

use Mage\Sales\Test\Page\SalesGuestPrint;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Assert that products printed correctly on sales guest print page.
 */
class AssertSalesPrintOrderProducts extends AbstractConstraint
{
    /**
     * Template for error message.
     */
    const ERROR_MESSAGE = "Product with name: '%s' was not found on sales guest print page.\n";

    /**
     * Assert that products printed correctly on sales guest print page.
     *
     * @param SalesGuestPrint $salesGuestPrint
     * @param InjectableFixture[] $products
     * @return void
     */
    public function processAssert(SalesGuestPrint $salesGuestPrint, array $products)
    {
        $errors = '';
        foreach ($products as $product) {
            if (!$salesGuestPrint->getViewBlock()->isItemVisible($product)) {
                $errors .= sprintf(self::ERROR_MESSAGE, $product->getName());
            }
        }

        \PHPUnit_Framework_Assert::assertEmpty($errors, $errors);
    }

    /**
     * Returns a string representation of successful assertion.
     *
     * @return string
     */
    public function toString()
    {
        return "Products were printed correctly.";
    }
}
