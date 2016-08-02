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

namespace Mage\Catalog\Test\TestCase\Product;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Two simple products are created.
 * 2. Configurable attribute with two options is created.
 * 3. Configurable attribute added to default template.
 * 4. Configurable product is created.
 *
 * Steps:
 * 1. Log in to backend.
 * 2. Open Catalog -> Manage Products.
 * 3. Search and open configurable product from preconditions.
 * 4. Fill in data according to dataSet.
 * 5. Save product.
 * 6. Perform all assertions.
 *
 * @group Configurable_Product_(MX)
 * @ZephyrId MPERF-7439
 */
class UpdateConfigurableProductEntityTest extends Scenario
{
    /**
     * Update configurable product.
     *
     * @return array
     */
    public function test()
    {
        $this->executeScenario();
    }
}
