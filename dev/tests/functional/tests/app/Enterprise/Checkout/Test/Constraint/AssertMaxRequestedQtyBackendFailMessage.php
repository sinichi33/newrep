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

namespace Enterprise\Checkout\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Mage\Sales\Test\Page\Adminhtml\SalesOrderCreateIndex;

/**
 * Assert that after adding products by sku to order, requested quantity is more than allowed error message appears.
 */
class AssertMaxRequestedQtyBackendFailMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Error maximum quantity allowed message.
     */
    const ERROR_MESSAGE = 'The maximum quantity allowed for purchase is %d.';

    /**
     * Assert that after adding products by sku to order, requested quantity is more than allowed error message appears.
     *
     * @param SalesOrderCreateIndex $salesOrderCreateIndex
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(SalesOrderCreateIndex $salesOrderCreateIndex, array $requiredAttentionProducts)
    {
        foreach ($requiredAttentionProducts as $product) {
            \PHPUnit_Framework_Assert::assertEquals(
                sprintf(self::ERROR_MESSAGE, $product->getStockData()['max_sale_qty']),
                $salesOrderCreateIndex->getAdvancedOrderCreateBlock()->getItemsBlock()
                    ->getItemProduct($product->getName())->getErrorMessage()
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Requested quantity is more than allowed error message is present after adding products to order.';
    }
}
