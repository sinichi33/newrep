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

namespace Mage\Sales\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Create customer.
 * 2. Create product.
 * 3. Add product to cart.
 *
 * Steps:
 * 1. Open Customers -> All Customers.
 * 2. Search and open customer from preconditions.
 * 3. Click Create Order.
 * 4. Check product in Shopping Cart section.
 * 5. Click Update Changes.
 * 6. Perform all assertions.
 *
 * @group Order_Management_(CS)
 * @ZephyrId MPERF-7553
 */
class MoveShoppingCartProductsOnOrderPageTest extends Scenario
{
    /**
     * Create order from customer page(cartActions).
     *
     * @return array
     */
    public function test()
    {
        $this->executeScenario();
    }
}
