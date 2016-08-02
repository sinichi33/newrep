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

namespace Enterprise\Checkout\Test\Block\Cart;

use Enterprise\Checkout\Test\Block\Sku\Products\Info;
use Mage\Checkout\Test\Block\Cart;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * AdvancedCheckout cart block.
 */
class AdvancedCheckoutCart extends Cart
{
    /**
     * Failed item block selector.
     *
     * @var string
     */
    protected $failedItem = '//tr[.//*[contains(.,"%s")]]';

    /**
     * Remove all failed items button selector.
     *
     * @var string
     */
    protected $removeAllFailedItems = '#remove_all_failed_items';

    /**
     * Get failed item block.
     *
     * @param FixtureInterface|string $product
     * @return Info
     */
    protected function getFailedItemBlock($product)
    {
        $item = $this->_rootElement->find(sprintf($this->failedItem, $product->getSku()), Locator::SELECTOR_XPATH);
        return $this->blockFactory->create('Enterprise\Checkout\Test\Block\Sku\Products\Info', ['element' => $item]);
    }

    /**
     * Get error message in failed item block.
     *
     * @param FixtureInterface $product
     * @return string
     */
    public function getFailedItemErrorMessage(FixtureInterface $product)
    {
        return $this->getFailedItemBlock($product)->getErrorMessage();
    }

    /**
     * Remove all failed items.
     *
     * @return void
     */
    public function removeAllFailedItems()
    {
        $removeAllFailedItemsButton = $this->_rootElement->find($this->removeAllFailedItems);
        if ($removeAllFailedItemsButton->isVisible()) {
            $removeAllFailedItemsButton->click();
        }
    }

    /**
     * Check that "Specify the product's options" link is visible.
     *
     * @param FixtureInterface $product
     * @return bool
     */
    public function specifyProductOptionsLinkIsVisible(FixtureInterface $product)
    {
        return $this->getFailedItemBlock($product)->linkIsVisible();
    }

    /**
     * Click "Specify the product's options" link.
     *
     * @param FixtureInterface $product
     * @return void
     */
    public function clickSpecifyProductOptionsLink(FixtureInterface $product)
    {
        $this->getFailedItemBlock($product)->clickOptionsLink();
    }

    /**
     * Get tier price messages in failed item block.
     *
     * @param FixtureInterface $product
     * @return array
     */
    public function getTierPriceMessages(FixtureInterface $product)
    {
        return $this->getFailedItemBlock($product)->getTierPriceMessages();
    }

    /**
     * Check that MSRP notice displayed in failed item block.
     *
     * @param FixtureInterface $product
     * @return bool
     */
    public function isMsrpNoticeDisplayed(FixtureInterface $product)
    {
        return $this->getFailedItemBlock($product)->isMsrpNoticeDisplayed();
    }
}
