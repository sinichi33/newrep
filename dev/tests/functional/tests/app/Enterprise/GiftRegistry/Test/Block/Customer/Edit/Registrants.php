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
 * Registrants information.
 */
class Registrants extends SimpleElement
{
    /**
     * Add registrant button selector.
     *
     * @var string
     */
    protected $addRegistrant = '#add_registrant_button';

    /**
     * Registrant block selector.
     *
     * @var string
     */
    protected $registrantBlock = '//li[*[contains(@id,"registrant:person_id")]]';

    /**
     * Registrant fields selectors
     *
     * @var array
     */
    protected $registrant = [
        'firstname' => [
            'selector' => '[name$="[firstname]"]'
        ],
        'lastname' => [
            'selector' => '[name$="[lastname]"]'
        ],
        'email' => [
            'selector' => '[name$="[email]"]'
        ],
        'role' => [
            'selector' => '[name$="[role]"]',
            'input' => 'select'
        ],
    ];

    /**
     * Set registrants information.
     *
     * @param array $value
     * @return void
     */
    public function setValue($value)
    {
        foreach ($value as $key => $registrants) {
            $registrant = $this->find(sprintf($this->registrantBlock, $key), Locator::SELECTOR_XPATH);
            if ($key !== 0) {
                $this->find($this->addRegistrant)->click();
            }
            foreach ($registrants as $field => $value) {
                $selector = $this->registrant[$field]['selector'];
                $input = isset($this->registrant[$field]['input']) ? $this->registrant[$field]['input'] : null;
                $registrant->find($selector, Locator::SELECTOR_CSS, $input)->setValue($value);
            }
        }
    }

    /**
     * Get registrants information.
     *
     * @return array
     */
    public function getValue()
    {
        $registrants = [];
        $key = 0;
        $registrant = $this->find(sprintf($this->registrantBlock, $key), Locator::SELECTOR_XPATH);
        while ($registrant->isVisible()) {
            foreach ($this->registrant as $field => $locator) {
                $input = isset($locator['input']) ? $locator['input'] : null;
                $element = $registrant->find($locator['selector'], Locator::SELECTOR_CSS, $input);
                if ($element->isVisible()) {
                    $registrants[$key][$field] = $element->getValue();
                }
            }
            $registrant = $this->find(sprintf($this->registrantBlock, ++$key));
        }

        return $registrants;
    }
}
