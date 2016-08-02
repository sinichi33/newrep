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

namespace Enterprise\Rma\Test\Block\Returns\Create;

use Enterprise\Rma\Test\Fixture\Rma;
use Magento\Mtf\Block\Block;
use Enterprise\Rma\Test\Block\Returns\Create\Items\Item;
use Magento\Mtf\Client\Locator;

/**
 * Rma items block.
 */
class Items extends Block
{
    /**
     * Selector for item block.
     *
     * @var string
     */
    protected $item = '//li[contains(@class,"fields")][%d]';

    /**
     * Selector for 'Add Item To Return' button.
     *
     * @var string
     */
    protected $addItemToReturn = '[onclick^="addRegistrant"]';

    /**
     * Fill items data.
     *
     * @param Rma $rma
     * @return void
     */
    public function fill(Rma $rma)
    {
        $items = $rma->getItems();
        $products = $rma->getDataFieldConfig('items')['source']->getProducts();
        foreach ($items as $key => $item) {
            $item['order_item_id'] = $products[$key]->getName();
            ++$key;
            $itemBlock = $this->getItemForm($key);
            if (!$itemBlock->isVisible()) {
                $this->clickAddItemToReturn();
            }
            $itemBlock->fillItem($item);
        }
    }

    /**
     * Get item form.
     *
     * @param int $key
     * @return Item
     */
    protected function getItemForm($key)
    {
        return $this->blockFactory->create(
            'Enterprise\Rma\Test\Block\Returns\Create\Items\Item',
            ['element' => $this->_rootElement->find(sprintf($this->item, $key), Locator::SELECTOR_XPATH)]
        );
    }

    /**
     * Click on 'Add Item To Return' button.
     *
     * @return void
     */
    protected function clickAddItemToReturn()
    {
        $this->_rootElement->find($this->addItemToReturn)->click();
    }
}
