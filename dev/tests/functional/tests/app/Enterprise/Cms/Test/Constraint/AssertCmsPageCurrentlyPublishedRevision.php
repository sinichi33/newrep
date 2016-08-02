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

use Mage\Cms\Test\Fixture\CmsPage;
use Mage\Cms\Test\Page\Adminhtml\CmsPageIndex;
use Mage\Cms\Test\Page\Adminhtml\CmsPageEdit;
use Enterprise\Cms\Test\Fixture\Revision;
use Enterprise\Cms\Test\Page\Adminhtml\CmsRevisionEdit;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Assert that link to Currently Published Revision on CMS Page Information Form is available.
 */
class AssertCmsPageCurrentlyPublishedRevision extends AbstractAssertForm
{
    /* tags */
    const SEVERITY = 'middle';
    /* end tags */

    /**
     * Assert that link to Currently Published Revision on CMS Page Information Form is available.
     *
     * @param CmsRevisionEdit $cmsRevisionEdit
     * @param CmsPage $cms
     * @param CmsPageEdit $cmsPageEdit
     * @param CmsPageIndex $cmsPageIndex
     * @param array $results
     * @param Revision|null $revision [optional]
     * @return void
     */
    public function processAssert(
        CmsRevisionEdit $cmsRevisionEdit,
        CmsPage $cms,
        CmsPageEdit $cmsPageEdit,
        CmsPageIndex $cmsPageIndex,
        array $results,
        Revision $revision = null
    ) {
        $filter = ['title' => $cms->getTitle()];
        $cmsPageIndex->open();
        $cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $formPublishedRevision = $cmsPageEdit->getPageVersionsForm()->getCurrentlyPublishedRevisionText();
        \PHPUnit_Framework_Assert::assertEquals(
            $cms->getTitle() . '; ' . $results['revision'],
            $formPublishedRevision,
            'Link to Currently Published Revision not equals to passed in fixture.'
        );
        $cmsPageEdit->getPageVersionsForm()->clickCurrentlyPublishedRevision();
        $formRevisionData = $cmsRevisionEdit->getRevisionForm()->getTabElement('content')->getContentData();
        $fixtureRevisionData['revision'] = $results['revisionKey'];
        $fixtureRevisionData['version'] = $cms->getTitle();
        $fixtureRevisionData['content'] = $revision !== null
            ? ['content' => $revision->getContent()]
            : $cms->getContent();
        $error = $this->verifyData($fixtureRevisionData, $formRevisionData);
        \PHPUnit_Framework_Assert::assertEmpty($error, $error);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Link to Currently Published Revision on CMS Page Information Form is available.';
    }
}
