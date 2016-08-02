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

namespace Enterprise\Rma\Test\Block\Returns;

use Magento\Mtf\Block\Form;
use Enterprise\Rma\Test\Fixture\Rma;
use Magento\Mtf\Client\Element\SimpleElement as Element;
use Magento\Mtf\Fixture\FixtureInterface;
use Enterprise\Rma\Test\Block\Returns\Create\Items;

/**
 * Rma create form.
 */
class Create extends Form
{
    /**
     * Selector for submit button.
     *
     * @var string
     */
    protected $submit = '[id="submit.save"]';

    /**
     * Selector for items block.
     *
     * @var string
     */
    protected $items = '#registrant_options';

    /**
     * Fill the root form.
     *
     * @param FixtureInterface $rma
     * @param Element|null $element
     * @return $this
     */
    public function fill(FixtureInterface $rma, Element $element = null)
    {
        $this->getItemsBlock()->fill($rma);
        parent::fill($rma, $element);

        return $this;
    }

    /**
     * Click on submit button.
     *
     * @return void
     */
    public function submit()
    {
        $this->_rootElement->find($this->submit)->click();
    }

    /**
     * Get items block.
     *
     * @return Items
     */
    protected function getItemsBlock()
    {
        return $this->blockFactory->create(
            'Enterprise\Rma\Test\Block\Returns\Create\Items',
            ['element' => $this->_rootElement->find($this->items)]
        );
    }
}
