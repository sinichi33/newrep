<?php
class Icube_Rma_Model_Api extends Mage_Api_Model_Resource_Abstract
{

    public function updatestatus($rmaId,$status)
    {
        $rma = Mage::getModel('enterprise_rma/rma')->load($rmaId,'increment_id');;
        if (!$rma->getId()) {
            Mage::log('No RMA Found! RMA #'.$rmaId, null, 'rma-zendesk.log');
            $this->_fault('not_exists');
            // No invoice found
            return false;
        }

        $rma->setStatus($status)->save();
        $result = 'RMA Updated! RMA #'.$rmaId.', Status : '.$status;
        Mage::log($result, null, 'rma-zendesk.log');
        return $result;

    }

}