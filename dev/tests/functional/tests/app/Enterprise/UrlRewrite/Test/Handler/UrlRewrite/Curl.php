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

namespace Enterprise\UrlRewrite\Test\Handler\UrlRewrite;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Util\Protocol\CurlInterface;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;
use Magento\Mtf\Handler\Curl as AbstractCurl;

/**
 * Create url rewrite.
 */
class Curl extends AbstractCurl implements UrlRewriteInterface
{
    /**
     * Data mapping.
     *
     * @var array
     */
    protected $mappingData = [
        'store_id' => [
            'Default Store View' => 1,
            'Main Website/Main Website Store/Default Store View' => 1,
        ],
        'options' => [
            'Temporary (302)' => 'R',
            'Permanent (301)' => 'RP',
            'No' => '',
        ],
    ];

    /**
     * Url for save rewrite.
     *
     * @var string
     */
    protected $url = 'urlrewrite/save/';

    /**
     * Post request for creating url rewrite.
     *
     * @param FixtureInterface $fixture [optional]
     * @throws \Exception
     * @return void
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $url = $_ENV['app_backend_url'] . $this->url;
        $data = $this->replaceMappingData($fixture->getData());
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->write(CurlInterface::POST, $url, '1.1', [], $data);
        $response = $curl->read();
        if (!strpos($response, 'class="success-msg"')) {
            throw new \Exception("URL Rewrite creation by curl handler was not successful! Response: $response");
        }
        $curl->close();
    }
}
