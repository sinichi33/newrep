<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_Paypal_Standard extends Mage_Paypal_Model_Standard
{

    /**
     * add surcharge to paypal form
     *
     * @return array
     */
    public function getStandardCheckoutFormFields()
    {
        $returnArray = parent::getStandardCheckoutFormFields();

        $orderIncrementId = $this->getCheckout()->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);

        //we are passing the individual cart items add surcharge as an item at the end
        if ($this->_config->lineItemsEnabled) {
            $itemsCount = 1;
            while (isset($returnArray['item_name_' . $itemsCount])) {
                $itemsCount++;
            }
            $postfix = "_" . $itemsCount;
            $returnArray['item_name' . $postfix] = $this->_getSurchargeDescription($order);
            $returnArray['quantity' . $postfix] = '1';
            $returnArray['amount' . $postfix] = sprintf('%.2f', $order->getBaseFoomanSurchargeAmount());
        } elseif (isset($returnArray['amount'])) {
            $returnArray['amount'] = sprintf(
                '%.2f', $returnArray['amount'] + $this->getQuote()->getBaseFoomanSurchargeAmount()
            );
        }

        return $returnArray;
    }

    /**
     * make description of surcharge acceptable to paypal
     * @param null $order
     *
     * @return mixed|string
     */
    protected function _getSurchargeDescription($order = null)
    {
        $desc = str_replace("&", "and", $order->getFoomanSurchargeDescription());
        if (empty($desc)) {
            $desc = Mage::helper('surcharge')->__('Paypal');
        }
        return Mage::helper('surcharge/compatibility')->escapeHtmlByVersion($desc);
    }
}

