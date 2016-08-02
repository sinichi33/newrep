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

use Enterprise\GiftRegistry\TEst\Fixture\GiftRegistryType;
use Enterprise\GiftRegistry\Test\Page\Adminhtml\BackendGiftRegistryIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that created gift registry type can be found at gift registry grid in backend.
 */
class AssertGiftRegistryTypeInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that created gift registry type can be found at gift registry grid in backend.
     *
     * @param BackendGiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryType $giftRegistryType
     * @return void
     */
    public function processAssert(BackendGiftRegistryIndex $giftRegistryIndex, GiftRegistryType $giftRegistryType)
    {
        $giftRegistryIndex->open();
        $filter = ['label' => $giftRegistryType->getLabel()];
        \PHPUnit_Framework_Assert::assertTrue(
            $giftRegistryIndex->getGiftRegistryGrid()->isRowVisible($filter),
            "Gift registry type '{$giftRegistryType->getLabel()}' is not present in grid."
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry type is present in gift registry type grid.';
    }
}
