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

namespace Mage\Adminhtml\Test\Block\Review\Edit;

use Mage\Adminhtml\Test\Block\Widget\Tab;
use Magento\Mtf\Client\Element\SimpleElement as Element;

/**
 * Review details tab.
 */
class ReviewDetails extends Tab
{
    /**
     * Selector for customer.
     *
     * @var string
     */
    protected $customer = '#customer';

    /**
     * Get data of tab.
     *
     * @param array|null $fields
     * @param Element|null $element
     * @return array
     */
    public function getDataFormTab($fields = null, Element $element = null)
    {
        return array_merge(parent::getDataFormTab($fields, $element), ['customer' => $this->getCustomer()]);
    }

    /**
     * Get customer.
     *
     * @return string
     */
    protected function getCustomer()
    {
        return $this->_rootElement->find($this->customer)->getText();
    }
}
