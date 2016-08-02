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

namespace Enterprise\Wishlist\Test\Block\Product;

use Enterprise\Wishlist\Test\Fixture\Wishlist;
use Magento\Mtf\Client\Locator;

/**
 * Multiple Wish list view block on the product page.
 */
class View extends \Mage\Catalog\Test\Block\Product\View
{
    /**
     * Add to Multiple Wishlist button.
     *
     * @var string
     */
    protected $addToMultipleWishlist = '.wishlist-selector a.change';

    /**
     * Item wish list.
     *
     * @var string
     */
    protected $wishlistItem = './/span[.="%s"]';

    /**
     * Add product to wishlist.
     *
     * @param Wishlist $multipleWishlist
     * @return void
     */
    public function addToMultipleWishlist(Wishlist $multipleWishlist)
    {
        $this->_rootElement->find($this->addToMultipleWishlist)->click();
        $this->_rootElement->find(sprintf($this->wishlistItem, $multipleWishlist->getName()), Locator::SELECTOR_XPATH)
            ->click();
    }
}
