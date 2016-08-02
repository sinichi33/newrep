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
 * Check visible/invisible Event block on catalog page/product pages.
 */
class AssertCatalogEventBlockOnFrontend extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

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
     * Product Page on Frontend.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Category Name.
     *
     * @var string
     */
    protected $categoryName;

    /**
     * Product name.
     *
     * @var string
     */
    protected $productName;

    /**
     * Assert that Event block is visible/invisible on page according to fixture(catalog page/product pages).
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogEvent $catalogEvent
     * @param CatalogCategoryView $catalogCategoryView
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogEvent $catalogEvent,
        CatalogCategoryView $catalogCategoryView,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView
    ) {
        $this->catalogCategoryView = $catalogCategoryView;
        $this->cmsIndex = $cmsIndex;
        $this->catalogProductView = $catalogProductView;

        $this->categoryName = $product->getDatafieldConfig('category_ids')['source']->getProductCategory()
            ->getName();
        $this->productName = $product->getName();

        $catalogEventData = $catalogEvent->getData();
        $this->checkEvent($catalogEventData['display_state']);
    }

    /**
     * Check pageEvent.
     *
     * @param array $pageEvent
     * @return void
     */
    protected function checkEvent(array $pageEvent)
    {
        foreach ($pageEvent as $page => $value) {
            $value == 'Yes' ? $this->checkEventBlockOnPage($page) : $this->checkEventBlockOnPage($page, false);
        }
    }

    /**
     * Check event block on visibility on specified page.
     *
     * @param string $page
     * @param bool $positive [optional]
     * @return void
     */
    protected function checkEventBlockOnPage($page, $positive = true)
    {

        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategory($this->categoryName);
        if ($page == 'product_page') {
            $this->catalogCategoryView->getListProductBlock()->openProductViewPage($this->productName);
        }
        $eventBlockVisibility = $this->catalogProductView->getEventBlock()->isVisible();
        $positive == true
            ? \PHPUnit_Framework_Assert::assertTrue($eventBlockVisibility, "EventBlock is absent on $page page.")
            : \PHPUnit_Framework_Assert::assertFalse($eventBlockVisibility, "EventBlock is present on $page page.")
        ;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Event block is visible/invisible on catalog/product pages according to fixture.';
    }
}
