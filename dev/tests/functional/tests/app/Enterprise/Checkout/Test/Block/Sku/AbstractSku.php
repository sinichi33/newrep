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

namespace Enterprise\Checkout\Test\Block\Sku;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;

/**
 * Order By SKU form.
 */
abstract class AbstractSku extends Form
{
    /**
     * Add to Cart button selector.
     *
     * @var string
     */
    protected $addToCart = '[id^="sku-submit-button"]';

    /**
     * Add new row button selector.
     *
     * @var string
     */
    protected $addRow = '[id^="add_new_item_button"]';

    /**
     * Row selector.
     *
     * @var string
     */
    protected $row = '//*[contains(@class,"order-row") and .//*[contains(@name,"items[%d_")]]';

    /**
     * Click Add to Cart button.
     *
     * @return void
     */
    public function addToCart()
    {
        $this->_rootElement->find($this->addToCart)->click();
    }

    /**
     * Fill order by SKU form.
     *
     * @param array $orderOptions
     * @return void
     */
    public function fillForm(array $orderOptions)
    {
        foreach ($orderOptions as $key => $value) {
            if ($key !== 0) {
                $this->_rootElement->find($this->addRow)->click();
            }
            $element = $this->_rootElement->find(sprintf($this->row, $key), Locator::SELECTOR_XPATH);
            $mapping = $this->dataMapping($value);
            $this->_fill($mapping, $element);
        }
    }
}
