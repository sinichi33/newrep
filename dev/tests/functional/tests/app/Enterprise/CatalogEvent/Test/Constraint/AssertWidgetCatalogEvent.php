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

use Mage\Adminhtml\Test\Page\Adminhtml\Cache;
use Mage\Catalog\Test\Page\Category\CatalogCategoryView;
use Enterprise\CatalogEvent\Test\Fixture\CatalogEvent;
use Mage\Cms\Test\Page\CmsIndex;
use Enterprise\CatalogEvent\Test\Fixture\CatalogEventWidget;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that widget catalog event carousel is present on category page and link "Go To Sale" on widget redirects
 * you to category page.
 */
class AssertWidgetCatalogEvent extends AbstractConstraint
{
    /**
     * Assert that widget catalog event carousel is present on category page and link "Go To Sale" on widget redirects
     * you to category page.
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogEventWidget $widget
     * @param CatalogCategoryView $catalogCategoryView
     * @param Cache $adminCache
     * @param CatalogEvent $event1
     * @param CatalogEvent $event2
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogEventWidget $widget,
        CatalogCategoryView $catalogCategoryView,
        Cache $adminCache,
        CatalogEvent $event1,
        CatalogEvent $event2
    ) {
        // Flush cache
        $adminCache->open();
        $adminCache->getPageActions()->flushCacheStorage();
        $adminCache->getMessagesBlock()->waitSuccessMessage();

        $event1->persist();
        $event2->persist();
        $cmsIndex->open();
        $categoryName = $event2->getCategoryId();
        $cmsIndex->getTopmenu()->selectCategory($categoryName);
        $errors = $catalogCategoryView->getWidgetView()->checkWidget($widget, $categoryName);
        \PHPUnit_Framework_Assert::assertEmpty($errors, implode(" ", $errors));

        $cmsIndex->open();
        $cmsIndex->getWidgetView()->clickToWidget($widget, $categoryName);
        $pageTitle = $cmsIndex->getCmsPageBlock()->getPageTitle();
        $expected = strtolower($categoryName);
        $actual = strtolower($pageTitle);
        \PHPUnit_Framework_Assert::assertEquals(
            $expected,
            $actual,
            "Wrong page title on Category page: expected = '$expected', actual = '$actual'"
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Widget catalog event carousel is present on category page and" .
        " After click no event carousel widget link on frontend redirecting to Category page was success.";
    }
}
