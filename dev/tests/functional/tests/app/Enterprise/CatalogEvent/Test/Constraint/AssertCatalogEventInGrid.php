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
use Enterprise\CatalogEvent\Test\Fixture\CatalogEvent;
use Enterprise\CatalogEvent\Test\Page\Adminhtml\CatalogEventIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check catalog event is present in the "Events" grid.
 */
class AssertCatalogEventInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Catalog Event fixture.
     *
     * @var CatalogEvent
     */
    protected $catalogEvent;

    /**
     * Pages where event block is present.
     *
     * @var string
     */
    protected $catalogEventPages = '';

    /**
     * Assert that catalog event is presented in the "Events" grid.
     *
     * @param CatalogEvent $catalogEvent
     * @param CatalogProductSimple $product
     * @param CatalogEventIndex $catalogEventIndex
     * @return void
     */
    public function processAssert(
        CatalogEvent $catalogEvent,
        CatalogProductSimple $product,
        CatalogEventIndex $catalogEventIndex
    ) {
        $categoryName = $product->getDatafieldConfig('category_ids')['source']->getProductCategory()->getName();
        $dateStart = strtotime($catalogEvent->getDateStart());
        $dateEnd = strtotime($catalogEvent->getDateEnd());
        $this->catalogEvent = $catalogEvent->getData();
        if (!empty($this->catalogEvent['sort_order'])) {
            $sortOrder = ($this->catalogEvent['sort_order'] < 0) ? 0 : $this->catalogEvent['sort_order'];
        } else {
            $sortOrder = "";
        }

        $filter = [
            'category_name' => $categoryName,
            'start_on' => $this->prepareDate($dateStart),
            'end_on' => $this->prepareDate($dateEnd),
            'status' => $this->getStatus($dateStart, $dateEnd),
            'countdown_ticker' => $this->prepareDisplayStateForFilter(),
            'sort_order' => $sortOrder,
        ];
        $catalogEventIndex->getEventGrid()->search(['category_name' => $filter['category_name']]);
        \PHPUnit_Framework_Assert::assertTrue(
            $catalogEventIndex->getEventGrid()->isRowVisible($filter, false, false),
            "Event on Category Name $categoryName is absent in Events grid by next filter:\n" . print_r($filter, true)
        );
    }

    /**
     * Prepare data format.
     *
     * @param int $date
     * @return string
     */
    protected function prepareDate($date)
    {
        return strftime("%b %e, %Y", $date);
    }

    /**
     * Get status.
     *
     * @param int $dateStart
     * @param int $dateEnd
     * @return string
     */
    protected function getStatus($dateStart, $dateEnd)
    {
        $currentDay = strtotime('now');
        if ($currentDay < $dateStart) {
            $status = 'Upcoming';
        } elseif ($currentDay > $dateEnd) {
            $status = 'Closed';
        } else {
            $status = 'Open';
        }

        return $status;
    }

    /**
     * Method prepare string display state for filter.
     *
     * @return string
     */
    protected function prepareDisplayStateForFilter()
    {
        $event = 'Lister Block';
        $displayStates = [
            'category_page' => 'Category Page',
            'product_page' => 'Product Page',
        ];

        $pageEvents = $this->catalogEvent['display_state'];
        foreach ($pageEvents as $key => $pageEvent) {
            if ($pageEvent === 'Yes') {
                $event .= ', ' . $displayStates[$key];
            }
        }

        return $event;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Catalog Event is present in Event grid.';
    }
}
