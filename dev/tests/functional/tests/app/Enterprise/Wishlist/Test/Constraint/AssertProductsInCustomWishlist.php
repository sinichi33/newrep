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

use Mage\Cms\Test\Page\CmsIndex;
use Enterprise\Wishlist\Test\Fixture\Wishlist;
use Mage\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Assert that products are present in custom wishlist.
 */
class AssertProductsInCustomWishlist extends AbstractConstraint
{
    /**
     * Wishlist index page.
     *
     * @var WishlistIndex
     */
    protected $wishlistIndex;

    /**
     * Assert that products are present in custom wishlist.
     *
     * @param CmsIndex $cmsIndex
     * @param Wishlist $multipleWishlist
     * @param WishlistIndex $wishlistIndex
     * @param InjectableFixture[] $products
     * @param int[]|null $qtyToAction
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        Wishlist $multipleWishlist,
        WishlistIndex $wishlistIndex,
        array $products,
        $qtyToAction = null
    ) {
        $this->wishlistIndex = $wishlistIndex;
        $cmsIndex->getTopLinksBlock()->openAccount();
        $cmsIndex->getLinksBlock()->openLink("My Wishlist");
        $wishlistIndex->getManagementBlock()->selectWishlist($multipleWishlist);
        foreach ($products as $key => $product) {
            $expectedQty = ($qtyToAction !== null) && isset($qtyToAction[$key]) ? $qtyToAction[$key] : null;
            $this->verifyItemProduct($product, $expectedQty);
        }
    }

    /**
     * Verify item product data.
     *
     * @param InjectableFixture $product
     * @param int|null $expectedQty
     * @return void
     */
    protected function verifyItemProduct(InjectableFixture $product, $expectedQty)
    {
        \PHPUnit_Framework_Assert::assertTrue(
            $this->wishlistIndex->getItemsBlock()->getItemProductBlock($product)->isVisible(),
            'Product "' . $product->getName() . '" in custom wishlist doesn\'t visible.'
        );

        if ($expectedQty !== null) {
            \PHPUnit_Framework_Assert::assertEquals(
                $expectedQty,
                $this->wishlistIndex->getItemsBlock()->getItemProductBlock($product)->getData()['qty'],
                'Actual quantity of "' . $product->getName() . '" in custom wishlist doesn\'t match to expected.'
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
        return 'Products with correct quantities are present in custom wishlist';
    }
}
