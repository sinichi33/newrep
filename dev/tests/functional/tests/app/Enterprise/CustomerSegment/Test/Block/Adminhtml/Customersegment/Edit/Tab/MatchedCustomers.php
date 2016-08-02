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

namespace Enterprise\CustomerSegment\Test\Block\Adminhtml\Customersegment\Edit\Tab;

use Mage\Adminhtml\Test\Block\Widget\Tab;
use Magento\Mtf\Client\Element;
use Enterprise\CustomerSegment\Test\Block\Adminhtml\Report\Customer\Segment\DetailGrid;

/**
 * Matched customers tab.
 */
class MatchedCustomers extends Tab
{
    /**
     * Customer grid mapping.
     *
     * @var string
     */
    protected $gridPath = '#segmentGrid';

    /**
     * Get Customer Segment edit form.
     *
     * @return DetailGrid
     */
    public function getCustomersGrid()
    {
        return $this->blockFactory->create(
            'Enterprise\CustomerSegment\Test\Block\Adminhtml\Report\Customer\Segment\DetailGrid',
            ['element' => $this->_rootElement->find($this->gridPath)]
        );
    }
}
