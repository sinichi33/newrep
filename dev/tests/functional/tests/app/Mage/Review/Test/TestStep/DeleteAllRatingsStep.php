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

namespace Mage\Review\Test\TestStep;

use Mage\Rating\Test\Page\Adminhtml\RatingEdit;
use Mage\Rating\Test\Page\Adminhtml\RatingIndex;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Delete all ratings.
 */
class DeleteAllRatingsStep implements TestStepInterface
{
    /**
     * Backend rating grid page.
     *
     * @var RatingIndex
     */
    protected $ratingIndex;

    /**
     * Backend rating edit page.
     *
     * @var RatingEdit
     */
    protected $ratingEdit;

    /**
     * @constructor
     * @param RatingEdit $ratingEdit
     * @param RatingIndex $ratingIndex
     */
    public function __construct(RatingEdit $ratingEdit, RatingIndex $ratingIndex)
    {
        $this->ratingEdit = $ratingEdit;
        $this->ratingIndex = $ratingIndex;
    }

    /**
     * Delete all ratings.
     *
     * @return void
     */
    public function run()
    {
        $this->ratingIndex->open();
        while ($this->ratingIndex->getRatingGrid()->isFirstRowVisible()) {
            $this->ratingIndex->getRatingGrid()->openFirstRow();
            $this->ratingEdit->getPageActions()->deleteAndAcceptAlert();
        }
    }
}
