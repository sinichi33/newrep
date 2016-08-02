<?php
class Fooman_Surcharge_Helper_Config extends Mage_Core_Helper_Abstract
{
    const XML_PATH_FOOMAN_SURCHARGES = 'surcharge/types';

    public function getSurcharges()
    {
        $surchargeNodes = Mage::getConfig()->getNode(self::XML_PATH_FOOMAN_SURCHARGES);
        return $surchargeNodes->asArray();
    }
}