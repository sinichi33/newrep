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

namespace Enterprise\GiftWrapping\Test\TestStep;

use Enterprise\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;
use Enterprise\GiftWrapping\Test\Page\Adminhtml\GiftWrappingEdit;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Delete all Gift Wrappings on backend.
 */
class DeleteAllGiftWrappingsStep implements TestStepInterface
{
    /**
     * Gift wrapping index page.
     *
     * @var GiftWrappingIndex
     */
    protected $giftWrappingIndex;

    /**
     * Gift wrapping edit page.
     *
     * @var GiftWrappingEdit
     */
    protected $giftWrappingEdit;

    /**
     * @constructor
     * @param GiftWrappingIndex $giftWrappingIndex
     * @param GiftWrappingEdit $giftWrappingEdit
     */
    public function __construct(GiftWrappingIndex $giftWrappingIndex, GiftWrappingEdit $giftWrappingEdit)
    {
        $this->giftWrappingIndex = $giftWrappingIndex;
        $this->giftWrappingEdit = $giftWrappingEdit;
    }

    /**
     * Delete all Gift Wrappings on backend.
     *
     * @return array
     */
    public function run()
    {
        $this->giftWrappingIndex->open();
        $giftWrappingGrid = $this->giftWrappingIndex->getGiftWrappingGrid();
        $giftWrappingGrid->resetFilter();
        while ($giftWrappingGrid->isFirstRowVisible()) {
            $giftWrappingGrid->openFirstRow();
            $this->giftWrappingEdit->getFormPageActions()->deleteAndAcceptAlert();
        }
    }
}
