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

namespace Enterprise\Cms\Test\TestCase;

use Mage\Cms\Test\Fixture\CmsPage;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\Fixture\FixtureFactory;
use Enterprise\Cms\Test\Fixture\Revision;
use Mage\Cms\Test\Page\Adminhtml\CmsPageEdit;
use Mage\Cms\Test\Page\Adminhtml\CmsPageIndex;
use Enterprise\Cms\Test\Page\Adminhtml\CmsVersionEdit;
use Enterprise\Cms\Test\Page\Adminhtml\CmsRevisionEdit;

/**
 * Precondition:
 * 1. Create CMS page under version control.
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to CMS > Pages > Manage Content.
 * 3. Open the page from precondition.
 * 4. Open 'Versions' tab.
 * 5. Open version on the top of the grid.
 * 6. Open a revision specified in dataset.
 * 7. Fill fields according to dataset.
 * 8. Click 'Save'.
 * 9. Open the revision created (expected id is specified in dataset).
 * 10. Click 'Publish'.
 * 11. Perform appropriate assertions.
 *
 * @group CMS_Versioning_(MX)
 * @ZephyrId MPERF-7621
 */
class PublishCmsPageRevisionEntityTest extends Injectable
{
    /**
     * Cms index page.
     *
     * @var CmsPageIndex
     */
    protected $cmsPageIndex;

    /**
     * Cms edit page.
     *
     * @var CmsPageEdit
     */
    protected $cmsPageEdit;

    /**
     * CmsVersionEdit page.
     *
     * @var CmsVersionEdit
     */
    protected $cmsVersionEdit;

    /**
     * CmsRevisionEdit page.
     *
     * @var CmsRevisionEdit
     */
    protected $cmsRevisionEdit;

    /**
     * Prepare data.
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $cms = $fixtureFactory->createByCode('cmsPage', ['dataSet' => 'cms_page_under_version_control']);
        $cms->persist();

        return ['cms' => $cms];
    }

    /**
     * Injection data.
     *
     * @param CmsPageIndex $cmsPageIndex
     * @param CmsPageEdit $cmsPageEdit
     * @param CmsVersionEdit $cmsVersionEdit
     * @param CmsRevisionEdit $cmsRevisionEdit
     * @return void
     */
    public function __inject(
        CmsPageIndex $cmsPageIndex,
        CmsPageEdit $cmsPageEdit,
        CmsVersionEdit $cmsVersionEdit,
        CmsRevisionEdit $cmsRevisionEdit
    ) {
        $this->cmsPageIndex = $cmsPageIndex;
        $this->cmsPageEdit = $cmsPageEdit;
        $this->cmsVersionEdit = $cmsVersionEdit;
        $this->cmsRevisionEdit = $cmsRevisionEdit;
    }

    /**
     * Run publish cms page revision test.
     *
     * @param CmsPage $cms
     * @param Revision $revision
     * @param int $initialRevision
     * @param int $revisionId
     * @return void
     */
    public function test(CmsPage $cms, Revision $revision, $initialRevision, $revisionId)
    {
        // Steps
        $this->cmsPageIndex->open();
        $title = $cms->getTitle();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen(['title' => $title]);
        $this->cmsPageEdit->getPageVersionsForm()->openTab('versions');
        $filter = ['label' => $title];
        $revisionGrid = $this->cmsVersionEdit->getRevisionsGrid();
        $revisionForm = $this->cmsRevisionEdit->getRevisionForm();
        $this->cmsPageEdit->getPageVersionsForm()->getTabElement('versions')->getVersionsGrid()->searchAndOpen($filter);
        $revisionGrid->searchAndOpen(['revision_number_from' => $revisionId]);
        $revisionForm->toggleEditor();
        $revisionForm->fill($revision);
        $this->cmsRevisionEdit->getFormPageActions()->save();
        $filter = [
            'revision_number_from' => $initialRevision,
            'revision_number_to' => $initialRevision,
        ];
        $revisionGrid->searchAndOpen($filter);
        $this->cmsRevisionEdit->getFormPageActions()->publish();
    }
}
