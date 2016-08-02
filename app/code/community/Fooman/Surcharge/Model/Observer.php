<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Observer
{
    protected $_processedMethodTitles = array();

    /**
     * add a dynamic rewrite for the paypal standard model
     * only required on Mage 1.4.1.1 and below
     */
    public function controllerFrontInitBefore()
    {
        if (version_compare(Mage::getVersion(), '1.4.1.1', '<')) {
            Mage::getConfig()->setNode(
                'global/models/paypal/rewrite/standard', 'Fooman_Surcharge_Model_Paypal_Standard'
            );
        }
    }

    /**
     * The subtotal block Mage_Tax_Block_Checkout_Subtotal only takes into consideration known
     * totals (subtotal and shipping) and their taxes when displaying a tax inclusive total
     * when using Fixed Product Taxes (FPT)
     * fix this for Surcharge Tax in this observer
     * triggered by event core_block_abstract_to_html_before
     *
     * @param Varien_Event_Observer $observer
     *
     * @return void
     */
    public function adjustSubtotal($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Tax_Block_Checkout_Subtotal
            && version_compare(Mage::getVersion(), '1.4.1.0', '<')
        ) {
            Mage::helper('surcharge/fixes')->adjustCheckoutSubtotal($block);
        }
    }

    /**
     * Reset the surcharge before quote is updated
     * triggered by event sales_quote_collect_totals_before
     *
     * @param Varien_Event_Observer $observer
     *
     * @return void
     */
    public function resetSurcharge($observer)
    {
        $quote = $observer->getEvent()->getQuote();
        if ($quote) {
            $quote->setFoomanSurchargeAmount(0);
            $quote->setBaseFoomanSurchargeAmount(0);
            $quote->setFoomanSurchargeTaxAmount(0);
            $quote->setBaseFoomanSurchargeTaxAmount(0);
            $quote->setFoomanSurchargeDescription();
            $quote->setFoomanSurchargeProcessed(false);

            /*
            //code to set default payment method checkmo
            if (Mage::getStoreConfig()) {
                if (!$quote->getPayment()->getMethod()) {
                        $quote->getPayment()->importData(array('method'=>'checkmo'));
                }
            }*/
            /*foreach ($quote->getAddressesCollection() as $address) {
                if ($address->getBaseTaxAmount()) {
                    $address->setBaseTaxAmount(
                        $address->getBaseTaxAmount() - $address->getBaseFoomanSurchargeTaxAmount()
                    );
                    $address->setTaxAmount(
                        $address->getTaxAmount() - $address->getFoomanSurchargeTaxAmount()
                    );
                }
                $address->setFoomanSurchargeAmount(0);
                $address->setBaseFoomanSurchargeAmount(0);
                $address->setFoomanSurchargeTaxAmount(0);
                $address->setBaseFoomanSurchargeTaxAmount(0);
                $address->setFoomanSurchargeDescription();
            }*/
        }
    }

    /**
     * code to change the surcharge amount for a creditmemo
     *
     * observes event adminhtml_sales_order_creditmemo_register_before
     *
     * @param $observer
     */
    public function adjustCreditmemoSurcharge($observer)
    {
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $request = $observer->getEvent()->getRequest();
        $order = $creditmemo->getOrder();

        $baseSurchargeAmount = 0;
        $surchargeAmount = 0;
        $baseTaxAmount = 0;
        $taxAmount = 0;

        $surchargeRefund = $request->getParam('creditmemo');
        //surcharge_amount
        if (isset($surchargeRefund['fooman_surcharge_amount'])) {
            $surchargeAmount = $surchargeRefund['fooman_surcharge_amount'];
            $maxPossible = $order->getFoomanSurchargeAmount() - $order->getFoomanSurchargeAmountRefunded();

            if ($surchargeAmount > $maxPossible) {
                $surchargeAmount = $maxPossible;
            }
            $baseSurchargeAmount = $creditmemo->getStore()->roundPrice(
                $surchargeAmount * $creditmemo->getOrder()->getStoreToOrderRate()
            );

            $factor = $surchargeAmount / $creditmemo->getOrder()->getFoomanSurchargeAmount();

            $baseTaxAmount = Mage::app()->getStore()->roundPrice($creditmemo->getOrder()->getBaseFoomanSurchargeTaxAmount() * $factor);
            $taxAmount = Mage::app()->getStore()->roundPrice($creditmemo->getOrder()->getFoomanSurchargeTaxAmount() * $factor);

            //calculate diffs to fully refunding surcharge
            //see @Fooman_Surcharge_Model_Order_Creditmemo_Total_Surcharge
            $diffBaseSurcharge = $creditmemo->getBaseFoomanSurchargeAmount() - $baseSurchargeAmount;
            $diffSurcharge = $creditmemo->getFoomanSurchargeAmount() - $surchargeAmount;
            $diffBaseSurchargeTax = $creditmemo->getBaseFoomanSurchargeTaxAmount() - $baseTaxAmount;
            $diffSurchargeTax = $creditmemo->getFoomanSurchargeTaxAmount() - $taxAmount;

            //set new values
            $creditmemo->setBaseFoomanSurchargeTaxAmount($baseTaxAmount);
            $creditmemo->setFoomanSurchargeTaxAmount($taxAmount);
            $creditmemo->setFoomanSurchargeAmount($surchargeAmount);
            $creditmemo->setBaseFoomanSurchargeAmount($baseSurchargeAmount);

            $creditmemo->setTaxAmount($creditmemo->getTaxAmount() - $diffSurchargeTax);
            $creditmemo->setBaseTaxAmount($creditmemo->getBaseTaxAmount() - $diffBaseSurchargeTax);
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $diffSurcharge - $diffSurchargeTax);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $diffBaseSurcharge - $diffBaseSurchargeTax);
        }

        //set Order Amounts
        $order->setBaseFoomanSurchargeAmountRefunded(
            $order->getBaseFoomanSurchargeAmountRefunded() + $baseSurchargeAmount
        );
        $order->setFoomanSurchargeAmountRefunded($order->getFoomanSurchargeAmountRefunded() + $surchargeAmount);
        $order->setBaseFoomanSurchargeTaxAmountRefunded(
            $order->getBaseFoomanSurchargeTaxAmountRefunded() + $baseTaxAmount
        );
        $order->setFoomanSurchargeTaxAmountRefunded(
            $order->getFoomanSurchargeTaxAmountRefunded() + $taxAmount
        );

    }

    /**
     * add surcharge as a line item when sending quote info to paypal
     * observes event paypal_prepare_line_items
     *
     * @param $observer
     */
    public function addSurchargeToPaypalCart($observer)
    {
        Mage::helper('surcharge')->debug('OBSERVER $addSurchargeToPaypalCart');
        $paypalCart = $observer->getEvent()->getPaypalCart();
        $additional = $observer->getEvent()->getAdditional();
        $salesEntity = $observer->getEvent()->getSalesEntity();
        if ($additional instanceof Varien_Object && $salesEntity instanceof Mage_Core_Model_Abstract) {
            if ($salesEntity->getBaseFoomanSurchargeAmount() != 0) {
                $items = $additional->getItems();
                $items[] = new Varien_Object(
                    array(
                         'id'     => $this->_convertDescriptionToId($salesEntity->getFoomanSurchargeDescription()),
                         'name'   => $this->_getDescription($salesEntity),
                         'qty'    => 1,
                         'amount' => round($salesEntity->getBaseFoomanSurchargeAmount(), 2),
                    )
                );
                $salesEntity->setBaseSubtotal(
                    $salesEntity->getBaseSubtotal() + $salesEntity->getBaseFoomanSurchargeAmount()
                );
                $additional->setItems($items);
            }
        } elseif ($paypalCart) {
            if ($paypalCart->getSalesEntity()->getBaseFoomanSurchargeAmount() > 0) {
                Mage::helper('surcharge')->debug('OBSERVER $addSurchargeToPaypalCart $paypalCart->addItem');
                $paypalCart->addItem(
                    $this->_getDescription($paypalCart->getSalesEntity()),
                    1,
                    $paypalCart->getSalesEntity()->getBaseFoomanSurchargeAmount(),
                    $this->_convertDescriptionToId($paypalCart->getSalesEntity()->getFoomanSurchargeDescription())
                );
                if ($paypalCart->isShippingAsItem()) {
                    //if shipping is added as line item - the above addItem('surcharge') will make shipping count twice
                    $paypalCart->updateTotal(
                        Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL,
                        -1 * $paypalCart->getSalesEntity()->getBaseShippingAmount()
                    );
                }
            }
        }
    }

    /**
     * create an id based on the description that can be used for Paypal
     *
     * @param $description
     *
     * @return Fooman_Surcharge_Helper_Data|mixed
     */
    protected function _convertDescriptionToId($description)
    {
        $description = preg_replace(
            "/[^a-z0-9]+/", "", strtolower($description)
        );
        if (empty($description)) {
            return Mage::helper('surcharge')->__('Surcharge');
        } else {
            return $description;
        }
    }

    /**
     * use description if not supply the default 'Surcharge'
     *
     * @param $salesEntity
     *
     * @return Fooman_Surcharge_Helper_Data
     */
    protected function _getDescription($salesEntity)
    {
        $label = $salesEntity->getFoomanSurchargeDescription()
            ? $salesEntity->getFoomanSurchargeDescription()
            : Mage::helper('surcharge')->__('Surcharge');
        return Mage::helper('surcharge/compatibility')->escapeHtmlByVersion($label);
    }

    /**
     * change payment method title with added surcharge
     * observes event payment_method_is_active
     *
     * @param $observer
     */
    public function paymentMethodIsActive($observer)
    {
        if (Mage::getStoreConfig('surcharge/fooman_surcharge_all/titleadjust')) {
            $result = $observer->getEvent()->getResult();
            if ($result->isAvailable) {
                $methodInstance = $observer->getEvent()->getMethodInstance();
                $methodCode = $methodInstance->getCode();
                if (!isset($this->_processedMethodTitles[$methodCode])) {
                    $quote = $observer->getEvent()->getQuote();
                    if ($quote) {
                        $surcharge = $this->_calcSurchargePreview($quote, $methodCode);
                        if ($surcharge && $surcharge->getBaseAmount() != 0
                            && !isset($result->foomanSurchargeAdjusted)
                        ) {
                            $path = 'payment/';
                            if (strpos($methodInstance->getCode(), 'msp') === 0) {
                                $path = 'msp/';
                            }
                            $formattedAmount = $this->_getFormattedAmount($quote, $surcharge->getAmount());
                            Mage::app()->getStore($methodInstance->getStoreId())->setConfig(
                                $path . $methodInstance->getCode() . '/title',
                                $this->_replaceTitle(
                                    $methodInstance->getConfigData('title'),
                                    $formattedAmount,
                                    $surcharge->getDescription()
                                )
                            );
                            $result->foomanSurchargeAdjusted = true;
                        }
                    }
                    $this->_processedMethodTitles[$methodCode] = true;
                }
            }
        }
    }

    protected function _calcSurchargePreview($quote, $methodCode)
    {
        $surchargeTotal = Mage::getModel('surcharge/quote_address_total_surcharge');
        /* @var $quote Mage_Sales_Model_Quote */
        foreach ($quote->getAddressesCollection() as $address) {
            if (!$address->getAllItems()) {
                continue;
            }
            /*if ($quote->getPayment()->getMethod() == $methodCode) {
                $surcharge = Mage::getModel('surcharge/surcharge');
                $surcharge->setBaseAmount($address->getBaseFoomanSurchargeAmount());
                $surcharge->setAmount($address->getFoomanSurchargeAmount());
                $surcharge->setBaseTaxAmount($address->getBaseFoomanSurchargeTaxAmount());
                $surcharge->setTaxAmount($address->getFoomanSurchargeTaxAmount());
            } else {*/
                $surcharge = $surchargeTotal->surchargeCalculateOnly(
                    $address, $quote, $methodCode
                );
            //}

            //only add surcharge once
            if ($surcharge->getBaseAmount() != 0) {
                if (Mage::helper('surcharge')->displayIncludeTaxCart()) {
                    $surcharge->setBaseAmount($surcharge->getBaseAmount() + $surcharge->getBaseTaxAmount());
                    $surcharge->setAmount($surcharge->getAmount() + $surcharge->getTaxAmount());
                }
                return $surcharge;
            }
        }
        return false;
    }

    public function adjustPaypalTitle($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block->getTemplate() == 'paypal/payment/mark.phtml'
            && Mage::getStoreConfig('surcharge/fooman_surcharge_all/titleadjust')
        ) {
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            if ($quote) {
                $surcharge = $this->_calcSurchargePreview($quote, 'paypal_standard');
                $transport = $observer->getEvent()->getTransport();
                if ($transport && $surcharge && $surcharge->getBaseAmount()) {
                    $transport->setHtml(
                        $this->_replaceTitle(
                            $observer->getEvent()->getTransport()->getHtml(),
                            $this->_getFormattedAmount($quote, $surcharge->getAmount()),
                            Mage::helper('surcharge/compatibility')->escapeHtmlByVersion(
                                $surcharge->getDescription()
                            ),
                            false
                        )
                    );
                }
            }
        }
    }

    /**
     * format amount with currency
     *
     * @param Mage_Sales_Model_Quote $quote
     * @param                        $amount
     *
     * @return string
     */
    protected function _getFormattedAmount(Mage_Sales_Model_Quote $quote, $amount)
    {
        if ($amount == 0) {
            return '';
        }
        $amountCurrency = $quote->getStore()->formatPrice($amount, false);
        return sprintf('%s', $amountCurrency);
    }

    /**
     * apply surcharge format
     *
     * @param      $title
     * @param      $formattedAmount
     * @param      $description
     *
     * @param bool $escape
     *
     * @return mixed
     */
    protected function _replaceTitle($title, $formattedAmount, $description, $escape = true)
    {
        $search = array('{TITLE}', '{AMOUNT}', '{DESCRIPTION}');
        $replace = array($title, $formattedAmount, $description);
        $format = Mage::getStoreConfig('surcharge/fooman_surcharge_all/titleformat');
        $helper = Mage::helper('core');
        $result = str_replace($search, $replace, $format);
        if ($escape) {
            if (method_exists($helper, 'escapeHtml')) {
                return Mage::helper('core')->escapeHtml($result);
            } else {
                return Mage::helper('core')->htmlEscape($result);
            }
        } else {
            return $result;
        }
    }

    public function salesOrderInvoiceCancel($observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();

        $order->setBaseFoomanSurchargeAmountInvoiced(
            $order->getBaseFoomanSurchargeAmountInvoiced() - $invoice->getBaseFoomanSurchargeAmount()
        );
        $order->setFoomanSurchargeAmountInvoiced(
            $order->getFoomanSurchargeAmountInvoiced() - $invoice->getFoomanSurchargeAmount()
        );
        $order->setBaseFoomanSurchargeTaxAmountInvoiced(
            $order->getBaseFoomanSurchargeTaxAmountInvoiced() - $invoice->getBaseFoomanSurchargeTaxAmount()
        );
        $order->setFoomanSurchargeTaxAmountInvoiced(
            $order->getFoomanSurchargeTaxAmountInvoiced() - $invoice->getFoomanSurchargeTaxAmount()
        );
    }
}
