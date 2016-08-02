<?php 
class Icube_Customgiftcard_IndexController extends Mage_Core_Controller_Front_Action 
{
	public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }    
    
	public function indexAction()
	{
        $this->loadLayout();
        $this->renderLayout();	
    }


    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return Mage::getSingleton('checkout/cart')->getQuote();
    }

    /**
     * Add Gift Card to current quote
     *
     */
    public function quickAddAjaxAction()
    {
        $code = $this->getRequest()->getParam('giftcard_code', '');
        if (isset($code)) {
      
            try {

                if (strlen($code) > Enterprise_GiftCardAccount_Helper_Data::GIFT_CARD_CODE_MAX_LENGTH) {
                    $message = $this->__('Wrong gift card code.');
                }

                Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                    ->loadByCode($code)
                    ->addToCart();

                // add gift card account into the quote.
                $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
                $this->_getQuote()
                    ->collectTotals()
                    ->save();


                $totals = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('icube/customcheckout/onepage/review/totals.phtml')->toHtml();

                $message = $this->__('Gift Card "%s" was added.', Mage::helper('core')->escapeHtml($code));
            } catch (Mage_Core_Exception $e) {
                $message = $e->getMessage();
            } catch (Exception $e) {
                $message = $this->__('Cannot apply gift card.');
            }

        echo json_encode(array('message' => $message,'totals' => $totals));  
        return;
        }
        
    }


    public function quickRemoveAjaxAction()
    {
        if ($code = $this->getRequest()->getParam('giftcard_code', '')) {
            try {

                Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                        ->loadByCode($code)
                        ->removeFromCart();

                $totals = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('icube/customcheckout/onepage/review/totals.phtml')->toHtml();
                $message = $this->__('Gift Card "%s" was removed.', Mage::helper('core')->escapeHtml($code));
            } catch (Mage_Core_Exception $e) {
                $message = $this->__($e->getMessage());
            } catch (Exception $e) {
                $message = $this->__('Cannot remove gift card.');
            }
            
            echo json_encode(array('message' => $message,'totals' => $totals));  
            return;
        } 
        
    }

    public function hasGiftCardAction() {
        $order = Mage::getSingleton('checkout/session')->getQuote();
        $result = false;

        if ($order->getGiftCardsAmount() > 0) {
            $result = true;
        }

        echo json_encode(array('result' => $result));
        return;
    }

}