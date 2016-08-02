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

namespace Enterprise\CatalogEvent\Test\Constraint;

use Mage\Catalog\Test\Fixture\CatalogProductSimple;
use Mage\Catalog\Test\Page\Category\CatalogCategoryView;
use Mage\Catalog\Test\Page\Product\CatalogProductView;
use Mage\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Mage\Adminhtml\Test\Page\Adminhtml\Cache;

/**
 * Assert that event block is absent on category/product pages.
 */
class AssertCatalogEventBlockIsAbsent extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that event block is absent on category/product pages.
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     * @param Cache $adminCache
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView,
        Cache $adminCache
    ) {
        // Flush cache
        $adminCache->open();
        $adminCache->getPageActions()->flushCacheStorage();
        $adminCache->getMessagesBlock()->waitSuccessMessage();

        $categoryName = $product->getCategoryIds()[0];
        $cmsIndex->open();
        $cmsIndex->getTopmenu()->selectCategory($categoryName);
        \PHPUnit_Framework_Assert::assertFalse(
            $catalogCategoryView->getEventBlock()->isVisible(),
            "Event block is present on Category page."
        );

        $cmsIndex->open();
        $cmsIndex->getTopmenu()->selectCategory($categoryName);
        $catalogCategoryView->getListProductBlock()->openProductViewPage($product->getName());
        \PHPUnit_Framework_Assert::assertFalse(
            $catalogProductView->getEventBlock()->isVisible(),
            "Event block is present on Product page."
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Event block is invisible on category/product pages';
    }
}
