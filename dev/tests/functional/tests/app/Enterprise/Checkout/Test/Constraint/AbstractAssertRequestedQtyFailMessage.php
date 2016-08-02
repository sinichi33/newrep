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

use Mage\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Abstract assert that after adding products by sku, wrong requested quantity error message appears.
 */
abstract class AbstractAssertRequestedQtyFailMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Error requested quantity message.
     */
    const ERROR_QUANTITY_MESSAGE = 'The product cannot be added to cart in requested quantity.';

    /**
     * Allowed qty message.
     *
     * @var string
     */
    protected $allowedQtyMessage = '';

    /**
     * Sale qty type.
     *
     * @var string
     */
    protected $saleQtyType = '';

    /**
     * Assert that after adding products by sku, wrong requested quantity error message appears.
     *
     * @param CheckoutCart $checkoutCart
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, array $requiredAttentionProducts)
    {
        foreach ($requiredAttentionProducts as $product) {
            $currentMessage = $checkoutCart->getAdvancedCheckoutCart()->getFailedItemErrorMessage($product);
            \PHPUnit_Framework_Assert::assertContains(
                self::ERROR_QUANTITY_MESSAGE,
                $currentMessage
            );
            \PHPUnit_Framework_Assert::assertContains(
                sprintf($this->allowedQtyMessage, $product->getStockData()[$this->saleQtyType]),
                $currentMessage
            );
        }
    }
}
