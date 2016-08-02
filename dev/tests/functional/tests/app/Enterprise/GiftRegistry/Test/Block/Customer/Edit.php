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

namespace Enterprise\GiftRegistry\Test\Block\Customer;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;
use Enterprise\GiftRegistry\Test\Fixture\GiftRegistryType;

/**
 * Gift registry edit form.
 */
class Edit extends Form
{
    /**
     * Gift registry type input selector.
     *
     * @var string
     */
    protected $giftRegistryType = '[name="type_id"]';

    /**
     * Next button selector.
     *
     * @var string
     */
    protected $next = "button[id='submit.next']";

    /**
     * Save button selector.
     *
     * @var string
     */
    protected $saveButton = '[id="submit.save"]';

    /**
     * Select gift registry type.
     *
     * @param string $value
     * @return void
     */
    public function selectGiftRegistryType($value)
    {
        $this->_rootElement->find($this->giftRegistryType, Locator::SELECTOR_CSS, 'select')->setValue($value);
        $this->_rootElement->find($this->next)->click();
    }

    /**
     * Click 'Save' button.
     *
     * @return void
     */
    public function save()
    {
        $this->_rootElement->find($this->saveButton)->click();
    }

    /**
     * Check if gift registry type is visible.
     *
     * @param GiftRegistryType $giftRegistryType
     * @return bool
     */
    public function isGiftRegistryVisible(GiftRegistryType $giftRegistryType)
    {
        $presentOptions = $this->_rootElement->find($this->giftRegistryType)->getText();
        $presentOptions = explode("\n", $presentOptions);

        return in_array($giftRegistryType->getLabel(), $presentOptions);
    }
}
