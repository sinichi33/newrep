<?php
require_once(Mage::getModuleDir('controllers','Mage_Checkout').DS.'CartController.php');
class Icube_Order_CheckoutController extends Mage_Checkout_CartController
{
    /**
     * Initialize coupon
     */
    public function couponPostAjaxAction()
    {
        /**
         * No reason continue with empty shopping cart
         */
        if (!$this->_getCart()->getQuote()->getItemsCount()) {
            $this->_goBack();
            return;
        }

        $couponCode = (string) $this->getRequest()->getParam('coupon_code');
        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }
        $oldCouponCode = $this->_getQuote()->getCouponCode();

        if (!strlen($couponCode) && !strlen($oldCouponCode)) {
            $this->_goBack();
            return;
        }

        try {
            $codeLength = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;

            $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')
                ->collectTotals()
                ->save();

            $totals = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('icube/customcheckout/onepage/review/totals.phtml')->toHtml();
            if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == $this->_getQuote()->getCouponCode()) {
                    echo json_encode(array('message' => $this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode)), 'success' => true, 'totals' => $totals));
                    return;
                } else {
                    echo json_encode(array('message' => $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode)), 'success' => false, 'totals' => $totals));
                    return;
                }
            } else {
                echo json_encode(array('message' => $this->__('Coupon code was canceled.', Mage::helper('core')->escapeHtml($couponCode)), 'success' => true, 'totals' => $totals));
                return;
            }

        } catch (Mage_Core_Exception $e) {
            echo json_encode(array('message' => $e->getMessage()));
            return;
        } catch (Exception $e) {
            Mage::logException($e);
            echo json_encode(array('message' => $this->__('Cannot apply the coupon code.')));
            return;
        }

        $this->_goBack();
    }

    public function reloadTotalsAjaxAction(){
        $totals = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('icube/customcheckout/onepage/review/totals.phtml')->toHtml();
        echo json_encode(array('totals' => $totals));
    }
}
