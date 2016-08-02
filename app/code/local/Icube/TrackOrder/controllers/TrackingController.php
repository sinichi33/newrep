<?php
class Icube_TrackOrder_TrackingController extends Mage_Core_Controller_Front_Action
{

	/*authentication for user login*/
	/*public function preDispatch()
	{
		parent::preDispatch();
 
		if (!Mage::getSingleton('customer/session')->authenticate($this)) {
			$this->setFlag('', 'no-dispatch', true);
			
		// adding message in customer login page
		Mage::getSingleton('core/session')
				->addSuccess(Mage::helper('customisation')->__('Please sign in or create a new account'));
		}
	}    */       
	

	/**
	 * Order Approval Page
	 */
	public function indexAction()
	{
		$this->loadLayout();
		$this->_title('Status Pesanan');
		$this->getLayout()->getBlock('trackorder.search.form')->setFormAction( Mage::getUrl('*/*/result') );
		$this->renderLayout();
	}

	//function for searching order
	private function __searching($param){

		$order = Mage::getModel('sales/order')
		->getCollection()
		->addFieldToSelect('*')
		->addFieldToFilter('customer_email', $param['email'])
		->addFieldToFilter('increment_id', $param['orderid'])
		->addFieldToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()))
		->getFirstItem();
		return $order;
	}

	public function resultAction() {
		$this->loadLayout();
		$this->_title('Result Tracking Order Page');

		$param = $this->getRequest()->getParams();
		if($param){
			if($param['email'] != '' || $param['orderid'] != ''){
				$result = $this->__searching($param); 
				$this->getLayout()->getBlock('trackorder.search.result')->setResult($result);
				$this->renderLayout();
			}else{
				Mage::getSingleton('core/session')->addError("Please fill all required fields.");
				$this->_redirect('trackorder/tracking');
			}
		}else{
			$this->_redirect('trackorder/tracking');
		}
	}
}