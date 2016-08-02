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

namespace Enterprise\CustomerSegment\Test\Constraint;

use Mage\Customer\Test\Fixture\Customer;
use Enterprise\CustomerSegment\Test\Fixture\CustomerSegment;
use Enterprise\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Enterprise\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Enterprise\CustomerSegment\Test\Block\Adminhtml\Customersegment\Edit\CustomerSegmentForm;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that grid on 'Matched Customer' tab contains customer according to conditions(it need save condition before
 * verification), assert number of matched customer near 'Matched Customer(%number%)' should be equal row in grid.
 */
class AssertCustomerSegmentMatchedCustomer extends AbstractConstraint
{
    /**
     * Assert that grid on 'Matched Customer' tab contains customer according to conditions.
     *
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @return void
     */
    public function processAssert(
        Customer $customer,
        CustomerSegment $customerSegment,
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew
    ) {
        $customerSegmentIndex->open();
        /** @var CustomerSegmentForm $formTabs */
        $formTabs = $customerSegmentNew->getCustomerSegmentForm();
        $customerSegmentIndex->getGrid()->searchAndOpen(['grid_segment_name' => $customerSegment->getName()]);
        $customerSegmentGrid = $formTabs->getMatchedCustomers()->getCustomersGrid();
        $formTabs->openTab('matched_customers');
        \PHPUnit_Framework_Assert::assertTrue(
            $customerSegmentGrid->isRowVisible(['grid_email' => $customer->getEmail()]),
            'Customer is absent in grid.'
        );
        $customerSegmentGrid->resetFilter();
        $totalOnTab = $formTabs->getNumberOfCustomersOnTabs();
        $totalInGrid = $customerSegmentGrid->getTotalRecords();
        \PHPUnit_Framework_Assert::assertEquals($totalInGrid, $totalOnTab, 'Wrong count of records is displayed.');
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer is present in Customer Segment grid. Number of matched customer equals to rows in grid.';
    }
}
