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

namespace Enterprise\Wishlist\Test\Block\Customer\Wishlist;

use Enterprise\Wishlist\Test\Fixture\Wishlist;
use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Management wish list block on 'My Wish List' page.
 */
class Management extends Block
{
    /**
     * Button "Create New Wish List" selector.
     *
     * @var string
     */
    protected $addWishlist = '#wishlist-create-button';

    /**
     * Change button selector.
     *
     * @var string
     */
    protected $changeButton = 'a.change';

    /**
     * Wishlist entity selector.
     *
     * @var string
     */
    protected $wishlist = '//*[@class="list-container"]//li/a[contains(text(), "%s")]';

    /**
     * Create new wish list.
     *
     * @return void
     */
    public function clickCreateNewWishlist()
    {
        $this->_rootElement->find($this->addWishlist)->click();
    }

    /**
     * Is wishlist visible.
     *
     * @param Wishlist $wishlist
     * @return bool
     */
    public function isWishlistVisible(Wishlist $wishlist)
    {
        $this->_rootElement->find($this->changeButton)->click();

        return $this->_rootElement->find(sprintf($this->wishlist, $wishlist->getName()), Locator::SELECTOR_XPATH)
            ->isVisible();
    }

    /**
     * Select wish list.
     *
     * @param Wishlist $wishlist
     * @return void
     */
    public function selectWishlist(Wishlist $wishlist)
    {
        $this->_rootElement->find($this->changeButton)->click();
        $this->_rootElement->find(sprintf($this->wishlist, $wishlist->getName()), Locator::SELECTOR_XPATH)->click();
    }
}
