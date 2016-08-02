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
 * Assert that after adding products by sku to shopping cart, requested quantity is not available error message appears.
 */
class AssertQtyIsNotEnoughFailMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Requested quantity is not available error message.
     */
    const ERROR_QUANTITY_MESSAGE = 'Requested quantity is not available.';

    /**
     * Quantity left in stock error message.
     */
    const LEFT_IN_STOCK_ERROR_MESSAGE = 'Only %d left in stock';

    /**
     * Assert that requested quantity is not available error message is displayed after adding products to cart by sku.
     *
     * @param CheckoutCart $checkoutCart
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, $requiredAttentionProducts)
    {
        foreach ($requiredAttentionProducts as $product) {
            $currentMessage = $checkoutCart->getAdvancedCheckoutCart()->getFailedItemErrorMessage($product);
            \PHPUnit_Framework_Assert::assertContains(
                self::ERROR_QUANTITY_MESSAGE,
                $currentMessage
            );
            \PHPUnit_Framework_Assert::assertContains(
                sprintf(self::LEFT_IN_STOCK_ERROR_MESSAGE, $product->getStockData()['qty']),
                $currentMessage
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
        return 'Requested quantity is not available error message is present after adding products to shopping cart.';
    }
}
