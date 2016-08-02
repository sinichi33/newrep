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

namespace Enterprise\GiftWrapping\Test\Constraint;

use Mage\Catalog\Test\Fixture\CatalogProductSimple;
use Mage\Catalog\Test\Page\Product\CatalogProductView;
use Mage\Checkout\Test\Page\CheckoutCart;
use Mage\Checkout\Test\Page\CheckoutOnepage;
use Mage\Customer\Test\Fixture\Address;
use Mage\Customer\Test\Fixture\Customer;
use Mage\Customer\Test\Page\CustomerAccountLogout;
use Enterprise\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that Gift Wrapping can be found during one page checkout on frontend.
 */
class AssertGiftWrappingOnFrontendCheckout extends AbstractConstraint
{
    /**
     * Assert that Gift Wrapping can be found during one page checkout on frontend.
     *
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param BrowserInterface $browser
     * @param CheckoutOnepage $checkoutOnepage
     * @param GiftWrapping $giftWrapping
     * @param Address $billingAddress
     * @param CatalogProductSimple $product
     * @param Customer $customer
     * @param CustomerAccountLogout $customerAccountLogout
     * @return void
     */
    public function processAssert(
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        BrowserInterface $browser,
        CheckoutOnepage $checkoutOnepage,
        GiftWrapping $giftWrapping,
        Address $billingAddress,
        CatalogProductSimple $product,
        Customer $customer,
        CustomerAccountLogout $customerAccountLogout
    ) {
        // Preconditions
        $customer->persist();
        $product->persist();
        // Steps
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $catalogProductView->getViewBlock()->addToCart($product);
        $checkoutCart->open()->getCartBlock()->getProceedToCheckoutBlock()->proceedToCheckout();
        $checkoutOnepage->getLoginBlock()->loginCustomer($customer);
        $checkoutOnepage->getBillingBlock()->fillBilling($billingAddress);
        $checkoutOnepage->getBillingBlock()->clickContinue();
        \PHPUnit_Framework_Assert::assertContains(
            $giftWrapping->getDesign(),
            $checkoutOnepage->getGiftOptionsBlock()->getGiftWrappingsAvailable(),
            "Gift Wrapping '{$giftWrapping->getDesign()}' is not present in one page checkout on frontend."
        );

        $customerAccountLogout->open();
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping can be found during one page checkout on frontend.';
    }
}
