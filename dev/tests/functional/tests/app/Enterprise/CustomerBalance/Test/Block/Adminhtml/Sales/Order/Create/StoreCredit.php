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

namespace Enterprise\CustomerBalance\Test\Block\Adminhtml\Sales\Order\Create;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;
use Mage\Adminhtml\Test\Block\Template;

/**
 * Backend store credit block on order create page.
 */
class StoreCredit extends Form
{
    /**
     * Backend abstract block.
     *
     * @var string
     */
    protected $templateBlock = './ancestor::body';

    /**
     * Selector for customer balance field.
     *
     * @var string
     */
    protected $customerBalanceField = '#p_method_use_customerbalance';

    /**
     * Get backend abstract block.
     *
     * @return Template
     */
    public function getTemplateBlock()
    {
        return $this->blockFactory->create(
            'Mage\Adminhtml\Test\Block\Template',
            ['element' => $this->_rootElement->find($this->templateBlock, Locator::SELECTOR_XPATH)]
        );
    }

    /**
     * Fill store credit.
     *
     * @param string $value
     * @return void
     */
    public function fillStoreCredit($value)
    {
        $this->_rootElement->find($this->customerBalanceField, Locator::SELECTOR_CSS, 'checkbox')->setValue($value);
        $this->getTemplateBlock()->waitLoader();
    }
}
