<?php

class Fooman_Surcharge_Model_Paypal_Hostedpro_Request extends Mage_Paypal_Model_Hostedpro_Request
{
    /**
     * Get order request data as array, add surcharge amount to items total
     *
     * @param Mage_Sales_Model_Order $order
     *
     * @return array
     */
    protected function _getOrderData(Mage_Sales_Model_Order $order)
    {
        $request = parent::_getOrderData($order);
        if ($order) {
            $request['subtotal'] = $request['subtotal'] + $order->getBaseFoomanSurchargeAmount();
        }
        return $request;
    }
}
