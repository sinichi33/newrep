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

namespace Enterprise\Cms\Test\Block\Adminhtml\Cms\Page\Revision\Edit\Tab;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;
use Mage\Adminhtml\Test\Block\Widget\Tab;

/**
 * Backend cms page revision content tab.
 */
class Content extends Tab
{
    /**
     * Locator for page content.
     *
     * @var string
     */
    protected $pageContent = "#page_content";

    /**
     * Content editor toggle button locator.
     *
     * @var string
     */
    protected $toggleButton = "#togglepage_content";

    /**
     * Locator for revision number.
     *
     * @var string
     */
    protected $revision = "//table/tbody/tr[1]/td";

    /**
     * Locator for version title.
     *
     * @var string
     */
    protected $version = "//table/tbody/tr[4]/td";

    /**
     * Get page content.
     *
     * @return string
     */
    protected function getPageContent()
    {
        $this->hideEditor();
        return $this->_rootElement->find($this->pageContent)->getText();
    }

    /**
     * Hide editor.
     *
     * @return void
     */
    protected function hideEditor()
    {
        $content = $this->_rootElement->find($this->pageContent);
        $toggleButton = $this->_rootElement->find($this->toggleButton);
        if (!$content->isVisible() && $toggleButton->isVisible()) {
            $toggleButton->click();
        }
    }

    /**
     * Get revision number.
     *
     * @return string
     */
    protected function getRevision()
    {
        return $this->_rootElement->find($this->revision, Locator::SELECTOR_XPATH)->getText();
    }

    /**
     * Get version title.
     *
     * @return string
     */
    protected function getVersion()
    {
        return $this->_rootElement->find($this->version, Locator::SELECTOR_XPATH)->getText();
    }

    /**
     * Get data of revision content tab.
     *
     * @return array
     */
    public function getContentData()
    {
        $data['revision'] = $this->getRevision();
        $data['version'] = $this->getVersion();
        $data['content']['content'] = $this->getPageContent();

        return $data;
    }

    /**
     * Fill data to fields on tab.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @return $this
     */
    public function fillFormTab(array $fields, SimpleElement $element = null)
    {
        $this->hideEditor();
        parent::fillFormTab($fields, $element);
        return $this;
    }
}
