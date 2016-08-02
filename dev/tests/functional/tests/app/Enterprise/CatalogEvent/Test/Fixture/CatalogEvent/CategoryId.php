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

namespace Enterprise\CatalogEvent\Test\Fixture\CatalogEvent;

use Mage\Catalog\Test\Fixture\CatalogCategory;
use Mage\Catalog\Test\Fixture\CatalogProductSimple\CategoryIds;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Create and return Category.
 */
class CategoryId extends CategoryIds
{
    /**
     * Names and Ids of the created categories.
     *
     * @var array
     */
    protected $data;

    /**
     * Fixtures of category.
     *
     * @var CatalogCategory
     */
    protected $category;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param int|string $data
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, $data)
    {
        $this->params = $params;
        if (!empty($data['presets'])) {
            $preset = $data['presets'];
            $category = $fixtureFactory->createByCode('catalogCategory', ['dataSet' => $preset]);
            $category->persist();

            /** @var CatalogCategory $category */
            $this->data = $category->getName();
            $this->category = $category;
        } elseif (isset($data['category']) && $data['category'] instanceof CatalogCategory) {
            $this->data = $data['category']->getName();
            $this->category = $data['category'];
        } else {
            $this->data['id'] = $data;
        }
    }

    /**
     * Return category.
     *
     * @return CatalogCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Return category array.
     *
     * @return void
     */
    public function getCategories()
    {
        //
    }

    /**
     * Get product category.
     *
     * @return void
     */
    public function getProductCategory()
    {
        //
    }

    /**
     * Get id of categories.
     *
     * @return void
     */
    public function getIds()
    {
        //
    }
}
