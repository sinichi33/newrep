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

namespace Enterprise\Checkout\Test\Block\Adminhtml\Sales\AdvancedOrder\Create;

use Magento\Mtf\Block\Block;
use Enterprise\Checkout\Test\Block\Adminhtml\Sales\AdvancedOrder\Create\Sku\Add;

/**
 * Backend Order By SKU block.
 */
class Sku extends Block
{
    /**
     * Add to order button selector.
     *
     * @var string
     */
    protected $addToOrder = '[onclick*="submitSkuForm"]';

    /**
     * Order by SKU form selector.
     *
     * @var string
     */
    protected $orderBySkuForm = ".add-by-sku";

    /**
     * Click 'Add to Order' button.
     *
     * @return void
     */
    public function addToCart()
    {
        $this->_rootElement->find($this->addToOrder)->click();
    }

    /**
     * Get order by sku form.
     *
     * @return Add
     */
    public function getOrderBySkuForm()
    {
        return $this->blockFactory->create(
            'Enterprise\Checkout\Test\Block\Adminhtml\Sales\AdvancedOrder\Create\Sku\Add',
            ['element' => $this->_rootElement->find($this->orderBySkuForm)]
        );
    }
}
