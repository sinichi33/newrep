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

use Mage\Sales\Test\Page\Adminhtml\SalesOrderCreateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after adding products by sku to order, requested quantity is not available error message appears.
 */
class AssertQtyIsNotEnoughBackendFailMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Requested quantity is not available error message.
     */
    const ERROR_QUANTITY_MESSAGE = 'The requested quantity for "%s" is not available.';

    /**
     * Assert that requested quantity is not available error message is displayed after adding products to order by sku.
     *
     * @param SalesOrderCreateIndex $salesOrderCreateIndex
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(SalesOrderCreateIndex $salesOrderCreateIndex, array $requiredAttentionProducts)
    {
        foreach ($requiredAttentionProducts as $product) {
            $productName = $product->getName();
            \PHPUnit_Framework_Assert::assertEquals(
                sprintf(self::ERROR_QUANTITY_MESSAGE, $productName),
                $salesOrderCreateIndex->getAdvancedOrderCreateBlock()->getItemsBlock()
                    ->getItemProduct($productName)->getNoticeMessage()
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
        return 'Requested quantity is not available error message is present after adding products to order by sku.';
    }
}
