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
use Enterprise\CatalogEvent\Test\Fixture\CatalogEvent;
use Mage\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check event status on category/product pages.
 */
abstract class AbstactAssertCatalogEventStatus extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Catalog Event status.
     *
     * @var string
     */
    protected $eventStatus = '';

    /**
     * Category Page on Frontend.
     *
     * @var CatalogCategoryView
     */
    protected $catalogCategoryView;

    /**
     * Index Page on Frontend.
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Product simple fixture.
     *
     * @var CatalogProductSimple
     */
    protected $product;

    /**
     * Product Page on Frontend.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Assert that Event block has $eventStatus.
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param CatalogEvent $catalogEvent
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        CatalogEvent $catalogEvent,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView
    ) {
        $this->catalogCategoryView = $catalogCategoryView;
        $this->cmsIndex = $cmsIndex;
        $this->product = $product;
        $this->catalogProductView = $catalogProductView;

        $pageEvent = $catalogEvent->getDisplayState();
        if ($pageEvent['category_page'] == "Yes") {
            $this->checkEventStatusOnCategoryPage();
        }
        if ($pageEvent['product_page'] == "Yes") {
            $this->checkEventStatusOnProductPage();
        }
    }

    /**
     * Event block has $this->eventStatus on Category Page.
     *
     * @return void
     */
    protected function checkEventStatusOnCategoryPage()
    {
        $categoryName = $this->product->getDatafieldConfig('category_ids')['source']->getProductCategory()->getName();
        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategory($categoryName);
        \PHPUnit_Framework_Assert::assertEquals(
            $this->eventStatus,
            $this->catalogCategoryView->getEventBlock()->getEventStatus(),
            'Wrong event status is displayed.'
        );
    }

    /**
     * Event block has $this->eventStatus on Product Page.
     *
     * @return void
     */
    protected function checkEventStatusOnProductPage()
    {
        $categoryName = $this->product->getDatafieldConfig('category_ids')['source']->getProductCategory()->getName();
        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategory($categoryName);
        $this->catalogCategoryView->getListProductBlock()->openProductViewPage($this->product->getName());
        \PHPUnit_Framework_Assert::assertEquals(
            $this->eventStatus,
            $this->catalogProductView->getEventBlock()->getEventStatus(),
            'Wrong event status is displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "$this->eventStatus status is present.";
    }
}
