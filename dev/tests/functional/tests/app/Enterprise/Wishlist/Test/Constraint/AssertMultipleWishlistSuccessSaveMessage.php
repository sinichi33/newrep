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

namespace Enterprise\Wishlist\Test\Constraint;

use Enterprise\Wishlist\Test\Fixture\Wishlist;
use Mage\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that multiple wish list success save message is displayed.
 */
class AssertMultipleWishlistSuccessSaveMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Success save message.
     */
    const SUCCESS_SAVE_MESSAGE = 'Wishlist "%s" was successfully saved';

    /**
     * Assert that multiple wish list success save message is displayed.
     *
     * @param WishlistIndex $wishlistIndex
     * @param Wishlist $wishlist
     * @return void
     */
    public function processAssert(WishlistIndex $wishlistIndex, Wishlist $wishlist)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_SAVE_MESSAGE, $wishlist->getName()),
            $wishlistIndex->getMessagesBlock()->getSuccessMessages()
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Multiple wish list success save message is present.';
    }
}
