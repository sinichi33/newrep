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

namespace Enterprise\GiftRegistry\Test\Constraint;

use Mage\Customer\Test\Fixture\Customer;
use Mage\Customer\Test\Page\CustomerAccountIndex;
use Enterprise\GiftRegistry\Test\Fixture\GiftRegistryType;
use Enterprise\GiftRegistry\Test\Page\GiftRegistryAddSelect;
use Enterprise\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that created gift registry type can be found on frontend.
 */
class AssertGiftRegistryTypeOnFrontend extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'middle';
    /* end tags */

    /**
     * Assert that created gift registry type can be found on frontend.
     *
     * @param Customer $customer
     * @param GiftRegistryType $giftRegistryType
     * @param CustomerAccountIndex $customerAccountIndex
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryAddSelect $giftRegistryAddSelect
     * @return void
     */
    public function processAssert(
        Customer $customer,
        GiftRegistryType $giftRegistryType,
        CustomerAccountIndex $customerAccountIndex,
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryAddSelect $giftRegistryAddSelect
    ) {
        $this->objectManager->create(
            'Mage\Customer\Test\TestStep\LoginCustomerOnFrontendStep',
            ['customer' => $customer]
        )->run();
        $customerAccountIndex->getAccountNavigationBlock()->openNavigationItem("Gift Registry");
        $giftRegistryIndex->getGiftRegistryList()->addNew();
        \PHPUnit_Framework_Assert::assertTrue(
            $giftRegistryAddSelect->getGiftRegistryEditForm()->isGiftRegistryVisible($giftRegistryType),
            "Gift registry '{$giftRegistryType->getLabel()}' is not present on frontend."
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry type can be found on frontend';
    }
}
