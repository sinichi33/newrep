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

use Enterprise\CustomerSegment\Test\Fixture\CustomerSegment;
use Enterprise\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Enterprise\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Assert that displayed segment data on edit page is equals passed from fixture.
 */
class AssertCustomerSegmentForm extends AbstractAssertForm
{
    /**
     * Skipped fields for verify data.
     *
     * @var array
     */
    protected $skippedFields = ['conditions_serialized', 'segment_id'];

    /**
     * Assert that displayed segment data on edit page is equals passed from fixture.
     *
     * @param CustomerSegment $customerSegment
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @return void
     */
    public function processAssert(
        CustomerSegment $customerSegment,
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew
    ) {
        $customerSegmentIndex->open();
        $customerSegmentIndex->getGrid()->searchAndOpen(['grid_segment_name' => $customerSegment->getName()]);

        $formData = $customerSegmentNew->getCustomerSegmentForm()->getData();
        $dataDiff = $this->verifyData($customerSegment->getData(), $formData, false, false);
        \PHPUnit_Framework_Assert::assertEmpty(
            $dataDiff,
            "Customer Segments data not equals to passed from fixture.\n Log:\n" . implode(";\n", $dataDiff)
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Segments page data on edit page equals data from fixture.';
    }
}
