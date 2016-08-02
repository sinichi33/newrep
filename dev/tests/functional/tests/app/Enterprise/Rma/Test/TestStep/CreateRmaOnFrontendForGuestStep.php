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

namespace Enterprise\Rma\Test\TestStep;

use Enterprise\Rma\Test\Page\RmaGuestReturn;
use Enterprise\Rma\Test\Fixture\Rma;
use Mage\Sales\Test\Fixture\Order;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;
use Enterprise\Rma\Test\Page\RmaGuestCreate;

/**
 * Create RMA on frontend for Guest step.
 */
class CreateRmaOnFrontendForGuestStep implements TestStepInterface
{
    /**
     * Rma guest create page.
     *
     * @var RmaGuestCreate
     */
    protected $rmaGuestCreate;

    /**
     * Rma fixture.
     *
     * @var Rma
     */
    protected $rma;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Rma guest return page.
     *
     * @var RmaGuestReturn
     */
    protected $rmaGuestReturn;

    /**
     * Fixture of order.
     *
     * @var Order
     */
    protected $order;

    /**
     * Array of products fixtures.
     *
     * @var array
     */
    protected $products;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param RmaGuestCreate $rmaGuestCreate
     * @param RmaGuestReturn $rmaGuestReturn
     * @param Rma $rma
     * @param Order $order
     * @param array $products [optional]
     */
    public function __construct(
        FixtureFactory $fixtureFactory,
        RmaGuestCreate $rmaGuestCreate,
        RmaGuestReturn $rmaGuestReturn,
        Rma $rma,
        Order $order,
        array $products = []
    ) {
        $this->rmaGuestCreate = $rmaGuestCreate;
        $this->rmaGuestReturn = $rmaGuestReturn;
        $this->fixtureFactory = $fixtureFactory;
        $this->products = $products;
        $this->order = $order;
        $this->rma = $this->updateRmaFixture($rma);
    }

    /**
     * Create RMA on frontend for Guest.
     *
     * @return array
     */
    public function run()
    {
        $this->rmaGuestCreate->getCreateBlock()->fill($this->rma);
        $this->rmaGuestCreate->getCreateBlock()->submit();

        return ['rma' => $this->updateRmaFixture($this->rma, $this->getRmaId())];
    }

    /**
     * Update RMA fixture.
     *
     * @param Rma $rma
     * @param null|string $rmaId
     * @return Rma
     */
    protected function updateRmaFixture(Rma $rma, $rmaId = null)
    {
        $newData = $rma->getData();
        $newData['items'] = [
            'data' => $newData['items'],
            'products' => $this->products,
        ];
        $newData['order_id'] = [
            'order' => $this->order
        ];
        if ($rmaId !== null) {
            $newData['entity_id'] = $rmaId;
        }

        return $this->fixtureFactory->createByCode('rma', ['data' => $newData]);
    }

    /**
     * Get RMA id.
     *
     * @return null|string
     */
    protected function getRmaId()
    {
        return $this->rmaGuestReturn->getMessagesBlock()->getId();
    }
}
