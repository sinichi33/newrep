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

use Mage\Catalog\Test\Fixture\CatalogProductSimple;
use Mage\Catalog\Test\Fixture\CatalogProductSimple\CategoryIds;
use Enterprise\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;
use Enterprise\CatalogEvent\Test\Page\Adminhtml\CatalogEventNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create Product.
 * 2. Create Catalog event.
 *
 * Steps:
 * 1. Log in to backend as admin user.
 * 2. Navigate to Catalog -> Categories -> Catalog Events.
 * 3. Choose catalog event from precondition.
 * 4. Click "Delete" button.
 * 5. Perform all assertions.
 *
 * @group Catalog_Events_(MX)
 * @ZephyrId MPERF-7658
 */
class DeleteCatalogEventEntityTest extends Injectable
{
    /**
     * Catalog Event Page.
     *
     * @var CatalogEventNew
     */
    protected $catalogEventNew;

    /**
     * Event Page.
     *
     * @var CatalogEventIndex
     */
    protected $catalogEventIndex;

    /**
     * Prepare product, Catalog event and pages.
     *
     * @param CatalogEventNew $catalogEventNew
     * @param CatalogEventIndex $catalogEventIndex
     * @param FixtureFactory $fixtureFactory
     * @param CatalogProductSimple $product
     *
     * @return array
     */
    public function __inject(
        CatalogEventNew $catalogEventNew,
        CatalogEventIndex $catalogEventIndex,
        FixtureFactory $fixtureFactory,
        CatalogProductSimple $product
    ) {
        $this->catalogEventNew = $catalogEventNew;
        $this->catalogEventIndex = $catalogEventIndex;
        $product->persist();
        /** @var CategoryIds $sourceCategories */
        $sourceCategory = $product->getDataFieldConfig('category_ids')['source']->getCategories()[0];
        $catalogEvent = $fixtureFactory->createByCode(
            'catalogEvent',
            [
                'dataSet' => 'default_event',
                'data' => ['category_id' => $sourceCategory->getId()],
            ]
        );
        $catalogEvent->persist();

        return [
            'product' => $product,
            'catalogEvent' => $catalogEvent,
        ];
    }

    /**
     * Run delete Catalog Event test.
     *
     * @param CatalogProductSimple $product
     * @return void
     */
    public function test(CatalogProductSimple $product)
    {
        //Steps
        $this->catalogEventIndex->open();
        $this->catalogEventIndex->getEventGrid()->searchAndOpen(['category_name' => $product->getCategoryIds()[0]]);
        $this->catalogEventNew->getPageActions()->deleteAndAcceptAlert();
    }
}
