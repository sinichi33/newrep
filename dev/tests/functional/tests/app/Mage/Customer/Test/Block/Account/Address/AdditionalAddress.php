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

namespace Mage\Customer\Test\Block\Account\Address;

use Mage\Customer\Test\Fixture\Address;
use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Additional Address block on customer address page.
 */
class AdditionalAddress extends Block
{
    /**
     * Address item selector.
     *
     * @var string
     */
    protected $addressItem = '//li[address[contains(.,"%s")]]';

    /**
     * Block text selector.
     *
     * @var string
     */
    protected $blockText = 'ol';

    /**
     * Delete address button selector.
     *
     * @var string
     */
    protected $deleteAddress = '.link-remove';

    /**
     * Delete additional address.
     *
     * @param Address $address
     * @return void
     */
    public function deleteAddress(Address $address)
    {
        $addressItemSelector = sprintf($this->addressItem, $address->getStreet());
        $addressItem = $this->_rootElement->find($addressItemSelector, Locator::SELECTOR_XPATH);
        $addressItem->find($this->deleteAddress)->click();
        $this->browser->acceptAlert();
    }

    /**
     * Get block text.
     *
     * @return string
     */
    public function getBlockText()
    {
        return $this->_rootElement->find($this->blockText)->getText();
    }
}
