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

namespace Enterprise\CatalogEvent\Test\Handler\CatalogEvent;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Config;
use Magento\Mtf\Util\Protocol\CurlInterface;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Create Catalog Event.
 */
class Curl extends AbstractCurl implements CatalogEventInterface
{
    /**
     * Mapping values for data.
     *
     * @var array
     */
    protected $mappingData = [
        'display_state' => [
            'category_page' => 'Yes',
            'product_page' => 'Yes'
        ],
        'category_page' => [
            'Yes' => 1
        ],
        'product_page' => [
            'Yes' => 2
        ]
    ];

    /**
     * Post request for creating Event.
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $data = $this->prepareData($fixture);
        $url = $_ENV['app_backend_url'] . 'catalog_event/save/category_id/' . $data['categoryId'] . '/category/';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->write(CurlInterface::POST, $url, '1.1', [], $data['data']);
        $response = $curl->read();
        $curl->close();
        $id = $this->getCatalogEventId($response);

        return ['id' => $id];

    }

    /**
     * Prepare data for curl.
     *
     * @param FixtureInterface $fixture
     * @return array
     */
    protected function prepareData(FixtureInterface $fixture)
    {
        $data = ['catalogevent' => $this->replaceMappingData($fixture->getData())];
        $data['catalogevent']['display_state'] = array_values($data['catalogevent']['display_state']);
        $categoryId = isset($data['catalogevent']['category_id']['id']) ?
            $data['catalogevent']['category_id']['id'] :
            $fixture->getDataFieldConfig('category_id')['source']->getCategory()->getId();

        return ['data' => $data, 'categoryId' => $categoryId];
    }

    /**
     * Return saved Catalog Event id.
     *
     * @param string $response
     * @return int
     * @throws \Exception
     */
    protected function getCatalogEventId($response)
    {
        preg_match_all('~tr title=[^\s]*\/edit/id\/(\d+)~', $response, $matches);
        if (empty($matches[1])) {
            throw new \Exception('Cannot find Catalog Event id');
        }

        return max($matches[1]);
    }
}
