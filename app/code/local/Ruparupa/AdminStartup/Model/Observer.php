<?php
class Ruparupa_AdminStartup_Model_Observer {
    public function redirectpageEvent(Varien_Event_Observer $observer) {
        $roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
        $roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();

        $roleBopis = (strpos($roleName, 'pickup_') !== false) ? 1 : 0;
		if ($roleBopis){
			header("Location: " . Mage::helper("adminhtml")->getUrl("kembangan328/bopisdashboard"));
       		exit;
		}
    }
}