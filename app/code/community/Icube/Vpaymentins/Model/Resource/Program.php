<?php
class Icube_Vpaymentins_Model_Resource_Program extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('vpaymentins/program', 'promo_code');
    }

    public function toOptionArray()
    {
        $collection = Mage::getModel('vpaymentins/program')->getCollection()
            ->addFieldToFilter('program_type', 'bin_filter')
            ->load();
        $options = array();
        foreach($collection as $program) {
            array_push($options, array('value'=>$program->getPromoCode(), 'label'=>$program->getPromoName() ));
        }
        return $options;
    }
}
?>