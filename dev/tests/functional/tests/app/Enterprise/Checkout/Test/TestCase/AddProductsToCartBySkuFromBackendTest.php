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

use Mage\Customer\Test\Fixture\Customer;
use Mage\Sales\Test\Page\Adminhtml\SalesOrderIndex;
use Mage\Sales\Test\Page\Adminhtml\SalesOrderCreateIndex;

/**
 * Preconditions:
 * 1. Create customer.
 * 2. Create product.
 *
 * Steps:
 * 1. Login to backend.
 * 2. Open Sales > Orders.
 * 3. Click 'Create new order' button.
 * 4. Click 'Add Products By Sku' button.
 * 5. Fill data according data set.
 * 6. Click 'Add to order' button.
 * 7. Perform all asserts.
 *
 * @group Add_by_SKU_(CS)
 * @ZephyrId MPERF-7617
 */
class AddProductsToCartBySkuFromBackendTest extends AbstractAdvancedCheckoutEntityTest
{
    /**
     * Sales order create index page.
     *
     * @var SalesOrderCreateIndex
     */
    protected $orderCreateIndex;

    /**
     * Sales order index page.
     *
     * @var SalesOrderIndex
     */
    protected $orderIndex;

    /**
     * Injection data.
     *
     * @param SalesOrderIndex $orderIndex
     * @param SalesOrderCreateIndex $orderCreateIndex
     * @return void
     */
    public function __inject(SalesOrderIndex $orderIndex, SalesOrderCreateIndex $orderCreateIndex)
    {
        $this->orderIndex = $orderIndex;
        $this->orderCreateIndex = $orderCreateIndex;
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
     * Add products by SKU on backend.
     *
     * @param Customer $customer
     * @param array $orderOptions
     * @param string $cartBlock
     * @param string|null $products
     * @return array
     */
    public function test(Customer $customer, array $orderOptions, $cartBlock, $products = null)
    {
        // Preconditions
        $products = $this->createProducts($products);
        $orderOptions = $this->prepareOrderOptions($products, $orderOptions);
        // Steps
        $this->orderIndex->open();
        $this->orderIndex->getPageActionsBlock()->addNew();
        $this->orderCreateIndex->getCustomerGrid()->selectCustomer($customer);
        $this->orderCreateIndex->getStoreBlock()->selectStoreView();
        $orderCreateBlock = $this->orderCreateIndex->getAdvancedOrderCreateBlock();
        $orderCreateBlock->getItemsBlock()->clickAddProductsBySku();
        $orderCreateBlock->getAddToOrderBySkuBlock()->getOrderBySkuForm()->fillForm($orderOptions);
        $this->orderCreateIndex->getAdvancedOrderCreateBlock()->getAddToOrderBySkuBlock()->addToCart();

        $filteredProducts = $this->filterProducts($products, $cartBlock);

        return [
            'products' => isset($filteredProducts['cart']) ? $filteredProducts['cart'] : [],
            'requiredAttentionProducts' => isset($filteredProducts['required_attention'])
                ? $filteredProducts['required_attention']
                : []
        ];
    }
}
