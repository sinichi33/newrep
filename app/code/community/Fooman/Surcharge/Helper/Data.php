<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_DISPLAY_CART_SURCHARGE = 'tax/cart_display/surcharge';
    const XML_PATH_DISPLAY_SALES_SURCHARGE = 'tax/sales_display/surcharge';
    const DEBUG = false;

    public function displayBothCart()
    {
        return (
            Mage::getStoreConfig(self::XML_PATH_DISPLAY_CART_SURCHARGE) == Mage_Tax_Model_Config::DISPLAY_TYPE_BOTH);
    }

    public function displayIncludeTaxCart()
    {
        return (Mage::getStoreConfig(self::XML_PATH_DISPLAY_CART_SURCHARGE)
            == Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX);
    }

    public function displayBothSales()
    {
        return (
            Mage::getStoreConfig(self::XML_PATH_DISPLAY_SALES_SURCHARGE) == Mage_Tax_Model_Config::DISPLAY_TYPE_BOTH);
    }

    public function displayIncludeTaxSales()
    {
        return (Mage::getStoreConfig(self::XML_PATH_DISPLAY_SALES_SURCHARGE)
            == Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX);
    }

    public function debug($mesg)
    {
        if (self::DEBUG) {
            if (isset($mesg['payment_posted']['cc_number'])) {
                unset($mesg['payment_posted']['cc_number']);
            }
            if (isset($mesg['payment_posted']['cc_cid'])) {
                unset($mesg['payment_posted']['cc_cid']);
            }
            Mage::log($mesg, null, 'surcharge.log');
        }
    }

    public function initSalesOrderTotals($parent)
    {
        $source = $parent->getSource();
        if ($source->getFoomanSurchargeAmount() != 0) {
            if ($source instanceof Mage_Sales_Model_Order) {
                $order = $source;
            } else {
                $order = $source->getOrder();
            }
            $label = Mage::helper('surcharge/compatibility')->escapeHtmlByVersion(
                $order->getFoomanSurchargeDescription()
            );
            if (Mage::helper('surcharge')->displayBothSales()) {
                $surcharge = new Varien_Object(
                    array(
                         'code'       => 'fooman_surcharge',
                         'value'      => $source->getFoomanSurchargeAmount(),
                         'base_value' => $source->getBaseFoomanSurchargeAmount(),
                         'label'      => Mage::helper('surcharge')->__(
                             '%s (Excl. Tax)', $label
                         )
                    )
                );
                $surchargeIncl = new Varien_Object(
                    array(
                         'code'       => 'fooman_surcharge_incl',
                         'value'      => $source->getFoomanSurchargeAmount()
                         + $source->getFoomanSurchargeTaxAmount(),
                         'base_value' => $source->getBaseFoomanSurchargeAmount()
                         + $source->getBaseFoomanSurchargeTaxAmount(),
                         'label'      => Mage::helper('surcharge')->__(
                             '%s (Incl. Tax)', $label
                         )
                    )
                );
                $parent->addTotalBefore($surcharge, 'grand_total');
                //add the inclusive surcharge after the excl surcharge
                $parent->addTotal($surchargeIncl, 'fooman_surcharge');
            } elseif (Mage::helper('surcharge')->displayIncludeTaxSales()) {
                $surchargeIncl = new Varien_Object(
                    array(
                         'code'       => 'fooman_surcharge_incl',
                         'value'      =>
                         $source->getFoomanSurchargeAmount()
                         + $source->getFoomanSurchargeTaxAmount(),
                         'base_value' => $source->getBaseFoomanSurchargeAmount()
                         + $source->getBaseFoomanSurchargeTaxAmount(),
                         'label'      => $label
                    )
                );
                $parent->addTotalBefore($surchargeIncl, 'grand_total');
            } else {
                $surcharge = new Varien_Object(
                    array(
                         'code'       => 'fooman_surcharge',
                         'value'      => $source->getFoomanSurchargeAmount(),
                         'base_value' => $source->getBaseFoomanSurchargeAmount(),
                         'label'      => $label
                    )
                );
                $parent->addTotalBefore($surcharge, 'grand_total');
            }
            $fixHelper = Mage::helper('surcharge/fixes');
            $fixHelper->zeroItemCreditmemo($source, $parent);
            $fixHelper->subtotalIncl($source, $parent);
        }
        return $this;
    }

    /**
     * return calculation base for surcharge
     *
     * @param           $object
     * @param           $storeId
     * @param float|int $existingSurcharge
     *
     * @return float
     */
    public function surchargeOn($object, $storeId, $existingSurcharge = 0)
    {
        $subtotal = 0;
        $surchargeOn = explode(',', Mage::getStoreConfig('surcharge/fooman_surcharge_all/surchargeon', $storeId));
        foreach ($surchargeOn as $surchargeComponent) {
            switch ($surchargeComponent) {
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_SUBTOTAL:
                    $subtotal += $object->getBaseSubtotal();
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_SUBTOTAL_INCL:
                    $subtotal += $this->getSubtotalInclBasedOnVersion($object);
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_SHIPPING:
                    $subtotal += $object->getBaseShippingAmount();
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_SHIPPING_INCL:
                    $subtotal += $object->getBaseShippingAmount() + $object->getBaseShippingTaxAmount();
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_TAX:
                    $subtotal += $object->getBaseTaxAmount();
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_EXCLUDE_DISCOUNT:
                    $subtotal += $object->getBaseDiscountAmount();
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_EXCLUDE_EE_GIFTCARD:
                    $subtotal -= $object->getBaseGiftCardsAmount();
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_EE_GIFTWRAP:
                    $subtotal += $object->getGwBasePrice();
                    $subtotal += $object->getGwItemsBasePrice();
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_EE_PRINTED_CARD:
                    $subtotal += $object->getGwPrintedCardBasePrice();
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_EXCLUDE_EE_POINTS:
                    $subtotal -= $object->getBaseRewardCurrencyAmount();
                    break;
                case Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_GRANDTOTAL:
                    //this overrides all other settings and returns directly
                    return $object->getBaseGrandTotal() - $object->getBaseFoomanSurchargeAmount() + $existingSurcharge;
            }
        }
        return  $subtotal + $existingSurcharge;
    }

    /**
     * not all supported Magento versions keep BaseSubtotalInclTax
     * supply an alternative here.
     *
     * @param $object
     *
     * @return float
     */
    public function getSubtotalInclBasedOnVersion($object)
    {
        if (version_compare(Mage::getVersion(), '1.4.1.0', '>=')) {
            return $object->getBaseSubtotalInclTax();
        } else {
            //older versions don't calculate this is the closest
            return $object->getBaseSubtotal()
            + $object->getBaseTaxAmount()
            - $object->getBaseShippingTaxAmount();
        }
    }
}
