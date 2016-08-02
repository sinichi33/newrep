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
 * Assert that product has tier price message appears after adding products by sku to shopping cart.
 */
class AssertProductTierPriceMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that product has tier price message appears after adding products by sku to shopping cart.
     *
     * @param CheckoutCart $checkoutCart
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, array $requiredAttentionProducts)
    {
        foreach ($requiredAttentionProducts as $product) {
            $messages = $checkoutCart->getAdvancedCheckoutCart()->getTierPriceMessages($product);
            $tierPrices = $product->getTierPrice();
            \PHPUnit_Framework_Assert::assertTrue(
                count($messages) === count($tierPrices),
                'Wrong qty messages is displayed.'
            );
            foreach ($tierPrices as $key => $tierPrice) {
                $price = (bool)strpos($messages[$key], (string)$tierPrice['price']);
                $priceQty = (bool)strpos($messages[$key], (string)$tierPrice['price_qty']);
                $savePercent = (bool)strpos($messages[$key], $this->getSavePercent($product->getPrice(), $tierPrice));
                \PHPUnit_Framework_Assert::assertTrue(
                    $price and $priceQty and $savePercent,
                    'Wrong tier price message is displayed.'
                );
            }
        }
    }

    /**
     * Get save percent.
     *
     * @param string $price
     * @param array $tierPrice
     * @return string
     */
    protected function getSavePercent($price, array $tierPrice)
    {
        $tierPriceQty = $tierPrice['price_qty'];

        return round(100 - ((100 * ($tierPrice['price'] * $tierPriceQty)) / ($price * $tierPriceQty))) . "%";
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'The message that product has tier price after adding products to cart by sku is present.';
    }
}
