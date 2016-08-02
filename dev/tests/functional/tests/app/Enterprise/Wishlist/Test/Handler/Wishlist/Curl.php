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

namespace Enterprise\Wishlist\Test\Handler\Wishlist;

use Enterprise\Wishlist\Test\Fixture\Wishlist;
use Mage\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlInterface;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\FrontendDecorator;

/**
 * Create new multiple wish list via curl.
 */
class Curl extends AbstractCurl implements WishlistInterface
{
    /**
     * Mapping values for data.
     *
     * @var array
     */
    protected $mappingData = [
        'visibility' => [
            'Yes' => 'on',
            'No' => 'off',
        ],
    ];

    /**
     * Customer fixture.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Post request for creating multiple wish list.
     *
     * @param FixtureInterface|null $multipleWishlist
     * @return array
     */
    public function persist(FixtureInterface $multipleWishlist = null)
    {
        $this->customer = $multipleWishlist->getDataFieldConfig('customer_id')['source']->getCustomer();
        $data = $this->replaceMappingData($this->prepareData($multipleWishlist));
        return ['id' => $this->createWishlist($data)];
    }

    /**
     * Prepare POST data for creating product request.
     *
     * @param Wishlist $multipleWishlist
     * @return array
     */
    protected function prepareData(Wishlist $multipleWishlist)
    {
        $data = $multipleWishlist->getData();
        unset($data['customer_id']);
        return $data;
    }

    /**
     * Create product via curl.
     *
     * @param array $data
     * @return int|null
     * @throws \Exception
     */
    protected function createWishlist(array $data)
    {
        $url = $_ENV['app_frontend_url'] . 'wishlist/index/createwishlist/';
        $curl = new FrontendDecorator(new CurlTransport(), $this->customer);
        $curl->write(CurlInterface::POST, $_ENV['app_frontend_url'] . 'wishlist');
        $curl->read();
        $curl->write(CurlInterface::POST, $url, '1.1', [], $data);
        $response = $curl->read();
        $curl->close();

        if (strpos($response, 'class="success-msg"') === false) {
            throw new \Exception("Multiple Wish list creation by curl handler was not successful! Response: $response");
        }
        preg_match('~wishlist/index/update/wishlist_id/(\d+)~', $response, $matches);

        return isset($matches[1]) ? $matches[1] : null;
    }
}
