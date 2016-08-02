<?php

class Icube_Customgiftcard_Model_Observer extends Enterprise_GiftCardAccount_Model_Observer
{
    
    /**
     * Charge specified Gift Card (using id)
     * used for event: enterprise_giftcardaccount_charge
     */
    public function chargeById(Varien_Event_Observer $observer)
    {
        $id = $observer->getEvent()->getGiftcardaccountId();
        $amount = $observer->getEvent()->getAmount();

        $giftcardaccount = $this->_getModel('enterprise_giftcardaccount/giftcardaccount')
                    ->load($id);

        // get gift card balance
        $balance = $giftcardaccount->getBalance() - $amount;

        $giftcardaccount->charge($amount)
            ->setOrder($observer->getEvent()->getOrder())
            ->save();

        // check if there are remaining gift card balance
        if ($balance > 0) {
            
            /* clear the balance and create a new history for the remaining balance
            */
            Mage::getSingleton('core/session')->setForfeitBalance(true);

            $this->_getModel('enterprise_giftcardaccount/giftcardaccount')
            ->load($id)
            ->charge($balance)
            ->save();       

            Mage::getSingleton('core/session')->unsForfeitBalance();     
        }

        return $this;
    }

    /**
     * Charge specified Gift Card (using code)
     * used for event: enterprise_giftcardaccount_charge_by_code
     */
    public function chargeByCode(Varien_Event_Observer $observer)
    {
        
        $id = $observer->getEvent()->getGiftcardaccountCode();
        $amount = $observer->getEvent()->getAmount();

        $giftcardaccount = $this->_getModel('enterprise_giftcardaccount/giftcardaccount')
            ->loadByCode($id);

        // get gift card balance
        $balance = $giftcardaccount->getBalance() - $amount;

        $giftcardaccount->charge($amount)
            ->setOrder($observer->getEvent()->getOrder())
            ->save();

        // check if there are remaining gift card balance
        if ($balance > 0) {
            
            /* clear the balance and create a new history for the remaining balance
            */
            Mage::getSingleton('core/session')->setForfeitBalance(true);
            
            $this->_getModel('enterprise_giftcardaccount/giftcardaccount')
            ->loadByCode($id)
            ->charge($balance)
            ->save();            

            Mage::getSingleton('core/session')->unsForfeitBalance();
        }

        return $this;
    }


    /**
    * review gift card account on quote.
     */
    public function reviewAppliedGiftcard(Varien_Event_Observer $observer)
    {
        
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $cards = Mage::helper('enterprise_giftcardaccount')->getCards($quote);

        // clear gift card account from quote.
        Mage::helper('enterprise_giftcardaccount')->setCards($quote, null);

        $message = '';
        foreach ($cards as $k => $card) {

            try {

                // add gift card account into the quote.
                $gc = Mage::getModel('enterprise_giftcardaccount/giftcardaccount')->load($card['i'])
                    ->addToCart(true, $quote);
                
                Mage::getSingleton('core/session')->addSuccess(sprintf('Gift Card "%s" was added.', Mage::helper('core')->escapeHtml($card['c'])));
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('core/session')->addError( $e->getMessage() );
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError(sprintf('Cannot apply gift card "%s".', Mage::helper('core')->escapeHtml($card['c'])));
            }

        }
        
        return $this;
    }    

}
