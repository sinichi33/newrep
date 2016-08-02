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

namespace Enterprise\UrlRewrite\Test\Constraint;

use Mage\Catalog\Test\Page\Product\CatalogProductView;
use Enterprise\UrlRewrite\Test\Fixture\UrlRewrite;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Checking the server response 404 page on frontend.
 */
class AssertPageByUrlRewriteIsNotFound extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Message on the product page 404.
     */
    const NOT_FOUND_MESSAGE = 'WE ARE SORRY, BUT THE PAGE YOU ARE LOOKING FOR CANNOT BE FOUND.';

    /**
     * Checking the server response 404 page on frontend.
     *
     * @param BrowserInterface $browser
     * @param CatalogProductView $catalogProductView
     * @param UrlRewrite $urlRewrite
     * @return void
     */
    public function processAssert(
        BrowserInterface $browser,
        CatalogProductView $catalogProductView,
        UrlRewrite $urlRewrite
    ) {
        $browser->open($_ENV['app_frontend_url'] . $urlRewrite->getIdentifier());
        \PHPUnit_Framework_Assert::assertEquals(
            self::NOT_FOUND_MESSAGE,
            $catalogProductView->getTitleBlock()->getTitle()
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Not found page is display.';
    }
}
