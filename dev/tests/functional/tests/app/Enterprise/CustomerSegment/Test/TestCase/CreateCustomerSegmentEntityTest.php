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

namespace Enterprise\CustomerSegment\Test\TestCase;

use Enterprise\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentEdit;
use Mage\Cms\Test\Page\CmsIndex;
use Mage\Customer\Test\Fixture\Address;
use Mage\Customer\Test\Fixture\Customer;
use Mage\Customer\Test\Page\Adminhtml\CustomerIndex;
use Mage\Customer\Test\Page\Adminhtml\CustomerEdit;
use Mage\Customer\Test\Page\CustomerAccountLogout;
use Enterprise\CustomerSegment\Test\Fixture\CustomerSegment;
use Enterprise\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Enterprise\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Mage\SalesRule\Test\Fixture\SalesRule;
use Mage\SalesRule\Test\Page\Adminhtml\PromoQuoteEdit;
use Mage\SalesRule\Test\Page\Adminhtml\PromoQuoteIndex;
use Magento\Mtf\Client\Browser;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Customer is created.
 * 2. Simple product is created.
 *
 * Steps:
 * 1. Login to backend as admin.
 * 2. Navigate to Customers -> Customer Segments.
 * 3. Click 'Add Segment' button.
 * 4. Fill all fields according to dataSet and click 'Save and Continue Edit' button.
 * 5. Navigate to Conditions tab.
 * 6. Add specific test condition according to dataSet.
 * 7. Create Shopping Cart Price Rule matched created customer segment.
 * 9. Perform assertions.
 *
 * @group Customer_Segments_(CS)
 * @ZephyrId MPERF-7432
 */
class CreateCustomerSegmentEntityTest extends Injectable
{
    /**
     * Page promo Quote Index.
     *
     * @var PromoQuoteIndex
     */
    protected $promoQuoteIndex;

    /**
     * Page promo Quote Edit.
     *
     * @var PromoQuoteEdit
     */
    protected $promoQuoteEdit;

    /**
     * Customer segment index page.
     *
     * @var CustomerSegmentIndex
     */
    protected $customerSegmentIndex;

    /**
     * Page of create new customer segment.
     *
     * @var CustomerSegmentNew
     */
    protected $customerSegmentNew;

    /**
     * Customer segment edit page.
     *
     * @var CustomerSegmentEdit
     */
    protected $customerSegmentEdit;

    /**
     * Customer grid page.
     *
     * @var CustomerIndex
     */
    protected $customerIndexPage;

    /**
     * Customer edit page.
     *
     * @var CustomerEdit
     */
    protected $customerIndexEditPage;

    /**
     * Index page.
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Fixture sales rule.
     *
     * @var SalesRule
     */
    protected $salesRule;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Customer account logout page.
     *
     * @var CustomerAccountLogout
     */
    protected $customerAccountLogout;

    /**
     * Delete all tax rules.
     *
     * @return void
     */
    public function __prepare()
    {
        $this->objectManager->create('\Mage\Tax\Test\TestStep\DeleteAllTaxRulesStep')->run();
    }

    /**
     * Inject pages.
     *
     * @param PromoQuoteIndex $promoQuoteIndex
     * @param PromoQuoteEdit $promoQuoteEdit
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @param CustomerSegmentEdit $customerSegmentEdit
     * @param CustomerIndex $customerIndexPage
     * @param CmsIndex $cmsIndex
     * @param FixtureFactory $fixtureFactory
     * @param CustomerEdit $customerIndexEditPage
     * @param CustomerAccountLogout $customerAccountLogout
     * @return void
     */
    public function __inject(
        PromoQuoteIndex $promoQuoteIndex,
        PromoQuoteEdit $promoQuoteEdit,
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew,
        CustomerSegmentEdit $customerSegmentEdit,
        CustomerIndex $customerIndexPage,
        CmsIndex $cmsIndex,
        FixtureFactory $fixtureFactory,
        CustomerEdit $customerIndexEditPage,
        CustomerAccountLogout $customerAccountLogout
    ) {
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;
        $this->customerSegmentEdit = $customerSegmentEdit;
        $this->promoQuoteIndex = $promoQuoteIndex;
        $this->promoQuoteEdit = $promoQuoteEdit;
        $this->customerIndexPage = $customerIndexPage;
        $this->customerIndexEditPage = $customerIndexEditPage;
        $this->cmsIndex = $cmsIndex;
        $this->fixtureFactory = $fixtureFactory;
        $this->customerAccountLogout = $customerAccountLogout;
    }

    /**
     * Run create customer segment test.
     *
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @param CustomerSegment $customerSegmentConditions
     * @param array $salesRule
     * @param Browser $browser
     * @return void
     */
    public function test(
        Customer $customer,
        CustomerSegment $customerSegment,
        CustomerSegment $customerSegmentConditions,
        array $salesRule,
        Browser $browser
    ) {
        // Preconditions
        $customer->persist();
        $replacement = $this->prepareReplacement($customer);

        // Steps
        $this->customerSegmentIndex->open();
        $this->customerSegmentIndex->getPageActionsBlock()->addNew();
        $this->customerSegmentNew->getCustomerSegmentForm()->fill($customerSegment);
        $this->customerSegmentNew->getPageMainActions()->saveAndContinue();
        // Retrieve customer segment id
        preg_match('@id/(\d+)/@', $browser->getUrl(), $matches);
        $customerSegmentId = $matches[1];
        $this->customerSegmentEdit->getCustomerSegmentForm()->openTab('conditions');
        $this->customerSegmentEdit->getCustomerSegmentForm()->fillForm($customerSegmentConditions, null, $replacement);
        $this->customerSegmentEdit->getPageMainActions()->save();
        $this->createCartPriceRule($salesRule, $customerSegmentId);
    }

    /**
     * Prepare condition replacement data.
     *
     * @param Customer $customer
     * @return array
     */
    protected function prepareReplacement(Customer $customer)
    {
        /** @var Address $address */
        $address = $customer->getDataFieldConfig('address')['source']->getAddresses()[0];
        return [
            'conditions' => [
                '%email%' => $customer->getEmail(),
                '%company%' => $address->getCompany(),
                '%address%' => $address->getStreet(),
                '%telephone%' => $address->getTelephone(),
                '%postcode%' => $address->getPostcode(),
                '%province%' => $address->getRegionId(),
                '%city%' => $address->getCity(),
            ],
        ];
    }

    /**
     * Create catalog price rule.
     *
     * @param array $salesRule
     * @param string $customerSegmentId
     * @return void
     */
    protected function createCartPriceRule($salesRule, $customerSegmentId)
    {
        $salesRule['conditions_serialized'] =
            str_replace('%customerSegmentName%', $customerSegmentId, $salesRule['conditions_serialized']);
        $this->salesRule = $this->fixtureFactory->createByCode('salesRule',
            ['dataSet' => 'active_sales_rule_for_all_groups_no_coupon', 'data' => $salesRule]
        );
        $this->salesRule->persist();
    }

    /**
     * Deleting cart price rule.
     *
     * @return void
     */
    public function tearDown()
    {
        if (!$this->salesRule instanceof SalesRule) {
            return;
        }
        $this->promoQuoteIndex->open();
        $this->promoQuoteIndex->getPromoQuoteGrid()->searchAndOpen(['name' => $this->salesRule->getName()]);
        $this->promoQuoteEdit->getFormPageActions()->delete();;
    }
}
