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

namespace Enterprise\Wishlist\Test\TestStep;

use Enterprise\Wishlist\Test\Fixture\Wishlist;
use Mage\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Add products to multiple wishlist step.
 */
class AddProductsToMultipleWishlistStep implements TestStepInterface
{
    /**
     * Multiple wishlist fixture.
     *
     * @var Wishlist
     */
    protected $multipleWishlist;

    /**
     * Array of products fixtures.
     *
     * @var array
     */
    protected $products;

    /**
     * Flag for duplicate.
     *
     * @var bool[]
     */
    protected $duplicate;

    /**
     * Browser interface.
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * Catalog product view page.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Preparing step properties.
     *
     * @constructor
     * @param Wishlist $multipleWishlist
     * @param BrowserInterface $browser
     * @param CatalogProductView $catalogProductView
     * @param array $products
     * @param bool[] $duplicate
     */
    public function __construct(
        Wishlist $multipleWishlist,
        BrowserInterface $browser,
        CatalogProductView $catalogProductView,
        array $products,
        $duplicate
    ) {
        $this->multipleWishlist = $multipleWishlist;
        $this->products = $products;
        $this->duplicate = $duplicate;
        $this->browser = $browser;
        $this->catalogProductView = $catalogProductView;
    }

    /**
     * Create multiple wishlist.
     *
     * @return array
     */
    public function run()
    {
        foreach ($this->products as $key => $product) {
            $this->addToMultipleWishlist($product, $this->duplicate[$key]);
            if ($this->duplicate[$key]) {
                $this->addToMultipleWishlist($product, true);
            }
        }
    }

    /**
     * Add product to multiple wish list.
     *
     * @param InjectableFixture $product
     * @param bool $isDuplicate [optional]
     * @return void
     */
    protected function addToMultipleWishlist(InjectableFixture $product, $isDuplicate = false)
    {
        $this->browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $this->catalogProductView->getViewBlock()->fillOptions($product);
        $checkoutData = $product->getCheckoutData();
        if (isset($checkoutData['qty'])) {
            $qty = $isDuplicate ? $checkoutData['qty'] / 2 : $checkoutData['qty'];
            $this->catalogProductView->getViewBlock()->setQty($qty);
        }
        $this->catalogProductView->getMultipleWishlistViewBlock()->addToMultipleWishlist($this->multipleWishlist);
    }
}
