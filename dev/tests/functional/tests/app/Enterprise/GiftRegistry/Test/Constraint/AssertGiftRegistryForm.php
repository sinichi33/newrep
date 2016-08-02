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

use Enterprise\GiftRegistry\Test\Fixture\GiftRegistry;
use Enterprise\GiftRegistry\Test\Page\GiftRegistryEdit;
use Enterprise\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Assert that gift registry data on edit page equals passed from fixture.
 */
class AssertGiftRegistryForm extends AbstractAssertForm
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Skipped fields for verify data.
     *
     * @var array
     */
    protected $skippedFields = ['type_id'];

    /**
     * Assert that gift registry data on edit page equals passed from fixture.
     *
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryEdit $giftRegistryEdit
     * @param GiftRegistry $giftRegistry
     * @return void
     */
    public function processAssert(
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryEdit $giftRegistryEdit,
        GiftRegistry $giftRegistry
    ) {
        $giftRegistryIndex->open();
        $fixtureData = $giftRegistry->getData();
        $giftRegistryIndex->getGiftRegistryList()->eventAction($fixtureData['title'], 'Edit');
        $formData = $giftRegistryEdit->getGiftRegistryEditForm()->getData();
        $errors = $this->verifyData($fixtureData, $formData);
        \PHPUnit_Framework_Assert::assertEmpty($errors, $errors);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry data on edit page equals data from fixture.';
    }
}
