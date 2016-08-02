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

namespace Enterprise\Cms\Test\Constraint;

use Mage\Cms\Test\Page\AdminHtml\CmsPageIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that success publish message is displayed on the page.
 */
class AssertCmsPageRevisionSuccessPublishMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Text value to be checked.
     */
    const SUCCESS_PUBLISH_MESSAGE = 'The revision has been published.';

    /**
     * Assert that success publish message is displayed on the page.
     *
     * @param CmsPageIndex $cmsPageIndex
     * @return void
     */
    public function processAssert(CmsPageIndex $cmsPageIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_PUBLISH_MESSAGE,
            $cmsPageIndex->getMessagesBlock()->getSuccessMessages()
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'CMS Page Revision success publish message is present.';
    }
}
