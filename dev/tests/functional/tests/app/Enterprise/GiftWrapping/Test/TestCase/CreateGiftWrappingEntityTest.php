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

namespace Enterprise\GiftWrapping\Test\TestCase;

use Enterprise\GiftWrapping\Test\Fixture\GiftWrapping;
use Enterprise\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;
use Enterprise\GiftWrapping\Test\Page\Adminhtml\GiftWrappingNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Steps:
 * 1. Login as admin to backend.
 * 2. Navigate to Sales > Gift Wrapping.
 * 3. Click the 'Add Gift Wrapping' button.
 * 4. Fill out fields from data set.
 * 5. Click 'Save' button.
 * 6. Perform asserts.
 *
 * @group Gift_Wrapping_(CS)
 * @ZephyrId MPERF-7613
 */
class CreateGiftWrappingEntityTest extends Injectable
{
    /**
     * Gift Wrapping grid page.
     *
     * @var GiftWrappingIndex
     */
    protected $giftWrappingIndexPage;

    /**
     * Gift Wrapping new page.
     *
     * @var GiftWrappingNew
     */
    protected $giftWrappingNewPage;

    /**
     * Injection pages.
     *
     * @param GiftWrappingIndex $giftWrappingIndexPage
     * @param GiftWrappingNew $giftWrappingNewPage
     * @return void
     */
    public function __inject(GiftWrappingIndex $giftWrappingIndexPage, GiftWrappingNew $giftWrappingNewPage)
    {
        $this->giftWrappingIndexPage = $giftWrappingIndexPage;
        $this->giftWrappingNewPage = $giftWrappingNewPage;
    }

    /**
     * Create Gift Wrapping entity test.
     *
     * @param GiftWrapping $giftWrapping
     * @return void
     */
    public function test(GiftWrapping $giftWrapping)
    {
        // Steps
        $this->giftWrappingIndexPage->open();
        $this->giftWrappingIndexPage->getGridPageActions()->addNew();
        $this->giftWrappingNewPage->getGiftWrappingForm()->fill($giftWrapping);
        $this->giftWrappingNewPage->getFormPageActions()->save();
    }

    /**
     * Delete all gift wrappings after test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create('Enterprise\GiftWrapping\Test\TestStep\DeleteAllGiftWrappingsStep')->run();
    }
}
