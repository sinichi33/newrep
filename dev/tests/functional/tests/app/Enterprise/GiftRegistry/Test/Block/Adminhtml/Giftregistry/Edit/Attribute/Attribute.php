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

namespace Enterprise\GiftRegistry\Test\Block\Adminhtml\Giftregistry\Edit\Attribute;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;
use Mage\Adminhtml\Test\Block\Widget\Tab;
use Enterprise\GiftRegistry\Test\Block\Adminhtml\Giftregistry\Edit\Attribute\Type\AttributeForm;

/**
 * Attribute handler class.
 */
class Attribute extends Tab
{
    /**
     * Form selector.
     *
     * @var string
     */
    protected $formSelector = '//*[@id="registry_option_%d"]';

    /**
     * Add attribute button selector.
     *
     * @var string
     */
    protected $addAttribute = '#registry_add_new_attribute';

    /**
     * Fill data to fields on tab.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @throws \Exception
     * @return $this
     */
    public function fillFormTab(array $fields, SimpleElement $element = null)
    {
        $attributeKey = 1;
        foreach ($fields['attributes']['value'] as $attributeField) {
            $this->addAttribute();
            if (!isset($attributeField['type'])) {
                throw new \Exception('Input type for attribute must be set.');
            }

            /** @var AttributeForm $attributeForm */
            $attributeForm = $this->blockFactory->create(
                __NAMESPACE__ . '\Type\\' . $this->optionNameConvert($attributeField['type']),
                [
                    'element' => $this->_rootElement->find(
                        sprintf($this->formSelector, $attributeKey),
                        Locator::SELECTOR_XPATH
                    )
                ]
            );
            $attributeForm->fillForm($attributeField);
            $attributeKey++;
        }

        return $this;
    }

    /**
     * Click 'Add Attribute' button.
     *
     * @return void
     */
    protected function addAttribute()
    {
        $this->_rootElement->find($this->addAttribute)->click();
    }

    /**
     * Prepare class name.
     *
     * @param string $name
     * @return string
     */
    protected function optionNameConvert($name)
    {
        $name = explode('/', $name);

        return str_replace(' ', '', $name[1]);
    }

    /**
     * Get data of tab.
     *
     * @param array|null $fields
     * @param SimpleElement|null $element
     * @return array
     */
    public function getDataFormTab($fields = null, SimpleElement $element = null)
    {
        $fields = reset($fields);
        $formData = [];
        if (empty($fields['value'])) {
            return $formData;
        }
        foreach ($fields['value'] as $keyRoot => $field) {
            $rootElement = $this->_rootElement->find(
                sprintf($this->formSelector, $field['label']),
                Locator::SELECTOR_XPATH
            );
            /** @var AttributeForm $attributeForm */
            $attributeForm = $this->blockFactory->create(
                __NAMESPACE__ . '\Type\\' . $this->optionNameConvert($field['type']),
                ['element' => $rootElement]
            );
            $formData['attributes'][$keyRoot] = $attributeForm->getDataOptions($field, $rootElement);
        }

        return $formData;
    }
}
