<?php
class Icube_Vpaymentins_Model_Resource_Program_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('vpaymentins/program');
    }
}
?>