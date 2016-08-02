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

namespace Enterprise\GiftRegistry\Test\Constraint;

use Magento\Mtf\Fixture\InjectableFixture;
use Mage\Catalog\Test\Page\Product\CatalogProductView;
use Mage\Checkout\Test\Page\CheckoutCart;
use Enterprise\GiftRegistry\Test\Fixture\GiftRegistry;
use Enterprise\GiftRegistry\Test\Page\GiftRegistryItems;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that product can be added to active gift registry from shopping cart.
 */
class AssertGiftRegistryActiveInShoppingCart extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that product can be added to active gift registry from shopping cart.
     *
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param InjectableFixture $product
     * @param GiftRegistry $giftRegistry
     * @param GiftRegistryItems $giftRegistryItems
     * @param BrowserInterface $browser
     * @return void
     */
    public function processAssert(
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        InjectableFixture $product,
        GiftRegistry $giftRegistry,
        GiftRegistryItems $giftRegistryItems,
        BrowserInterface $browser
    ) {
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $catalogProductView->getViewBlock()->addToCart($product);
        $catalogProductView->getMessagesBlock()->waitSuccessMessage();
        $checkoutCart->open();
        $checkoutCart->getGiftRegistryBlock()->addToGiftRegistry($giftRegistry);
        \PHPUnit_Framework_Assert::assertTrue(
            $giftRegistryItems->getGiftRegistryItemsBlock()->isProductInGrid($product),
            "Product can not be added to active gift registry '{$giftRegistry->getTitle()}' from shopping cart."
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Product can be added to active gift registry from shopping cart.';
    }
}
