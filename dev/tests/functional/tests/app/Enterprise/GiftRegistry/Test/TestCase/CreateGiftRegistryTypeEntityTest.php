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

namespace Enterprise\GiftRegistry\Test\TestCase;

use Mage\Customer\Test\Fixture\Customer;
use Mage\Customer\Test\Page\CustomerAccountLogout;
use Enterprise\GiftRegistry\Test\Fixture\GiftRegistryType;
use Enterprise\GiftRegistry\Test\Page\Adminhtml\BackendGiftRegistryIndex;
use Enterprise\GiftRegistry\Test\Page\Adminhtml\GiftRegistryNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Steps:
 * 1. Log in to Backend.
 * 2. Navigate to Customers > Gift Registry.
 * 3. Click "Add Gift Registry Type".
 * 4. Fill data according to dataSet.
 * 5. Save gift registry.
 * 6. Perform all assertions.
 *
 * @group Gift_Registry_(CS)
 * @ZephyrId MPERF-7644
 */
class CreateGiftRegistryTypeEntityTest extends Injectable
{
    /**
     * Backend GiftRegistryIndex page.
     *
     * @var BackendGiftRegistryIndex
     */
    protected $giftRegistryIndex;

    /**
     * GiftRegistryNew page.
     *
     * @var GiftRegistryNew
     */
    protected $giftRegistryNew;

    /**
     * CustomerAccountLogout page.
     *
     * @var CustomerAccountLogout
     */
    protected $customerAccountLogout;

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
     * Preparing pages for test.
     *
     * @param BackendGiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryNew $giftRegistryNew
     * @param CustomerAccountLogout $customerAccountLogout
     * @return void
     */
    public function __inject(
        BackendGiftRegistryIndex $giftRegistryIndex,
        GiftRegistryNew $giftRegistryNew,
        CustomerAccountLogout $customerAccountLogout
    ) {
        $this->giftRegistryIndex = $giftRegistryIndex;
        $this->giftRegistryNew = $giftRegistryNew;
        $this->customerAccountLogout = $customerAccountLogout;
    }

    /**
     * Run create gift registry type entity test.
     *
     * @param GiftRegistryType $giftRegistryType
     * @return void
     */
    public function test(GiftRegistryType $giftRegistryType)
    {
        // Steps
        $this->giftRegistryIndex->open();
        $this->giftRegistryIndex->getPageActions()->addNew();
        $this->giftRegistryNew->getGiftRegistryForm()->fill($giftRegistryType);
        $this->giftRegistryNew->getPageActions()->save();
    }

    /**
     * Log out.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->customerAccountLogout->open();
    }
}
