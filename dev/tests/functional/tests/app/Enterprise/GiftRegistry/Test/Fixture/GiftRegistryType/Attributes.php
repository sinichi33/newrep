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

namespace Enterprise\GiftRegistry\Test\Fixture\GiftRegistryType;

use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Prepare attributes for gift registry type.
 *
 * Data keys:
 *  - preset (Gift registry type verification preset name)
 */
class Attributes implements FixtureInterface
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
     * @constructor
     * @param array $params
     * @param array $data [optional]
     */
    public function __construct(array $params, array $data = [])
    {
        $this->params = $params;
        if (isset($data['preset'])) {
            $this->data = $this->getPreset($data['preset']);
        } else {
            $this->data = $data;
        }
    }

    /**
     * Persists prepared data into application.
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
     * Preset for Attribute manage options.
     *
     * @param string $name
     * @return array|null
     */
    protected function getPreset($name)
    {
        $preset = [
            'text' => [
                [
                    'code' => 'text_%isolation%',
                    'type' => 'Custom Types/Text',
                    'group' => 'Event Information',
                    'label' => 'text_%isolation%',
                    'is_required' => 'Yes',
                    'sort_order' => '10',
                ],
            ],
            'select' => [
                [
                    'code' => 'select_%isolation%',
                    'type' => 'Custom Types/Select',
                    'group' => 'Gift Registry Properties',
                    'label' => 'select_%isolation%',
                    'is_required' => 'Yes',
                    'sort_order' => '20',
                    'options' => [
                        [
                            'code' => 'code1_%isolation%',
                            'label' => 'label1_%isolation%',
                            'is_default' => 'Yes',
                        ],
                        [
                            'code' => 'code2_%isolation%',
                            'label' => 'label2_%isolation%',
                            'is_default' => 'No',
                        ],
                        [
                            'code' => 'code3_%isolation%',
                            'label' => 'label3_%isolation%',
                            'is_default' => 'No',
                        ],
                    ],
                ],
            ],
            'date' => [
                [
                    'code' => 'date_%isolation%',
                    'type' => 'Custom Types/Date',
                    'group' => 'Privacy Settings',
                    'label' => 'date_%isolation%',
                    'is_required' => 'Yes',
                    'sort_order' => '30',
                    'date_format' => 'Full',
                ],
            ],
            'country' => [
                [
                    'code' => 'contry_%isolation%',
                    'type' => 'Custom Types/Country',
                    'group' => 'Recipients Information',
                    'label' => 'country_%isolation%',
                    'is_required' => 'No',
                    'sort_order' => '40',
                    'show_region' => 'Yes',
                ],
            ],
            'event_date' => [
                [
                    'type' => 'Static Types/Event Date',
                    'label' => 'eventdate_%isolation%',
                    'is_required' => 'No',
                    'sort_order' => '50',
                    'is_searcheable' => 'Yes',
                    'is_listed' => 'No',
                    'date_format' => 'Medium',
                ],
            ],
            'event_country' => [
                [
                    'type' => 'Static Types/Event Country',
                    'label' => 'eventcountry_%isolation%',
                    'is_required' => 'No',
                    'sort_order' => '60',
                    'is_searcheable' => 'Yes',
                    'is_listed' => 'No',
                    'show_region' => 'No',
                ],
            ],
            'event_location' => [
                [
                    'type' => 'Static Types/Event Location',
                    'label' => 'eventlocation_%isolation%',
                    'is_required' => 'No',
                    'sort_order' => '70',
                    'is_searcheable' => 'No',
                    'is_listed' => 'No',
                ],
            ],
            'role' => [
                [
                    'type' => 'Static Types/Role',
                    'label' => 'role_%isolation%',
                    'is_required' => 'No',
                    'sort_order' => '80',
                    'is_searcheable' => 'No',
                    'is_listed' => 'No',
                    'options' => [
                        [
                            'code' => 'code1_%isolation%',
                            'label' => 'label1_%isolation%',
                            'is_default' => 'Yes',
                        ],
                        [
                            'code' => 'code2_%isolation%',
                            'label' => 'label2_%isolation%',
                            'is_default' => 'No',
                        ],
                        [
                            'code' => 'code3_%isolation%',
                            'label' => 'label3_%isolation%',
                            'is_default' => 'No',
                        ],
                    ],
                ],
            ],
        ];

        if (!isset($preset[$name])) {
            return null;
        }

        return $preset[$name];
    }
}
