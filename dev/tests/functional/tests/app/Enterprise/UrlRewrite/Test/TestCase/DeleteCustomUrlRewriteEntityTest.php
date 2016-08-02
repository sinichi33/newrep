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

use Enterprise\UrlRewrite\Test\Fixture\UrlRewrite;
use Enterprise\UrlRewrite\Test\Page\Adminhtml\UrlRewriteEdit;
use Enterprise\UrlRewrite\Test\Page\Adminhtml\UrlRewriteIndex;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Custom URL Rewrites is created.
 *
 * Steps:
 * 1. Log in to backend.
 * 2. Go to Catalog -> URL Redirects.
 * 3. Search and open created URL Redirect.
 * 4. Delete Redirect.
 * 5. Perform all assertions.
 *
 * @group URL_Rewrites_(PS)
 * @ZephyrId MPERF-7517
 */
class DeleteCustomUrlRewriteEntityTest extends Injectable
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
     * Inject pages.
     *
     * @param UrlRewriteIndex $urlRewriteIndex
     * @param UrlRewriteEdit $urlRewriteEdit
     * @return void
     */
    public function __inject(UrlRewriteIndex $urlRewriteIndex, UrlRewriteEdit $urlRewriteEdit)
    {
        $this->urlRewriteIndex = $urlRewriteIndex;
        $this->urlRewriteEdit = $urlRewriteEdit;
    }

    /**
     * Delete custom URL Rewrite test.
     *
     * @param UrlRewrite $urlRewrite
     * @return void
     */
    public function test(UrlRewrite $urlRewrite)
    {
        // Precondition
        $urlRewrite->persist();

        // Steps
        $this->urlRewriteIndex->open();
        $filter = ['request_path' => $urlRewrite->getIdentifier()];
        $this->urlRewriteIndex->getUrlRedirectGrid()->searchAndOpen($filter);
        $this->urlRewriteEdit->getFormPageActions()->deleteAndAcceptAlert();
    }
}
