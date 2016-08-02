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
use Magento\Mtf\TestCase\Injectable;

/**
 * Abstract class for AdvancedCheckoutEntity tests.
 */
abstract class AbstractAdvancedCheckoutEntityTest extends Injectable
{
    /**
     * Cms index page.
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Customer account index page.
     *
     * @var CustomerAccountIndex
     */
    protected $customerAccountIndex;

    /**
     * Customer order by SKU page.
     *
     * @var CustomerOrderSku
     */
    protected $customerOrderSku;

    /**
     * Checkout cart page.
     *
     * @var CheckoutCart
     */
    protected $checkoutCart;

    /**
     * Configuration data set name.
     *
     * @var string
     */
    protected $configuration;

    /**
     * Filter products.
     *
     * @param array $products
     * @param string $cartBlock
     * @return array
     */
    protected function filterProducts(array $products, $cartBlock)
    {
        $filteredProducts = [];
        $cartBlock = explode(',', $cartBlock);
        foreach ($cartBlock as $key => $value) {
            $filteredProducts[trim($value)][$key] = $products[$key];
        }

        return $filteredProducts;
    }

    /**
     * Create products.
     *
     * @param string $products [optional]
     * @return array
     */
    protected function createProducts($products = null)
    {
        return $products === null
            ? [null]
            : $this->objectManager->create(
                'Mage\Catalog\Test\TestStep\CreateProductsStep',
                ['products' => $products]
            )->run()['products'];
    }

    /**
     * Prepare order options.
     *
     * @param array $products
     * @param array $orderOptions
     * @return array
     */
    protected function prepareOrderOptions(array $products, array $orderOptions)
    {
        foreach ($orderOptions as $key => $value) {
            $options = explode(',', $value);
            foreach ($options as $item => $option) {
                $orderOptions[$item][$key] = trim($option);
            }
            unset($orderOptions[$key]);
        }

        foreach ($products as $key => $product) {
            $productSku = $product === null
                ? $productSku = $orderOptions[$key]['sku']
                : $productSku = $product->getSku();
            $orderOptions[$key]['sku'] = $orderOptions[$key]['sku'] === 'simpleWithOptionCompoundSku'
                ? $productSku . '-' . $product->getCustomOptions()[0]['options'][0]['sku']
                : $productSku;
        }

        return $orderOptions;
    }

    /**
     * Setup configuration.
     *
     * @param bool $rollback
     * @return void
     */
    protected function setupConfiguration($rollback = false)
    {
        $this->objectManager->create(
            'Mage\Core\Test\TestStep\SetupConfigurationStep',
            ['configData' => $this->configuration, 'rollback' => $rollback]
        )->run();
    }

    /**
     * Login customer.
     *
     * @param Customer $customer
     * @return void
     */
    protected function loginCustomer(Customer $customer)
    {
        $this->objectManager->create(
            'Mage\Customer\Test\TestStep\LoginCustomerOnFrontendStep',
            ['customer' => $customer]
        )->run();
    }
}
