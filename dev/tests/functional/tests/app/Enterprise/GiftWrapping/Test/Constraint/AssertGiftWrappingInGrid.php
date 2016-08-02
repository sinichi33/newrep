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

namespace Enterprise\GiftWrapping\Test\Constraint;

use Enterprise\GiftWrapping\Test\Fixture\GiftWrapping;
use Enterprise\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert Gift Wrapping availability in Gift Wrapping grid.
 */
class AssertGiftWrappingInGrid extends AbstractConstraint
{
    /**
     * Assert Gift Wrapping availability in Gift Wrapping grid.
     *
     * @param GiftWrappingIndex $giftWrappingIndexPage
     * @param GiftWrapping $giftWrapping
     * @return void
     */
    public function processAssert(GiftWrappingIndex $giftWrappingIndexPage, GiftWrapping $giftWrapping)
    {
        $data = $giftWrapping->getData();
        $filter = $this->prepareFilter($data);
        $giftWrappingIndexPage->open();
        \PHPUnit_Framework_Assert::assertTrue(
            $giftWrappingIndexPage->getGiftWrappingGrid()->isRowVisible($filter, true, false),
            'Gift Wrapping \'' . $filter['design'] . '\' is absent in Gift Wrapping grid.'
        );
    }

    /**
     * Prepare filter.
     *
     * @param array $data
     * @return array
     */
    protected function prepareFilter(array $data)
    {
        $filter = [
            'design' => $data['design'],
            'status' => $data['status'],
            'base_price' => $data['base_price'],
        ];
        if(isset($data['website_ids'])){
            $filter['website_ids'] = reset($data['website_ids']);
        }

        return $filter;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping is present in grid.';
    }
}
