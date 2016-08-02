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

namespace Enterprise\GiftWrapping\Test\Fixture\GiftWrapping;

use Mage\Adminhtml\Test\Fixture\Website;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Prepare Website id for Gift Wrapping creation.
 *
 * Data keys:
 *  - dataSet
 */
class WebsiteIds implements FixtureInterface
{
    /**
     * Prepared dataSet data.
     *
     * @var array
     */
    protected $data;

    /**
     * Data set configuration settings.
     *
     * @var array
     */
    protected $params;

    /**
     * Array with websites fixtures.
     *
     * @var Website
     */
    protected $websites;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->params = $params;
        if (isset($data['dataSet'])) {
            $dataSets = is_array($data['dataSet']) ? $data['dataSet'] : [$data['dataSet']];
            foreach ($dataSets as $dataSet) {
                $website = $fixtureFactory->createByCode('website', ['dataSet' => $dataSet]);
                /** @var Website $website */
                if (!$website->getWebsiteId()) {
                    $website->persist();
                }
                $this->websites[] = $website;
                $this->data[] = $website->getName();
            }
        }
    }

    /**
     * Persist websites.
     *
     * @return void
     */
    public function persist()
    {
        //
    }

    /**
     * Return prepared data set.
     *
     * @param string|null $key [optional]
     * @return mixed
     */
    public function getData($key = null)
    {
        return $this->data;
    }

    /**
     * Return data set configuration settings.
     *
     * @return array
     */
    public function getDataConfig()
    {
        return $this->params;
    }

    /**
     * Return Websites fixtures.
     *
     * @return Website[]
     */
    public function getWebsites()
    {
        return $this->websites;
    }
}
