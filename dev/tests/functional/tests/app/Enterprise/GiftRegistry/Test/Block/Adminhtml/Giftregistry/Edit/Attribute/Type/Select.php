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

namespace Enterprise\GiftRegistry\Test\Block\Adminhtml\Giftregistry\Edit\Attribute\Type;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Filling select type attribute.
 */
class Select extends AttributeForm
{
    /**
     * Add new option button selector.
     *
     * @var string
     */
    protected $addNewOption = '[id^="registry_add_select_row_button"]';

    /**
     * Options selector.
     *
     * @var string
     */
    protected $optionSelector = '//tr[contains(@id,"_select_%d")]';

    /**
     * Fill attribute options.
     *
     * @param array $options
     * @return void
     */
    protected function fillOptions(array $options)
    {
        foreach ($options as $key => $option) {
            $this->_rootElement->find($this->addNewOption)->click();
            /** @var Option $optionForm */
            $optionForm = $this->blockFactory->create(
                __NAMESPACE__ . '\\Option',
                [
                    'element' => $this->_rootElement->find(
                        sprintf($this->optionSelector, $key),
                        Locator::SELECTOR_XPATH
                    )
                ]
            );
            $optionForm->fillForm($option);
        }
    }

    /**
     * Filling attribute form.
     *
     * @param array $attributeFields
     * @param SimpleElement $element
     * @return void
     */
    public function fillForm(array $attributeFields, SimpleElement $element = null)
    {
        $element = $element === null ? $this->_rootElement : $element;
        $mapping = $this->dataMapping($attributeFields);
        $this->_fill($mapping, $element);
        $this->fillOptions($mapping['options']['value']);
    }

    /**
     * Getting options data.
     *
     * @param array|null $fields
     * @param SimpleElement|null $element
     * @return array
     */
    public function getDataOptions(array $fields = null, SimpleElement $element = null)
    {
        $parentFormDataOptions = parent::getDataOptions($fields, $element);
        if (isset($fields['options'])) {
            foreach ($fields['options'] as $key => $option) {
                $optionsBlock = $this->_rootElement->find(
                    sprintf($this->optionSelector, $key),
                    Locator::SELECTOR_XPATH
                );
                /** @var AttributeForm $optionForm */
                $optionForm = $this->blockFactory->create(
                    __NAMESPACE__ . '\\Option',
                    ['element' => $optionsBlock]
                );

                $optionData = $optionForm->getDataOptions($option, $optionsBlock);
                $parentFormDataOptions['options'][$key] = $optionData;
            }
        }
        return $parentFormDataOptions;
    }
}
