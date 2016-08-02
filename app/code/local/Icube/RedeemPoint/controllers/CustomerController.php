<?php 

require_once (BP . DS .'lib/Nusoap/lib/nusoap.php');

class Icube_RedeemPoint_CustomerController extends Mage_Core_Controller_Front_Action 
{

    public function indexAction() {

        if(! Mage::helper('customer')->isLoggedIn()){
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
        }

         $this->loadLayout();
         $this->renderLayout();
    }
    public function availableAction() {

         if(! Mage::helper('customer')->isLoggedIn()){
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
         }

         $session = Mage::getSingleton('core/session');
         $url   = Mage::getUrl('redeempoint/customer');
         $customer = Mage::getSingleton('customer/session')->getCustomer();
         if (Mage::getSingleton('customer/session')->isLoggedIn()) {

             $this->loadLayout();
             $this->renderLayout();

             if (!empty($this->getRequest()->getPost('type'))) {
              
                
                 $post = $this->getRequest()->getPost('memberid');
                 
                 if (empty($this->getRequest()->getPost('memberpin'))) {
                     $session->addError($this->__("Passkey can’t be empty"));
                     Mage::app()->getFrontController()->getResponse()->setRedirect($url);
                 }

                 if (empty($this->getRequest()->getPost('memberid'))) {
                     $session->addError($this->__("Member id can't empty"));
                     Mage::app()->getFrontController()->getResponse()->setRedirect($url);
                 }  

                
                 if ($this->getRequest()->getPost('type')=="AHI") {
                     $customer->setMemberAce($post);
                 }elseif ($this->getRequest()->getPost('type')=="TGI") {
                     $customer->setMemberToyskingdom($post);
                 }elseif ($this->getRequest()->getPost('type')=="HCI") {
                     $customer->setMemberInforma($post);
                 }

                 $customer->save();

             } else {

                Mage::app()->getFrontController()->getResponse()->setRedirect($url);

             }
         } else {

             $this->_redirect('customer/account/login');
         }

    }
	
	public function availableAjaxAction() 
	{
		require_once (BP . DS .'lib/Nusoap/lib/nusoap.php');
		
		if(! Mage::helper('customer')->isLoggedIn()){
            return false;
         }
         
        $post = $this->getRequest()->getPost();
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        
        if ($post['type']=="AHI") {
             $customer->setMemberAce($post['memberid']);
         }elseif ($post['type']=="TGI") {
             $customer->setMemberToyskingdom($post['memberid']);
         }elseif ($post['type']=="HCI") {
             $customer->setMemberInforma($post['memberid']);
         }
		// save member
        $customer->save();

        $wsdl = Mage::getStoreConfig("redeempoint/config/api");
        $client = new nusoap_client($wsdl, 'wsdl');

        $err = $client->getError();
        if ($err) {
            echo json_encode(array('status' => 'error','message'=> $err));
            return;
        }

        $param    = array('company' => $post['type'],'cardID' => $post['memberid'], 'passKey' => $post['memberpin']); 
        $response = $client->Call("getPoint", $param);

        $response = json_decode($response['getPointResult'],true);

        if(!isset($response[0]['point'])) {
            $erorMesage=$response[0]['errMsg'];
            echo json_encode(array('status' => 'error','message'=> $erorMesage));
            return;

        } else {
	        echo json_encode(array('status' => 'success','point'=> $response[0]['point']));
        }             
         
	}
	
    public function successAction() {

        if(! Mage::helper('customer')->isLoggedIn()){
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
        }

        $this->loadLayout();
        $this->renderLayout();

    }
	
