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

namespace Enterprise\Rma\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Enable RMA on Frontend (Configuration - Sales - RMA Settings).
 * 2. Create customer.
 * 3. Create product.
 * 4. Create Order.
 * 5. Create shipping.
 *
 * Steps:
 * 1. Go to frontend and logout if you log in already.
 * 2. Click on "Orders and Returns" link in the footer.
 * 3. Fill Order and Returns form with Test Data.
 * 4. Click "Continue".
 * 5. Click 'Return' link.
 * 6. Fill form 'Return Items Information'.
 * 7. Click 'Submit'.
 * 8. Perform all assertions.
 *
 * @group RMA_(CS)
 * @ZephyrId MPERF-7540
 */
class CreateRmaEntityOnFrontendTest extends Scenario
{
    /**
     * Setup configuration.
     *
     * @return void
     */
    public function __prepare()
    {
        $this->objectManager->create(
            'Mage\Core\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'rma_enable_on_frontend']
        )->run();
    }

    /**
     * Run test create Rma Entity from frontend.
     *
     * @return void
     */
    public function test()
    {
        $this->executeScenario();
    }
}
