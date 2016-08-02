<?php
class Fooman_Surcharge_Model_System_Cctype extends Mage_Payment_Model_Source_Cctype
{

    /**
     * extend Magento default credit card code retrieval to
     * add workaround for Ebizmarts SagePaySuite types
     *
     * @return array
     */
    public function toOptionArray()
    {

        $extraCcTypes = array();
        if ((string)Mage::getConfig()->getModuleConfig('Ebizmarts_SagePaySuite')->active == 'true') {
            $sageCcs = Mage::getModel('sagepaysuite/config')->getCcTypesSagePayDirect();
            foreach ($sageCcs as $code => $name) {
                $extraCcTypes[] = array(
                    'value' => $code, 'label' => Mage::helper('surcharge')->__('Ebizmarts_SagePaySuite') . ' ' . $name
                );
            }
        }
        if ((string)Mage::getConfig()->getModuleConfig('Adyen_Payment')->active == 'true') {
            $aydenCcs = Mage::getModel('adyen/source_ccType')->toOptionArray();
            foreach ($aydenCcs as $code => $name) {
                $extraCcTypes[] = array(
                    'value' => $code, 'label' => Mage::helper('surcharge')->__('Adyen_Payment') . ' ' . $name
                );
            }
        }
        return array_merge(parent::toOptionArray(), $extraCcTypes);
    }
}
