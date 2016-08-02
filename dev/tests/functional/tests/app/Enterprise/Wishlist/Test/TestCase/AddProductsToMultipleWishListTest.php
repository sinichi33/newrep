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

use Magento\Mtf\ObjectManager;
use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Create Products.
 * 2. Enable Multiple Wishlist functionality.
 * 3. Create Customer Account.
 * 4. Create Wishlist.
 *
 * Steps:
 * 1. Login to frontend as a customer.
 * 2. Navigate to created product.
 * 3. Select created wishlist and add products to it.
 * 4. Perform appropriate assertions.
 *
 * @group Wishlists_(CS)
 * @ZephyrId MPERF-7572
 */
class AddProductsToMultipleWishListTest extends Scenario
{
    /**
     * Add Product to Multiple Wish list.
     *
     * @return void
     */
    public function test()
    {
        $this->executeScenario();
    }

    /**
     * Disable multiple wish list in config.
     *
     * @return void
     */
    public static function tearDownAfterClass()
    {
        ObjectManager::getInstance()->create(
            'Mage\Core\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'enable_multiple_wishlist', 'rollback' => true]
        )->run();
    }
}