	public function createAjaxAction() 
    {
	    if(! Mage::helper('customer')->isLoggedIn()){
            return false;
         }

        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $cusName      = $customerData->getName();
        $cusEmail     = $customerData->getEmail();
        $cusId        = $customerData->getId();
        $dateExpires  = Mage::getModel('core/date')->date('Y-m-d');
        $dateExpires  = date('Y-m-d', strtotime("+6 months", strtotime($dateExpires)));
        $post         = $this->getRequest()->getPost();
        
        if (!empty($post['point'])) 
        {   
	        ///Request Redeem point 
            
            $wsdl = Mage::getStoreConfig("redeempoint/config/api");
            $client = new nusoap_client($wsdl, 'wsdl');

	        $err = $client->getError();
	        if ($err) {
	            echo json_encode(array('status' => 'error','message'=> $err));
	            return;
	        }

            $param = array('company'=>$post['type'], 'custID'=>$cusId, 'cardID'=>$post['memberid'], 'passKey'=>$post['memberpin'] ,'Amount'=>$post['point']); 
            $response = $client->Call("redeemPoint", $param);
            $response = json_decode($response['redeemPointResult'],true);
	        
	        if(isset($response[0]['RedeemID'])) 
	        {
		        $param = array('RedeemID' =>$response[0]['RedeemID']); 
                $reConfirmRedeem = $client->Call("confirmRedeem", $param);
                $reConfirmRedeem = json_decode($reConfirmRedeem['confirmRedeemResult'], true);
                
                if(isset($reConfirmRedeem[0]['VoucherID'])) 
                {
	                $gift_card = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');
	                $gift_card
                            ->setCode($reConfirmRedeem[0]['VoucherID'])
                            ->setCustomerId($cusId)
                            ->setStatus($gift_card::STATUS_ENABLED)
                            ->setDateExpires($dateExpires)
                            ->setWebsiteId(1)
                            ->setState($gift_card::STATE_AVAILABLE)
                            ->setIsRedeemable($gift_card::NOT_REDEEMABLE)
                            ->setType('redeem')
                            ->setCompanyId($post['type'])
                            ->setBalance($post['point']);
                            
                    $gift_card->save();
                    
                    $params = array('giftcard' => $gift_card,
                    				'companyId' => $post['type']);

                    $createJournal = Mage::getModel('journal/journal')->createRedeem($params);

                    $emailTemplate  = Mage::getModel('core/email_template')
                                ->loadDefault('icube_email_giftcard'); 

                    $data = array();
                    $data['code_voucher'] = $reConfirmRedeem[0]['VoucherID']; 

					$emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_sales/email', Mage::app()->getStore()->getStoreId()));

					$emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_sales/name', Mage::app()->getStore()->getStoreId()));

                    $emailTemplate->getProcessedTemplate($data);
                    $emailTemplate->send($cusEmail,$cusName, $data);  
                    
                    // Send GiftCard phtml
                            
				    $block = Mage::app()->getLayout()
				    		->createBlock('customgiftcard/list')
							->setCustomerId($cusId)
							->setTemplate('icube/customgiftcard/list.phtml');

                    $param    = array('company' => $post['type'],'cardID' => $post['memberid'], 'passKey' => $post['memberpin']); 
                    $response = $client->Call("getPoint", $param);
                    $response = json_decode($response['getPointResult'],true);
                    $_point   = $response[0]['point'];

				    echo  json_encode(array('status' => 'success','html'=> $block->toHtml(), 'point'=> $_point));
					return;             

	            }
	            else
	            {
                    $erorMesage=$reConfirmRedeem[0]['errMsg'];
                    echo json_encode(array('status' => 'error','message'=> $erorMesage));
					return;
                }
                
	        }
	        else
	        {
                $erorMesage=$response[0]['errMsg'];
                echo json_encode(array('status' => 'error','message'=> $erorMesage));
	            return;

            }       
	        
	    }
	    else
	    {
            echo json_encode(array('status' => 'error','message'=> "Point can't be empty"));
        }
    }

	
    public function createAction() {

        if(! Mage::helper('customer')->isLoggedIn()){
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
        }

        $url          = Mage::getUrl('redeempoint/customer/success');
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $cusName      = $customerData->getName();
        $cusEmail     = $customerData->getEmail();
        $cusId        = $customerData->getId();
        $point        = $this->getRequest()->getPost('point');
        $dateExpires  = Mage::getModel('core/date')->date('Y-m-d');
        $dateExpires  = date('Y-m-d', strtotime("+6 months", strtotime($dateExpires)));
        $session      = Mage::getSingleton('core/session');

        if (!empty($this->getRequest()->getPost('point'))) {
           
            $gift_card = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');

            ///Request Redeem point 
            
            $type = $this->getRequest()->getPost('type');
            $wsdl = Mage::getStoreConfig("redeempoint/config/api");
            $client = new nusoap_client($wsdl, 'wsdl');
            $session = Mage::getSingleton('core/session');

            $err = $client->getError();
            if ($err) {
                echo '<h2>Constructor error</h2>' . $err;
                exit();
            }

            $cardID = $this->getRequest()->getPost('memberid');
            $passKey = $this->getRequest()->getPost('memberpin');

            $param = array('company' =>$type, 'custID'=> $cusId, 'cardID' => $cardID, 'passKey' => $passKey ,'Amount' => $this->getRequest()->getPost('point')); 
            $response = $client->Call("redeemPoint", $param);
            $response = json_decode($response['redeemPointResult'],true);


            if(isset($response[0]['RedeemID'])) {

                $param = array('RedeemID' =>$response[0]['RedeemID']); 
                $reConfirmRedeem = $client->Call("confirmRedeem", $param);
                $reConfirmRedeem = json_decode($reConfirmRedeem['confirmRedeemResult'], true);

                 if(isset($reConfirmRedeem[0]['VoucherID'])) {

                        $gift_card
                            ->setCode($reConfirmRedeem[0]['VoucherID'])
                            ->setCustomerId($cusId)
                            ->setStatus($gift_card::STATUS_ENABLED)
                            ->setDateExpires($dateExpires)
                            ->setWebsiteId(1)
                            ->setState($gift_card::STATE_AVAILABLE)
                            ->setIsRedeemable($gift_card::NOT_REDEEMABLE)
                            ->setType('redeem')
                            ->setCompanyId($type)
                            ->setBalance($point);
                            
                        $gift_card->save();

                        $params = array('giftcard' => $gift_card,
                    				'companyId' => $post['type']);

                        $createJournal = Mage::getModel('journal/journal')->createRedeem($params);

                        $emailTemplate  = Mage::getModel('core/email_template')
                                    ->loadDefault('icube_email_giftcard'); 

                        $data = array();
                        $data['code_voucher'] = $reConfirmRedeem[0]['VoucherID']; 

                        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_sales/email', Mage::app()->getStore()->getStoreId()));

						$emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_sales/name', Mage::app()->getStore()->getStoreId()));

						$emailTemplate->getProcessedTemplate($data);
						$emailTemplate->send($cusEmail,$cusName, $data);   
                }else{

                    $erorMesage=$reConfirmRedeem[0]['errMsg'];
                    $session->addError($this->__($erorMesage));
                    $url   = Mage::getUrl('redeempoint/customer');
                }
            }else{

                $erorMesage=$response[0]['errMsg'];
                $session->addError($this->__($erorMesage));
                $url   = Mage::getUrl('redeempoint/customer');

            }

        }else{
            $url   = Mage::getUrl('redeempoint/customer');
        }
        
        Mage::app()->getFrontController()->getResponse()->setRedirect($url);

    }

      
}