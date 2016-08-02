<?php
class Icube_Customisation_LoginController extends Mage_Core_Controller_Front_Action
{
    public function indexAction(){
        
        $session = Mage::getSingleton("customer/session");

        if($session->isLoggedIn()) {
            $this->_redirect('customer/account/index');
            return;
        }

        $this->loadLayout();
         
        $block = $this->getLayout()->createBlock(
            'customer/form_login',
            'custom.login',
            array('template' => 'icube/customisation/login.phtml')
        );
         
        $this->getLayout()->getBlock('content')->append($block);
         
        $this->renderLayout();
    }
}
