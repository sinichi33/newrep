<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Rules
 */


class Amasty_Rules_Model_Rule_Groupn extends Amasty_Rules_Model_Rule_Abstract
{
    function calculateDiscount($rule, $address, $quote)
    {
        $r = array();
        $prices = $this->_getSortedCartPices($rule, $address);
        $qty = $this->_getQty($rule, count($prices));
        if (!$this->hasDiscountItems($prices,$qty)) {
            return $r;
        }
        $prices = array_reverse($prices);

        $currQty = 0;
        $lastId = -1;
        $step = (int)$rule->getDiscountStep();

        $countPrices = count($prices);

        //we must check all items price and compare with group price
        $totalPrice = 0;
        foreach ($prices as $price){
            $totalPrice +=  $price['base_price'];
        }

        if ( $totalPrice < $rule->getDiscountAmount() ){
            return $r;
        }

        foreach ($prices as $i => $price) {
            if ( $this->_skipBySteps($rule,$step,$i,$currQty,$qty) ) continue;

            ++$currQty;

            if ($i < $countPrices - ($countPrices % $step)) {
                $discount = $price['price'] - $quote->getStore()->convertPrice($rule->getDiscountAmount()) / $step;
                $baseDiscount = $price['base_price'] - $rule->getDiscountAmount() / $step;
                $percentage = $discount * 100 / $price['price'];
            } else {
                $discount = 0;
                $baseDiscount = 0;
            }

            if ($price['id'] != $lastId) {
                $lastId = intVal($price['id']);
                $r[$lastId] = array();
                $r[$lastId]['discount'] = $discount;
                $r[$lastId]['base_discount'] = $baseDiscount;
                $r[$lastId]['percent'] = $percentage;
            } else {
                $r[$lastId]['discount'] += $discount;
                $r[$lastId]['base_discount'] += $baseDiscount;
                $r[$lastId]['percent'] = $percentage;
            }
        }

        return $r;
    }
}