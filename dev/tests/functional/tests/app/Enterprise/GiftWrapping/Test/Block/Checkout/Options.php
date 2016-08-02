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

namespace Enterprise\GiftWrapping\Test\Block\Checkout;

use Enterprise\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Block\Block;

/**
 * Gift options block on shipping method step on one page checkout frontend.
 */
class Options extends Block
{
    /**
     * Add gift options.
     *
     * @var string
     */
    protected $allowGiftOptions = 'input[name="allow_gift_options"]';

    /**
     * Gift Options for individual items.
     *
     * @var string
     */
    protected $allowGiftOptionsForItems = 'input[name="allow_gift_messages_for_order"]';

    /**
     * Gift Wrapping Design Options.
     *
     * @var string
     */
    protected $giftWrappingOptions = '#allow-gift-options-for-order-container select[name$="[design]"] > option';

    /**
     * Get Gift Wrappings Available on Onepage Checkout.
     *
     * @return array
     */
    public function getGiftWrappingsAvailable()
    {
        $this->_rootElement->find($this->allowGiftOptions)->click();
        $this->_rootElement->find($this->allowGiftOptionsForItems)->click();
        $giftWrappings = $this->_rootElement->getElements($this->giftWrappingOptions);
        $getGiftWrappingsAvailable = [];
        foreach ($giftWrappings as $giftWrapping) {
            $getGiftWrappingsAvailable[] = $giftWrapping->getText();
        }

        return $getGiftWrappingsAvailable;
    }
}
