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

namespace Enterprise\CustomerSegment\Test\Constraint;

/**
 * Assert that Catalog Price Rule is not applied on product(s) in shopping cart according to rule condition.
 */
class AssertCustomerSegmentInCartPriceRuleIsNotApplying extends AbstractAssertCustomerSegmentPriceRuleApplying
{
    /**
     * Assert that shopping cart subtotal equals with grand total.
     *
     * @return void
     */
    protected function assert()
    {
        $totalBlock = $this->checkoutCart->getTotalsBlock();

        \PHPUnit_Framework_Assert::assertEquals(
            $totalBlock->getData('subtotal'),
            $totalBlock->getData('grand_total'),
            'Catalog Price Rule is applied on product in shopping cart. Price is not equals.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Catalog Price Rule is not applied on product in shopping cart.';
    }
}
