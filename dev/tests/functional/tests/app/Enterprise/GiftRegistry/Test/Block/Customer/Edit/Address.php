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

namespace Enterprise\GiftRegistry\Test\Block\Customer\Edit;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Shipping address.
 */
class Address extends SimpleElement
{
    /**
     * Address fields selectors.
     *
     * @var array
     */
    protected $address = [
        'firstname' => [
            'selector' => '#shipping-new-address-form [name$="[firstname]"]'
        ],
        'lastname' => [
            'selector' => '#shipping-new-address-form [name$="[lastname]"]'
        ],
        'company' => [
            'selector' => '[name$="[company]"]'
        ],
        'street' => [
            'selector' => '[name$="[street][]"]'
        ],
        'city' => [
            'selector' => '[name$="[city]"]'
        ],
        'region_id' => [
            'selector' => '[name$="[region_id]"]',
            'input' => 'select'
        ],
        'postcode' => [
            'selector' => '[name$="[postcode]"]'
        ],
        'country_id' => [
            'selector' => '[name$="[country_id]"]',
            'input' => 'select'
        ],
        'telephone' => [
            'selector' => '[name$="[telephone]"]'
        ],
    ];

    /**
     * Set shipping address.
     *
     * @param array $value
     * @return void
     */
    public function setValue($value)
    {
        foreach ($value as $field => $val) {
            $input = isset($this->address[$field]['input']) ? $this->address[$field]['input'] : null;
            $this->find($this->address[$field]['selector'], Locator::SELECTOR_CSS, $input)->setValue($val);
        }
    }

    /**
     * Get shipping address.
     *
     * @return array
     */
    public function getValue()
    {
        $address = [];
        foreach ($this->address as $field => $locator) {
            $input = isset($locator['input']) ? $locator['input'] : null;
            $address[$field] = $this->find($locator['selector'], Locator::SELECTOR_CSS, $input)->getValue();
        }

        return $address;
    }
}
