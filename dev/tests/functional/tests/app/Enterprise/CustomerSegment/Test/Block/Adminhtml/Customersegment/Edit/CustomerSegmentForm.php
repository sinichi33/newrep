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

namespace Enterprise\CustomerSegment\Test\Block\Adminhtml\Customersegment\Edit;

use Enterprise\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Client\Element\SimpleElement;
use Mage\Adminhtml\Test\Block\Widget\FormTabs;
use Enterprise\CustomerSegment\Test\Block\Adminhtml\Customersegment\Edit\Tab\MatchedCustomers;
use Mage\Adminhtml\Test\Block\Widget\Tab;
use Mage\Adminhtml\Test\Block\Template;

/**
 * Backend CustomerSegment form.
 */
class CustomerSegmentForm extends FormTabs
{
    /**
     * Selector for website field.
     *
     * @var string
     */
    protected $website = '#segment_website_ids';

    /**
     * Backend abstract block selector.
     *
     * @var string
     */
    protected $templateBlock = './ancestor::body';

    /**
     * Fill form with tabs.
     *
     * @param FixtureInterface $customerSegment
     * @param SimpleElement|null $element
     * @param array|null $replace
     * @return $this
     */
    public function fillForm(FixtureInterface $customerSegment, SimpleElement $element = null, array $replace = null)
    {
        $tabs = $this->getFieldsByTabs($customerSegment);
        if ($replace) {
            $tabs = $this->prepareData($tabs, $replace);
        }
        return $this->fillTabs($tabs, $element);
    }

    /**
     * Fill form with tabs.
     *
     * @param FixtureInterface $customerSegment
     * @param SimpleElement|null $element
     * @return FormTabs
     */
    public function fill(FixtureInterface $customerSegment, SimpleElement $element = null)
    {
        $this->fillWebsite($customerSegment);
        return parent::fill($customerSegment, $element);
    }

    /**
     * Fill website.
     *
     * @param CustomerSegment $customerSegment
     * @return void
     */
    protected function fillWebsite(CustomerSegment $customerSegment)
    {
        $websiteField = $this->_rootElement->find($this->website, Locator::SELECTOR_CSS, 'multiselect');
        if ($websiteField->isVisible() && !$customerSegment->hasData('website_ids')) {
            $websiteField->setValue('Main Website');
        }
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
        foreach ($tabs as $tabName => $fields) {
            foreach ($fields as $key => $pairs) {
                if (isset($replace[$tabName])) {
                    $tabs[$tabName][$key]['value'] = str_replace(
                        array_keys($replace[$tabName]),
                        array_values($replace[$tabName]),
                        $pairs['value']
                    );
                }
            }
        }
        return $tabs;
    }

    /**
     * Get Matched Customers tab.
     *
     * @return MatchedCustomers
     */
    public function getMatchedCustomers()
    {
        return $this->getTabElement('matched_customers');
    }

    /**
     * Get number of customer on navigation tabs.
     *
     * @return string
     */
    public function getNumberOfCustomersOnTabs()
    {
        $customerLink = $this->_rootElement->find($this->tabs['matched_customers']['selector'], Locator::SELECTOR_CSS)
            ->getText();
        preg_match('`\((\d*?)\)`', $customerLink, $customersCount);
        return $customersCount[1];
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
