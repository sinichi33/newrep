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

namespace Enterprise\GiftRegistry\Test\Block\Cart;

use Magento\Mtf\Block\Block;
use Enterprise\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\Mtf\Client\Locator;

/**
 * Frontend gift registry shopping cart block.
 */
class Link extends Block
{
    /**
     * Gift registry input selector.
     *
     * @var string
     */
    protected $giftRegistry = '[name="entity"]';

    /**
     * 'Add All To Gift Registry' button selector.
     *
     * @var string
     */
    protected $addToGiftRegistryButton = '//button[contains(.,"Gift Registry")]';

    /**
     * Gift registry option selector.
     *
     * @var string
     */
    protected $giftRegistryOption = '//select[@id="giftregistry_entity"]/option[contains(text(),"%s")]';

    /**
     * Add to gift registry.
     *
     * @param GiftRegistry $giftRegistry
     * @return void
     */
    public function addToGiftRegistry(GiftRegistry $giftRegistry)
    {
        $giftRegistryTitle = $giftRegistry->getTitle();
        $this->_rootElement->find($this->giftRegistry, Locator::SELECTOR_CSS, 'select')->setValue($giftRegistryTitle);
        $this->_rootElement->find($this->addToGiftRegistryButton, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Check that gift registry available in shopping cart.
     *
     * @param GiftRegistry $giftRegistry
     * @return bool
     */
    public function isGiftRegistryAvailable(GiftRegistry $giftRegistry)
    {
        $optionSelector = sprintf($this->giftRegistryOption, $giftRegistry->getTitle());
        return $this->_rootElement->find($optionSelector, Locator::SELECTOR_XPATH)->isVisible();
    }
}
