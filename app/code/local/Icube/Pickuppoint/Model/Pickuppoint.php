<?php
class Icube_Pickuppoint_Model_Pickuppoint extends Mage_Core_Model_Abstract
{   
    public function _construct()
    {
        parent::_construct();
        $this->_init('pickuppoint/pickuppoint');
    }     
}
