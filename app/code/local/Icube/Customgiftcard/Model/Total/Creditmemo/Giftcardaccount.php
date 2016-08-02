<?php
/**
 *  Icube Custom - Rewrite Magento Enterprise Edition Model
 *  Enterprise_GiftCardAccount_Model_Total_Creditmemo_Giftcardaccount
 * 
 */

class Icube_Customgiftcard_Model_Total_Creditmemo_Giftcardaccount extends Enterprise_GiftCardAccount_Model_Total_Creditmemo_Giftcardaccount
{
    /**
     * Collect gift card account totals for credit memo
     *
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return Enterprise_GiftCardAccount_Model_Total_Creditmemo_Giftcardaccount
     */
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {   
        $order = $creditmemo->getOrder();
        if ($order->getBaseGiftCardsAmount() && $order->getBaseGiftCardsInvoiced() != 0) {
            $gcaLeft = $order->getBaseGiftCardsInvoiced() - $order->getBaseGiftCardsRefunded();

            $used = 0;
            $baseUsed = 0;

            if ($gcaLeft >= $creditmemo->getBaseGrandTotal()) {
                $baseUsed = $creditmemo->getBaseGrandTotal();
                $used = $creditmemo->getGrandTotal();
            } else {
                $baseUsed = $order->getBaseGiftCardsInvoiced() - $order->getBaseGiftCardsRefunded();
                $used = $order->getGiftCardsInvoiced() - $order->getGiftCardsRefunded();
            }

            $creditmemo->setBaseGiftCardsAmount($baseUsed);
            $creditmemo->setGiftCardsAmount($used);

            // set creditmemo maximum amount
            $order->setBaseTotalPaid($order->getBaseTotalPaid() + $order->getBaseGiftCardsAmount());
        }

        $creditmemo->setBaseCustomerBalanceReturnMax($creditmemo->getBaseCustomerBalanceReturnMax() + $creditmemo->getBaseGiftCardsAmount());

        $creditmemo->setCustomerBalanceReturnMax($creditmemo->getCustomerBalanceReturnMax() + $creditmemo->getGiftCardsAmount());
        $creditmemo->setCustomerBalanceReturnMax($creditmemo->getCustomerBalanceReturnMax() + $creditmemo->getGrandTotal());

        return $this;
    }
}
