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

namespace Enterprise\Wishlist\Test\Block\Widget;

use Mage\Customer\Test\Fixture\Customer;
use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Wish list search block.
 */
class Search extends Block
{
    /**
     * Search button button css selector.
     *
     * @var string
     */
    protected $searchButton = './/*[contains(@class,"actions")]/button[contains(.,"Search")]';

    /**
     * Input field by customer email param.
     *
     * @var string
     */
    protected $emailInput = '[name="params[email]"]';

    /**
     * Search type selector.
     *
     * @var string
     */
    protected $searchType = '[name="search_by"]';

    /**
     * Search wish list by customer.
     *
     * @param Customer $customer
     * @return void
     */
    public function searchByCustomer(Customer $customer)
    {
        if ($this->_rootElement->find($this->searchType, Locator::SELECTOR_CSS, 'select')->isVisible()) {
            $this->selectSearchType('Wish List Owner Email Search');
        }
        $this->_rootElement->find($this->emailInput)->setValue($customer->getEmail());
        $this->clickSearchButton();
    }

    /**
     * Select search type.
     *
     * @param string $type
     * @return void
     */
    protected function selectSearchType($type)
    {
        $this->_rootElement->find($this->searchType, Locator::SELECTOR_CSS, 'select')->setValue($type);
    }

    /**
     * Click button search.
     *
     * @return void
     */
    public function clickSearchButton()
    {
        $this->_rootElement->find($this->searchButton, Locator::SELECTOR_XPATH)->click();
    }
}
