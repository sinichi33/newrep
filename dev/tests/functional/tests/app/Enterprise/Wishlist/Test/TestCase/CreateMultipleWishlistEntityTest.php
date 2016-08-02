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

namespace Enterprise\Wishlist\Test\TestCase;

use Enterprise\Wishlist\Test\Fixture\Wishlist;
use Mage\Adminhtml\Test\Page\Adminhtml\Cache;
use Mage\Catalog\Test\Fixture\CatalogCategory;
use Mage\Customer\Test\Fixture\Customer;
use Mage\Customer\Test\Page\CustomerAccountIndex;
use Mage\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Enable Multiple Wishlist functionality & set "Number of Multiple Wish Lists = 3".
 * 2. Create Customer Account.
 *
 * Steps:
 * 1. Login to frontend as a Customer.
 * 2. Navigate to: My Account > My Wishlist.
 * 3. Start creating Wishlist.
 * 4. Fill in data according to attached data set.
 * 5. Perform appropriate assertions.
 *
 * @group Wishlist_(CS)
 * @ZephyrId MPERF-7548
 */
class CreateMultipleWishlistEntityTest extends Injectable
{
    /**
     * CustomerAccountIndex page.
     *
     * @var CustomerAccountIndex
     */
    protected $customerAccountIndex;

    /**
     * WishlistIndex page.
     *
     * @var WishlistIndex
     */
    protected $wishlistIndex;

    /**
     * Admin cache page.
     *
     * @var Cache
     */
    protected $cachePage;

    /**
     * Prepare configuration and create customer.
     *
     * @param Customer $customer
     * @param CatalogCategory $category
     * @return array
     */
    public function __prepare(Customer $customer, CatalogCategory $category)
    {
        $customer->persist();
        $category->persist();

        return ['customer' => $customer, 'category' => $category];
    }

    /**
     * Inject pages.
     *
     * @param CustomerAccountIndex $customerAccountIndex
     * @param WishlistIndex $wishlistIndex
     * @param Cache $cachePage
     * @return void
     */
    public function __inject(CustomerAccountIndex $customerAccountIndex, WishlistIndex $wishlistIndex, Cache $cachePage)
    {
        $this->customerAccountIndex = $customerAccountIndex;
        $this->wishlistIndex = $wishlistIndex;
        $this->cachePage = $cachePage;
    }

    /**
     * Run Create multiple wishlist entity test.
     *
     * @param Customer $customer
     * @param Wishlist $wishlist
     * @return void
     */
    public function test(Customer $customer, Wishlist $wishlist)
    {
        // Prepare widget for assertions
        $this->setupConfiguration();
        $this->createWishlistSearchWidget();

        // Steps:
        $this->objectManager->create(
            'Mage\Customer\Test\TestStep\LoginCustomerOnFrontendStep',
            ['customer' => $customer]
        )->run();
        $this->customerAccountIndex->getAccountNavigationBlock()->openNavigationItem('My Wishlists');
        $this->wishlistIndex->getManagementBlock()->clickCreateNewWishlist();
        $this->wishlistIndex->getBehaviourBlock()->fillWishlist($wishlist);
    }

    /**
     * Add wish list search widget.
     *
     * @return void
     */
    protected function createWishlistSearchWidget()
    {
        $wishlistSearch = $this->objectManager->create(
            'Enterprise\Wishlist\Test\Fixture\WishlistWidget',
            ['dataSet' => 'add_search']
        );
        $wishlistSearch->persist();
        $this->cachePage->open()->getPageActions()->flushCacheStorage();
    }

    /**
     * Setup configuration.
     *
     * @param bool $rollback [optional]
     * @return void
     */
    public function setupConfiguration($rollback = false)
    {
        $this->objectManager->create(
            'Mage\Core\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'enable_multiple_wishlist', 'rollback' => $rollback]
        )->run();
    }

    /**
     * Inactive multiple wishlist in config and delete wishlist search widget.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->setupConfiguration(true);
        $this->objectManager->create('Mage\Widget\Test\TestStep\DeleteAllWidgetsStep')->run();
        $this->cachePage->open()->getPageActions()->flushCacheStorage();
    }
}
