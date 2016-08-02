<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Promo
 */

    class Amasty_Promo_Helper_Calc extends Mage_Core_Helper_Abstract
    {
        function getQuoteSubtotal($quote){
            $promoTotals = 0;
            foreach($quote->getItemsCollection() as $item){
                if ($item->getIsPromo()){
                    $promoTotals += $item->getRowTotal();

                }

            }
            $totals = $quote->getTotals();

            return $totals['subtotal']->getValue() - $promoTotals;
        }
    }
?>