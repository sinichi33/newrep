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

namespace Enterprise\Checkout\Test\Block\Adminhtml\Sales\AdvancedOrder\Create\Sku;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Block\Block;
use Magento\Mtf\Fixture\FixtureInterface;
use Enterprise\Checkout\Test\Block\Adminhtml\Sales\Sku\Errors\Grid\Description;

/**
 * Backend Order By SKU error block.
 */
class Errors extends Block
{
    /**
     * Error message locator.
     *
     * @var string
     */
    protected $skuErrors = ".sku-errors h4";

    /**
     * Failed item block selector.
     *
     * @var string
     */
    protected $failedItem = '//tr[.//*[contains(.,"%s")]]';

    /**
     * Get error message.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->_rootElement->find($this->skuErrors)->getText();
    }

    /**
     * Get failed item block.
     *
     * @param FixtureInterface $product
     * @return Description
     */
    public function getFailedItemBlock($product)
    {
        $item = $this->_rootElement->find(sprintf($this->failedItem, $product->getSku()), Locator::SELECTOR_XPATH);
        return $this->blockFactory->create(
            'Enterprise\Checkout\Test\Block\Adminhtml\Sales\Sku\Errors\Grid\Description',
            ['element' => $item]
        );
    }
}
