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

namespace Enterprise\CatalogEvent\Test\TestCase;

use Mage\Catalog\Test\Fixture\CatalogCategory;
use Mage\Catalog\Test\Fixture\CatalogProductSimple;
use Enterprise\CatalogEvent\Test\Fixture\CatalogEvent;
use Enterprise\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;
use Enterprise\CatalogEvent\Test\Page\Adminhtml\CatalogEventNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create product.
 *
 * Steps:
 * 1. Log in to backend as admin user.
 * 2. Navigate to Catalog -> Categories -> Catalog Events.
 * 3. Start new Event creation.
 * 4. Choose category from precondition.
 * 5. Fill in all data according to data set.
 * 6. Save Event.
 * 7. Perform all assertions.
 *
 * @group Catalog_Events_(MX)
 * @ZephyrId MPERF-7574
 */
class CreateCatalogEventEntityFromCatalogEventPageTest extends Injectable
{
    /**
     * Catalog Event New page.
     *
     * @var CatalogEventNew
     */
    protected $catalogEventNew;

    /**
     * Catalog Event Index page.
     *
     * @var CatalogEventIndex
     */
    protected $catalogEventIndex;

    /**
     * Inject pages.
     *
     * @param CatalogEventIndex $catalogEventIndex
     * @param CatalogEventNew $catalogEventNew
     * @return void
     */
    public function __inject(CatalogEventIndex $catalogEventIndex, CatalogEventNew $catalogEventNew)
    {
        $this->catalogEventIndex = $catalogEventIndex;
        $this->catalogEventNew = $catalogEventNew;
    }

    /**
     * Create Catalog Event Entity from Catalog Event page.
     *
     * @param CatalogEvent $catalogEvent
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function test(CatalogEvent $catalogEvent, FixtureFactory $fixtureFactory)
    {
        //Preconditions:
        /**@var CatalogProductSimple $product */
        $product = $fixtureFactory->createByCode(
            'catalogProductSimple',
            ['dataSet' => 'product_with_category']
        );
        $product->persist();

        //Steps:
        $this->catalogEventIndex->open();
        $this->catalogEventIndex->getPageActions()->addNew();
        /** @var CatalogCategory $category */
        $category = $product->getDatafieldConfig('category_ids')['source']->getProductCategory();
        $this->catalogEventNew->getTreeCategories()->selectCategory($category->getPath() . '/' . $category->getName());
        $this->catalogEventNew->getEventForm()->fill($catalogEvent);
        $this->catalogEventNew->getPageActions()->save();

        return ['product' => $product];
    }
}
