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

namespace Enterprise\UrlRewrite\Test\TestCase;

use Magento\Mtf\Fixture\FixtureFactory;
use Enterprise\UrlRewrite\Test\Fixture\UrlRewrite;
use Enterprise\UrlRewrite\Test\Page\Adminhtml\UrlRewriteEdit;
use Enterprise\UrlRewrite\Test\Page\Adminhtml\UrlRewriteIndex;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create simple product.
 * 2. Create product UrlRewrite.
 *
 * Steps:
 * 1. Open backend.
 * 2. Go to Catalog -> Url Redirects.
 * 3. Search and open created Url Redirect.
 * 4. Fill data according to dataset.
 * 5. Perform all assertions.
 *
 * @group URL_Rewrites_(MX)
 * @ZephyrId MPERF-7581
 */
class UpdateProductUrlRewriteEntityTest extends Injectable
{
    /**
     * Url rewrite index page.
     *
     * @var UrlRewriteIndex
     */
    protected $urlRewriteIndex;

    /**
     * Url rewrite edit page.
     *
     * @var UrlRewriteEdit
     */
    protected $urlRewriteEdit;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Inject pages.
     *
     * @param UrlRewriteIndex $urlRewriteIndex
     * @param UrlRewriteEdit $urlRewriteEdit
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __inject(
        UrlRewriteIndex $urlRewriteIndex,
        UrlRewriteEdit $urlRewriteEdit,
        FixtureFactory $fixtureFactory
    ) {
        $this->urlRewriteIndex = $urlRewriteIndex;
        $this->urlRewriteEdit = $urlRewriteEdit;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Run update product URL rewrite test.
     *
     * @param UrlRewrite $urlRewriteOrigin
     * @param UrlRewrite $urlRewrite
     * @return array
     */
    public function test(UrlRewrite $urlRewriteOrigin, UrlRewrite $urlRewrite)
    {
        //Preconditions
        $urlRewriteOrigin->persist();
        //Steps
        $this->urlRewriteIndex->open();
        $filter = ['request_path' => $urlRewriteOrigin->getIdentifier()];
        $this->urlRewriteIndex->getUrlRedirectGrid()->searchAndOpen($filter);
        $this->urlRewriteEdit->getEditForm()->fill($urlRewrite);
        $this->urlRewriteEdit->getFormPageActions()->save();
        // Create new url rewrite fixture for asserts
        $data = array_merge($urlRewriteOrigin->getData(), $urlRewrite->getData());
        $data['target_path'] = [
            'custom' => $urlRewriteOrigin->getTargetPath(),
            'readyEntity' => $urlRewriteOrigin->getDataFieldConfig('target_path')['source']->getEntity()
        ];
        $data['store_id'] = ['data' => $urlRewriteOrigin->getStoreId()];
        $urlRewrite = $this->fixtureFactory->createByCode('urlRewrite', ['data' => $data]);

        return ['urlRewrite' => $urlRewrite];
    }
}
