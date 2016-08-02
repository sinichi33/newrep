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

namespace Enterprise\GiftRegistry\Test\Block\Customer;

use Magento\Mtf\Block\Block;
use Enterprise\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\Mtf\Client\Locator;

/**
 * Gift registry list block.
 */
class GiftRegistryList extends Block
{
    /**
     * "Add New" button selector.
     *
     * @var string
     */
    protected $addNewButton = "button[onclick*='addselect']";

    /**
     * Gift registry event selector in list.
     *
     * @var string
     */
    protected $eventSelector = 'tr td[title="%s"]';

    /**
     * Gift registry event action selector.
     *
     * @var string
     */
    protected $eventActionSelector = '//tr[td[contains(.,"%s")]]//a[contains(.,"%s")]';

    /**
     * Click on "Add New" button.
     *
     * @return void
     */
    public function addNew()
    {
        $this->_rootElement->find($this->addNewButton)->click();
    }

    /**
     * Check gift registry visibility in list.
     *
     * @param GiftRegistry $giftRegistry
     * @return bool
     */
    public function isGiftRegistryInGrid(GiftRegistry $giftRegistry)
    {
        return $this->_rootElement->find(sprintf($this->eventSelector, $giftRegistry->getTitle()))->isVisible();
    }

    /**
     * Click to action in appropriate gift registry event.
     *
     * @param string $event
     * @param string $action
     * @param bool $acceptAlert [optional]
     * @return void
     */
    public function eventAction($event, $action, $acceptAlert = false)
    {
        $selector = sprintf($this->eventActionSelector, $event, $action);
        $this->_rootElement->find($selector, Locator::SELECTOR_XPATH)->click();
        if ($acceptAlert) {
            $this->browser->acceptAlert();
        }
    }
}
