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

namespace Enterprise\Checkout\Test\TestCase;

use Enterprise\Checkout\Test\Page\CustomerOrderSku;
use Mage\Checkout\Test\Page\CheckoutCart;
use Mage\Cms\Test\Page\CmsIndex;
use Mage\Customer\Test\Fixture\Customer;
use Mage\Customer\Test\Page\CustomerAccountIndex;

/**
 * Preconditions:
 * 1. Create customer.
 * 2. Create product.
 * 3. Setup configuration.
 *
 * Steps:
 * 1. Login to frontend.
 * 2. Open My Account > Order by SKU.
 * 3. Fill data according dataSet.
 * 4. Click 'Add to Cart' button.
 * 5. Perform all asserts.
 *
 * @group Add_by_SKU_(CS)
 * @ZephyrId MPERF-7460
 */
class AddProductsToCartBySkuFromCustomerAccountTest extends AbstractAdvancedCheckoutEntityTest
{
    /**
     * Injection data.
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CustomerOrderSku $customerOrderSku
     * @param CheckoutCart $checkoutCart
     * @return void
     */
    public function __inject(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        CustomerOrderSku $customerOrderSku,
        CheckoutCart $checkoutCart
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->customerOrderSku = $customerOrderSku;
        $this->checkoutCart = $checkoutCart;
    }

    /**
     * Create customer.
     *
     * @param Customer $customer
     * @return array
     */
    public function __prepare(Customer $customer)
    {
        $customer->persist();

        return ['customer' => $customer];
    }

    /**
     * Add products by SKU from My Account test.
     *
     * @param Customer $customer
     * @param array $orderOptions
     * @param string $cartBlock
     * @param string $config
     * @param string $products [optional]
     * @return array
     */
    public function test(Customer $customer, array $orderOptions, $cartBlock, $config, $products = null)
    {
        // Preconditions
        $this->configuration = $config;
        $this->setupConfiguration();
        $products = $this->createProducts($products);
        $orderOptions = $this->prepareOrderOptions($products, $orderOptions);
        // Steps
        $this->cmsIndex->open();
        $this->loginCustomer($customer);
        $this->customerAccountIndex->getAccountNavigationBlock()->openNavigationItem("Order by SKU");
        $this->customerOrderSku->getCustomerSkuBlock()->fillForm($orderOptions);
        $this->customerOrderSku->getCustomerSkuBlock()->addToCart();

        $filteredProducts = $this->filterProducts($products, $cartBlock);

        return [
            'products' => isset($filteredProducts['cart']) ? $filteredProducts['cart'] : [],
            'requiredAttentionProducts' => isset($filteredProducts['required_attention'])
                ? $filteredProducts['required_attention']
                : []
        ];
    }

    /**
     * Clear shopping cart and set configuration after test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->checkoutCart->open()->getCartBlock()->clearShoppingCart();
        $this->checkoutCart->open()->getAdvancedCheckoutCart()->removeAllFailedItems();
        $this->setupConfiguration(true);
    }
}
