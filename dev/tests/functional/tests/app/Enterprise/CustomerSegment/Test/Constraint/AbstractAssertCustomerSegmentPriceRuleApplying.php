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

use Mage\Catalog\Test\Fixture\CatalogProductSimple;
use Mage\Catalog\Test\Page\Product\CatalogProductView;
use Mage\Checkout\Test\Page\CheckoutCart;
use Mage\Cms\Test\Page\CmsIndex;
use Mage\Customer\Test\Fixture\Customer;
use Mage\Customer\Test\Page\CustomerAccountLogout;
use Enterprise\CustomerSegment\Test\Fixture\CustomerSegment;
use Enterprise\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Enterprise\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Abstract class for implementing assert applying.
 */
abstract class AbstractAssertCustomerSegmentPriceRuleApplying extends AbstractConstraint
{
    /**
     * Page CheckoutCart.
     *
     * @var CheckoutCart
     */
    protected $checkoutCart;

    /**
     * Page CmsIndex.
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Page CustomerAccountLogout.
     *
     * @var CustomerAccountLogout
     */
    protected $customerAccountLogout;

    /**
     * Page CatalogProductView.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Customer from precondition.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Customer segment fixture.
     *
     * @var CustomerSegment
     */
    protected $customerSegment;

    /**
     * Customer Segment index page.
     *
     * @var CustomerSegmentIndex
     */
    protected $customerSegmentIndex;

    /**
     * Page for creating new customer.
     *
     * @var CustomerSegmentNew
     */
    protected $customerSegmentNew;

    /**
     * Implementation assert.
     *
     * @return void
     */
    abstract protected function assert();

    /**
     * Login to frontend. Create product. Adding product to shopping cart.
     *
     * @param CheckoutCart $checkoutCart
     * @param CatalogProductSimple $product
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountLogout $customerAccountLogout
     * @param CatalogProductView $catalogProductView
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param BrowserInterface $browser
     * @param CustomerSegmentNew $customerSegmentNew
     * @return void
     */
    public function processAssert(
        CheckoutCart $checkoutCart,
        CatalogProductSimple $product,
        CmsIndex $cmsIndex,
        CustomerAccountLogout $customerAccountLogout,
        CatalogProductView $catalogProductView,
        Customer $customer,
        CustomerSegment $customerSegment,
        CustomerSegmentIndex $customerSegmentIndex,
        BrowserInterface $browser,
        CustomerSegmentNew $customerSegmentNew
    ) {
        $this->checkoutCart = $checkoutCart;
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountLogout = $customerAccountLogout;
        $this->catalogProductView = $catalogProductView;
        $this->customer = $customer;
        $this->customerSegment = $customerSegment;
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;

        $this->cmsIndex->open();
        $this->objectManager->create(
            'Mage\Customer\Test\TestStep\LoginCustomerOnFrontendStep',
            ['customer' => $this->customer]
        )->run();

        $product->persist();
        $this->checkoutCart->open()->getCartBlock()->clearShoppingCart();
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $this->catalogProductView->getViewBlock()->clickAddToCart();
        $this->checkoutCart->getMessagesBlock()->getSuccessMessages();
        $this->assert();
        $this->customerAccountLogout->open();
    }
}
