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

namespace Enterprise\Checkout\Test\Block\Sku\Products;

use Magento\Mtf\Block\Block;

/**
 * SKU failed information Block.
 */
class Info extends Block
{
    /**
     * Error message selector.
     *
     * @var string
     */
    protected $errorMessage = '.item-msg.error';

    /**
     * Specify products options link selector.
     *
     * @var string
     */
    protected $optionsLink = '.configure-popup';

    /**
     * Tier price message selector.
     *
     * @var string
     */
    protected $tierPriceMessage = '.tier-price';

    /**
     * MSRP notice selector.
     *
     * @var string
     */
    protected $msrp = '.cart-msrp-unit';

    /**
     * Get error message.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->_rootElement->find($this->errorMessage)->getText();
    }

    /**
     * Check that specify the product's options link is visible.
     *
     * @return bool
     */
    public function linkIsVisible()
    {
        return $this->_rootElement->find($this->optionsLink)->isVisible();
    }

    /**
     * Click specify the product's options link.
     *
     * @return void
     */
    public function clickOptionsLink()
    {
        $this->_rootElement->find($this->optionsLink)->click();
    }

    /**
     * Get tier price messages.
     *
     * @return array
     */
    public function getTierPriceMessages()
    {
        $messages = [];
        $elements = $this->_rootElement->getElements($this->tierPriceMessage);
        foreach ($elements as $key => $element) {
            $messages[$key] = $element->getText();
        }

        return $messages;
    }

    /**
     * Check that MSRP notice displayed.
     *
     * @return bool
     */
    public function isMsrpNoticeDisplayed()
    {
        return $this->_rootElement->find($this->msrp)->isVisible();
    }
}
