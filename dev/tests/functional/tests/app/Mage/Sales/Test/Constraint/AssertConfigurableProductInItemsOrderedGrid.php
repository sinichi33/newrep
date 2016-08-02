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

namespace Mage\Sales\Test\Constraint;

use Mage\Catalog\Test\Fixture\ConfigurableProduct;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Assert configurable product was added to Items Ordered grid in customer account on Order creation page backend.
 */
class AssertConfigurableProductInItemsOrderedGrid extends AssertProductInItemsOrderedGrid
{
    /**
     * Get configurable product price.
     *
     * @param InjectableFixture $product
     * @return int
     */
    protected function getProductPrice(InjectableFixture $product)
    {
        $price = $product->getPrice();
        if (!$this->productsIsConfigured) {
            return $price;
        }
        $checkoutData = $product->getCheckoutData();
        if ($checkoutData === null) {
            return 0;
        }
        $attributesData = $product->getConfigurableOptions()['attributes_data'];
        foreach ($checkoutData['options']['configurable_options'] as $option) {
            $itemOption = $attributesData[$option['title']]['options'][$option['value']];
            $price += $itemOption['price_type'] == 'Fixed'
                ? $itemOption['price']
                : $product->getPrice() / 100 * $itemOption['price'];
        }

        return $price;
    }
}
