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

namespace Enterprise\Checkout\Test\Constraint;

use Mage\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that requested qty does not meet specified increments error message is displayed after adding products by sku.
 */
class AssertQtyIncrementsFailMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Qty increments error message.
     */
    const QTY_INCREMENTS_ERROR_MESSAGE = 'This product is available for purchase in increments of %d only.';

    /**
     * Assert that requested qty does not meet the increments error message is displayed after adding products by sku.
     *
     * @param CheckoutCart $checkoutCart
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, array $requiredAttentionProducts)
    {
        foreach ($requiredAttentionProducts as $product) {
            \PHPUnit_Framework_Assert::assertEquals(
                sprintf(self::QTY_INCREMENTS_ERROR_MESSAGE, $product->getStockData()['qty_increments']),
                $checkoutCart->getAdvancedCheckoutCart()->getFailedItemErrorMessage($product)
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Requested qty does not meet the increments error message is present after adding products by sku.';
    }
}
