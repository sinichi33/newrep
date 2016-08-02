<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_Quote_Address_Total_Surcharge extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    const FOOMAN_SURCHARGE_ADDRESS_TAX_ADDED = 'fooman_surcharge_address_tax_added';

    protected $_code = 'fooman_surcharge';

    protected $_surchargeBasis = null;

    /**
     * reset surcharge amounts and the start of the calculation
     *
     * @param Mage_Sales_Model_Quote_Address $address
     */
    protected function _resetSurcharge(Mage_Sales_Model_Quote_Address $address)
    {
        $address->setFoomanSurchargeAmount(0);
        $address->setBaseFoomanSurchargeAmount(0);
        $address->setFoomanSurchargeTaxAmount(0);
        $address->setBaseFoomanSurchargeTaxAmount(0);
        $address->setFoomanSurchargeDescription();
    }

    /**
     * determine if we should apply the surcharge
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return bool
     */
    protected function _init(Mage_Sales_Model_Quote_Address $address)
    {
        //we can return if there are no items
        $items = $address->getAllItems();
        if (!count($items)) {
            return false;
        }

        if (!is_numeric($address->getQuote()->getId())) {
            return false;
        }

        return true;
    }

    /**
     * create a new surcharge model to collect amounts
     * and set common values for calculation
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @param Mage_Sales_Model_Quote         $quote
     *
     * @return Fooman_Surcharge_Model_Surcharge
     */
    protected function _initSurcharge(Mage_Sales_Model_Quote_Address $address, Mage_Sales_Model_Quote $quote)
    {
        $surcharge = Mage::getModel('surcharge/surcharge');
        $surcharge->setStoreId($quote->getStoreId());
        $surcharge->setSurchargeBasis($this->_surchargeOn($address, $quote->getStoreId()));
        $surcharge->setPaymentPosted(Mage::app()->getRequest()->getParam('payment'));
        return $surcharge;
    }

    /**
     * the auto group assign can leave the customer group id in a wrong state
     * fix it here
     *
     * @param Mage_Sales_Model_Quote $quote
     */
    protected function _fixQuote(Mage_Sales_Model_Quote $quote)
    {
        //Magento bug? $quote->getCustomerGroupId() does not supply the correct group
        //reset the data here by loading it directly from the db
        if ($quote->getIsSuperMode() !== true
            && !Mage::getStoreConfigFlag('customer/create_account/auto_group_assign')
        ) {
            if ($quote->getCustomerId()) {
                $customer = Mage::getModel('customer/customer')->load($quote->getCustomerId());
                if ($customer->getId()) {
                    $customerGroupId = $customer->getGroupId();
                    if ($customerGroupId) {
                        $quote->setData('customer_group_id', $customerGroupId);
                    }
                }
            }
        }
    }

    /**
     * update the address object with the applicable surcharge and update address tax and grand total
     *
     * @param Mage_Sales_Model_Quote_Address   $address
     * @param Fooman_Surcharge_Model_Surcharge $surcharge
     */
    protected function _updateAddressWithSurcharge(
        Mage_Sales_Model_Quote_Address $address,
        Fooman_Surcharge_Model_Surcharge $surcharge
    ) {
        //apply surcharge amounts in order currency
        $address->setFoomanSurchargeAmount($surcharge->getAmount());
        $address->setFoomanSurchargeTaxAmount($surcharge->getTaxAmount());
        $address->setTaxAmount($address->getTaxAmount() + $surcharge->getTaxAmount());
        $address->setGrandTotal($address->getGrandTotal() + $surcharge->getAmount() + $surcharge->getTaxAmount());

        //Repeat for base currency
        $address->setBaseFoomanSurchargeAmount($surcharge->getBaseAmount());
        $address->setBaseFoomanSurchargeTaxAmount($surcharge->getBaseTaxAmount());
        $address->setBaseTaxAmount($address->getBaseTaxAmount() + $surcharge->getBaseTaxAmount());
        $address->setBaseGrandTotal(
            $address->getBaseGrandTotal() + $surcharge->getBaseAmount() + $surcharge->getBaseTaxAmount()
        );

        //save the description
        $address->setFoomanSurchargeDescription($surcharge->getDescription());
    }

    /**
     * update quote object with surcharge amounts
     *
     * @param Mage_Sales_Model_Quote           $quote
     * @param Fooman_Surcharge_Model_Surcharge $surcharge
     */
    protected function _updateQuoteWithSurcharge(
        Mage_Sales_Model_Quote $quote,
        Fooman_Surcharge_Model_Surcharge $surcharge
    ) {
        $quote->setFoomanSurchargeAmount((float)$quote->getFoomanSurchargeAmount() + $surcharge->getAmount());
        $quote->setBaseFoomanSurchargeAmount(
            (float)$quote->getBaseFoomanSurchargeAmount() + $surcharge->getBaseAmount()
        );

        //save the description
        $quote->setFoomanSurchargeDescription($surcharge->getDescription());
        $quote->setFoomanSurchargeProcessed(true);
    }

    /**
     * run surcharge calculation and apply amounts to address and quote
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return $this|Mage_Sales_Model_Quote_Address_Total_Abstract
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        Varien_Profiler::start('surcharge/quote_address_total_surcharge::collect');

        $this->_resetSurcharge($address);
        if (!$this->_init($address)) {
            return $this;
        }

        $quote = $address->getQuote();
        $this->_fixQuote($quote);

        //surcharge
        $surcharge = $this->surcharge($address, $quote);

        //update address
        $this->_updateAddressWithSurcharge($address, $surcharge);

        //update quote
        $this->_updateQuoteWithSurcharge($quote, $surcharge);

        Mage::helper('surcharge')->debug($surcharge->debug());

        Varien_Profiler::stop('surcharge/quote_address_total_surcharge::collect');
        return $this;
    }

    /**
     * prepare the surcharge for display in the totals list
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return $this|array
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        Varien_Profiler::start('surcharge/quote_address_total_surcharge::fetch');
        $amount = $address->getFoomanSurchargeAmount();
        if ($amount != 0) {

            if ($address->getFoomanSurchargeDescription()) {
                $title = $address->getFoomanSurchargeDescription();
            } else {
                $title = $address->getQuote()->getFoomanSurchargeDescription();
            }
            $address->addTotal(
                array(
                     'code'  => $this->getCode(),
                     'title' => Mage::helper('surcharge/compatibility')->escapeHtmlByVersion($title),
                     'value' => $amount
                )
            );
        }
        Varien_Profiler::stop('surcharge/quote_address_total_surcharge::fetch');
        return $this;
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     * @param Mage_Sales_Model_Quote         $quote
     *
     * @return Fooman_Surcharge_Model_Surcharge|Mage_Core_Model_Abstract
     */
    public function surcharge(Mage_Sales_Model_Quote_Address $address, Mage_Sales_Model_Quote $quote)
    {
        Varien_Profiler::start('surcharge/quote_address_total_surcharge::surcharge');
        $surcharge = $this->_initSurcharge($address, $quote);

        $configuredSurcharges = Mage::helper('surcharge/config')->getSurcharges();
        if ($configuredSurcharges) {
            foreach ($configuredSurcharges as $type => $surchargeNode) {
                try {
                    $surchargeTypeModel = Mage::getModel('surcharge/surcharge_' . $surchargeNode['model_key']);
                    $surcharge = $surchargeTypeModel->calculateSurcharge($type, $surcharge, $quote, $address);
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }

        //apply conversion base to order currency for surcharge
        $surcharge->setAmount($this->_round($surcharge->getBaseAmount()) * $quote->getBaseToQuoteRate());

        //figure out if we need to tax the surcharge
        $surcharge = $this->calculateSurchargeTax($surcharge, $address);
        Varien_Profiler::stop('surcharge/quote_address_total_surcharge::surcharge');
        return $surcharge;
    }

    public function surchargeCalculateOnly(
        Mage_Sales_Model_Quote_Address $address,
        Mage_Sales_Model_Quote $quote,
        $paymentMethod = null
    ) {
        Varien_Profiler::start('surcharge/quote_address_total_surcharge::surcharge');
        $surcharge = $this->_initSurcharge($address, $quote);

        $configuredSurcharges = Mage::helper('surcharge/config')->getSurcharges();
        if ($configuredSurcharges) {
            foreach ($configuredSurcharges as $type => $surchargeNode) {
                try {
                    if ($surchargeNode['model_key'] == 'method' && !$surcharge->getSurchargeApplied()) {
                        $surcharge = $this->_initSurcharge($address, $quote);
                        $surcharge->setPaymentPosted(array('method' => $paymentMethod));
                        $surchargeTypeModel = Mage::getModel('surcharge/surcharge_method');
                        $surcharge = $surchargeTypeModel->calculateSurcharge($type, $surcharge, $quote, $address);
                    }
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }

        //apply conversion base to order currency for surcharge
        $surcharge->setAmount($this->_round($surcharge->getBaseAmount()) * $quote->getBaseToQuoteRate());

        //figure out if we need to tax the surcharge
        $surcharge = $this->calculateSurchargeTax($surcharge, $address);
        Varien_Profiler::stop('surcharge/quote_address_total_surcharge::surcharge');
        return $surcharge;
    }

    public function calculateSurchargeTax(
        Fooman_Surcharge_Model_Surcharge $surcharge,
        Mage_Sales_Model_Quote_Address $address
    ) {
        Varien_Profiler::start('surcharge/quote_address_total_surcharge::calculateSurchargeTax');
        $quote = $address->getQuote();
        $storeId = $surcharge->getStoreId();
        $surchargeTaxClass = $this->_getSurchargeTaxClass($quote);
        if ($surchargeTaxClass) {

            $taxCalculationModel = Mage::getSingleton('tax/calculation');
            $request = $taxCalculationModel->getRateRequest(
                $quote->getShippingAddress(), $quote->getBillingAddress(), $quote->getCustomerTaxClassId(), $storeId
            );
            $request->setStore(Mage::app()->getStore($storeId));
            $rate = $taxCalculationModel->getRate($request->setProductClassId($surchargeTaxClass));
            if ($rate) {
                if (!Mage::getStoreConfig('tax/calculation/tax_included_in_surcharge', $storeId)) {
                    $surcharge->setBaseTaxAmount(
                        $this->_roundTax(
                            $address, $surcharge->getBaseAmount() * ($rate / 100), $rate, true, 'base'
                        )
                    );
                    $surcharge->setTaxAmount(
                        $this->_roundTax(
                            $address, $surcharge->getAmount() * ($rate / 100), $rate, true, 'regular'
                        )
                    );
                } else {
                    $surcharge->setBaseTaxAmount(
                        $this->_roundTax(
                            $address, $surcharge->getBaseAmount() - (
                            $surcharge->getBaseAmount() * (1 / (1 + $rate / 100))),
                            $rate, false, 'base'
                        )
                    );
                    $surcharge->setTaxAmount(
                        $this->_roundTax(
                            $address, $surcharge->getAmount() - (
                            $surcharge->getAmount() * (1 / (1 + $rate / 100))), $rate,
                            false, 'regular'
                        )
                    );
                }
            } else {
                //factor out tax if no tax applies and surcharge was entered price inclusive
                if (Mage::getStoreConfig('tax/calculation/tax_included_in_surcharge', $storeId)) {
                    $request = $taxCalculationModel->getRateRequest(false, false, false, $storeId);
                    $request->setStore(Mage::app()->getStore());
                    $rate = $taxCalculationModel->getRate($request->setProductClassId($surchargeTaxClass));
                    $surcharge->setAmount($this->_round($surcharge->getAmount() / (1 + $rate / 100)));
                    $surcharge->setBaseAmount(
                        $this->_round($surcharge->getBaseAmount() / (1 + $rate / 100))
                    );
                }
            }
            if (!Mage::registry(self::FOOMAN_SURCHARGE_ADDRESS_TAX_ADDED)) {
                Mage::getModel('surcharge/quote_address_total_tax')->_saveAppliedTaxes(
                    $address, $taxCalculationModel->getAppliedRates($request), $surcharge->getTaxAmount(),
                    $surcharge->getBaseTaxAmount(), $rate
                );
                Mage::register(self::FOOMAN_SURCHARGE_ADDRESS_TAX_ADDED, true);
            }

        }

        //apply conversion base to order currency for surcharge tax
        $surcharge->setTaxAmount($this->_round($surcharge->getBaseTaxAmount() * $quote->getBaseToQuoteRate()));

        //adjust surcharge amount to be tax free
        if (Mage::getStoreConfig('tax/calculation/tax_included_in_surcharge', $storeId)) {
            $surcharge->setBaseAmount($surcharge->getBaseAmount() - $surcharge->getBaseTaxAmount());
            $surcharge->setAmount($surcharge->getAmount() - $surcharge->getTaxAmount());
        }

        Varien_Profiler::stop('surcharge/quote_address_total_surcharge::calculateSurchargeTax');
        return $surcharge;
    }

    /**
     * round tax with existing rounding tax deltas
     *
     * @param        $address
     * @param        $tax
     * @param        $rate
     * @param        $direction
     * @param string $type
     *
     * @return float
     */
    protected function _roundTax($address, $tax, $rate, $direction, $type = 'regular')
    {
        if ($tax != 0) {
            $delta = 0;
            switch (Mage::getSingleton('tax/config')->getAlgorithm(Mage::app()->getStore()->getId())) {
                case Mage_Tax_Model_Calculation::CALC_TOTAL_BASE:
                    $deltas = $address->getRoundingDeltas();
                    $key = $type . $direction;
                    $delta = isset($deltas[$key][$rate]) ? $deltas[$key][$rate] : 0;
                    //no break;
                default:
                    return $this->_round(Mage::getSingleton('tax/calculation')->round($tax + $delta));
                    break;
            }
        }
    }

    /**
     * round amount according to Magento's current rounding setting
     *
     * @param $amount
     *
     * @return float
     */
    protected function _round($amount)
    {
        //return round($amount,1);
        return Mage::app()->getStore()->roundPrice($amount);
    }

    /**
     * return calculation base for surcharge
     *
     * @param           $address
     * @param           $storeId
     * @param float|int $existingSurcharge
     *
     * @return float
     */
    protected function _surchargeOn(Mage_Sales_Model_Quote_Address $address, $storeId, $existingSurcharge = 0)
    {
        return Mage::helper('surcharge')->surchargeOn($address, $storeId, $existingSurcharge);
    }

    /**
     * determine tax class from setting or dynamically from quote content
     *
     * @param Mage_Sales_Model_Quote $quote
     *
     * @return string
     */
    protected function _getSurchargeTaxClass(Mage_Sales_Model_Quote $quote)
    {
        $storeId = $quote->getStoreId();
        $taxClass = Mage::getStoreConfig('tax/classes/surcharge_tax_class', $storeId);
        switch ($taxClass) {
            case Fooman_Surcharge_Model_System_SurchargeTaxClass::BIGGEST_FACTOR:
                return $this->_getBiggestFactorTax($quote);
                break;
            default:
                return $taxClass;
        }
    }

    /**
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return string | bool
     */
    protected function _getBiggestFactorTax(Mage_Sales_Model_Quote $quote)
    {
        $itemsByTaxClass = array();

        foreach ($quote->getAllItems() as $item) {
            if (!isset($itemsByTaxClass[$item->getTaxClassId()])) {
                $itemsByTaxClass[$item->getTaxClassId()] = $item->getRowTotal();
            } else {
                $itemsByTaxClass[$item->getTaxClassId()] += $item->getRowTotal();;
            }
        }
        if (!empty($itemsByTaxClass)) {
            return array_search(max($itemsByTaxClass), $itemsByTaxClass);
        }
        return false;
    }
}
