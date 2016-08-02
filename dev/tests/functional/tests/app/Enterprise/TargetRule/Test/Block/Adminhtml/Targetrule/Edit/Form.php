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

namespace Enterprise\TargetRule\Test\Block\Adminhtml\Targetrule\Edit;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\InjectableFixture;
use Mage\Adminhtml\Test\Block\Widget\FormTabs;
use Mage\Adminhtml\Test\Block\Widget\Tab;
use Mage\Adminhtml\Test\Block\Template;

/**
 * Target rule form on backend target rule page.
 */
class Form extends FormTabs
{
    /**
     * Backend abstract block selector.
     *
     * @var string
     */
    protected $templateBlock = './ancestor::body';

    /**
     * Fill form with tabs.
     *
     * @param InjectableFixture $fixture
     * @param array $replace
     * @return void
     */
    public function fillForm(InjectableFixture $fixture, array $replace)
    {
        $tabs = $this->getFieldsByTabs($fixture);
        $tabs = $this->prepareData($tabs, $replace);
        $this->fillTabs($tabs);
    }

    /**
     * Replace placeholders in each values of data.
     *
     * @param array $tabs
     * @param array $replace
     * @return array
     */
    protected function prepareData(array $tabs, array $replace)
    {
        foreach ($replace as $tabName => $fields) {
            foreach ($fields as $key => $pairs) {
                if (isset($tabs[$tabName][$key])) {
                    $tabs[$tabName][$key]['value'] = str_replace(
                        array_keys($pairs),
                        array_values($pairs),
                        $tabs[$tabName][$key]['value']
                    );
                }
            }
        }

        return $tabs;
    }

    /**
     * Open tab.
     *
     * @param string $tabName
     * @return Tab
     */
    public function openTab($tabName)
    {
        parent::openTab($tabName);
        $this->getTemplateBlock()->waitLoader();

        return $this;
    }

    /**
     * Get backend abstract block.
     *
     * @return Template
     */
    protected function getTemplateBlock()
    {
        return $this->blockFactory->create(
            'Mage\Adminhtml\Test\Block\Template',
            ['element' => $this->_rootElement->find($this->templateBlock, Locator::SELECTOR_XPATH)]
        );
    }
}
