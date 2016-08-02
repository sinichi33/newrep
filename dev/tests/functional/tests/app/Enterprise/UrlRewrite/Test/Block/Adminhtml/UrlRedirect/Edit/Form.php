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

namespace Enterprise\UrlRewrite\Test\Block\Adminhtml\UrlRedirect\Edit;

use Enterprise\UrlRewrite\Test\Fixture\UrlRewrite;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Url rewrite form.
 */
class Form extends \Magento\Mtf\Block\Form
{
    /**
     * Selector for store field.
     *
     * @var string
     */
    protected $store = '#store_id';

    /**
     * Fill the root form.
     *
     * @param FixtureInterface $urlRewrite
     * @param SimpleElement|null $element
     * @return $this
     */
    public function fill(FixtureInterface $urlRewrite, SimpleElement $element = null)
    {
        $this->fillStore($urlRewrite);
        return parent::fill($urlRewrite);
    }

    /**
     * Fill store.
     *
     * @param UrlRewrite $urlRewrite
     * @return void
     */
    protected function fillStore(UrlRewrite $urlRewrite)
    {
        $storeField = $this->_rootElement->find($this->store, Locator::SELECTOR_CSS, 'selectstore');
        if ($storeField->isVisible() && !$urlRewrite->hasData('store_id')) {
            $storeField->setValue('Main Website/Main Website Store/Default Store View');
        }
    }
}
