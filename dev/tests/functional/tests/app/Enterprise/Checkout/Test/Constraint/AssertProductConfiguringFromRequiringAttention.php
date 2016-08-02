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

use Mage\Catalog\Test\Page\Product\CatalogProductView;
use Mage\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that product can be configured and added to cart after added this product to cart by sku.
 */
class AssertProductConfiguringFromRequiringAttention extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Success adding product to cart message.
     */
    const SUCCESS_MESSAGE = '%s was added to your shopping cart.';

    /**
     * Assert that product can be configured and added to cart after added this product to cart by sku.
     *
     * @param CheckoutCart $checkoutCart
     * @param CatalogProductView $catalogProductView
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(
        CheckoutCart $checkoutCart,
        CatalogProductView $catalogProductView,
        array $requiredAttentionProducts
    ) {
        foreach ($requiredAttentionProducts as $product) {
            $checkoutCart->open()->getAdvancedCheckoutCart()->clickSpecifyProductOptionsLink($product);
            $catalogProductView->getViewBlock()->addToCart($product);
            \PHPUnit_Framework_Assert::assertEquals(
                sprintf(self::SUCCESS_MESSAGE, $product->getName()),
                $checkoutCart->getMessagesBlock()->getSuccessMessages()
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
        return "Product can be configured from requiring attention block.";
    }
}
