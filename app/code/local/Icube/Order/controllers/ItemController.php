<?php
class Icube_Order_ItemController extends Mage_Core_Controller_Front_Action
{
	public function selectStoreAjaxAction(){

		$applyToAll	= $this->getRequest()->getParam('applyToAll');

		$itemId	= $this->getRequest()->getParam('id');
		$code	= $this->getRequest()->getParam('code');
		$code = explode('-', $code);

		$storeCode = $code[0];
		$companyId = $code[1];
		$pickupLocation = $code[2];
		
		$deliverypickup = $this->getRequest()->getParam('method');

		$resource = Mage::getSingleton('core/resource');
		$writeConnection = $resource->getConnection('core_write');
		$table = $resource->getTableName('sales/quote_item');

		if (strtolower($applyToAll) == "true") {
			$response = array();
			$cart = Mage::getModel('checkout/cart')->getQuote();
			foreach ($cart->getAllItems() as $item) {
				$store = null;
				$itemId = $item->getItemId();
				$productId = $item->getProductId();
				$result = array('message' => '');
				$deliverypickup = $this->getRequest()->getParam('method');

				$orderHelper = Mage::helper('icube_order');
				$checkStores = $orderHelper->getStoreList($productId,$item->getQty(), $storeCode)->getData();

				// check if the same store have stock
				if($checkStores){

					$store = $checkStores[0];

				// check if the same pickup point have stock
				} elseif ($checkStores = $orderHelper->getStoreList($productId,$item->getQty(), null, $pickupLocation)->getData()) {

					$store = $checkStores[0];

				// check if DC have stock
				} elseif ($checkStores = $orderHelper->getStoreList($productId,$item->getQty(), 'DC')->getData()) {

					$store = $checkStores[0];
					$deliverypickup = 'delivery';
					$result['message'] = $item->getName()." tidak tersedia di store yang Anda pilih, kami akan langsung mengirimkannya ke alamat Anda";

				} else {
					$deliverypickup = 'delivery';
					$result['message'] = $item->getName()." tidak tersedia di store yang Anda pilih, silakan pilih store lainnya untuk produk ini";
				}

				
				$result['id'] = $itemId;
				$result['method'] = $deliverypickup;
				$result['code'] = ($store) ?$store['store_code'].'-'.$store['company_id'].'-'.$store['pickup_location_code'] : $this->getRequest()->getParam('code');
					
				$store_code = ($store) ? $store['store_code'] : NULL;
				$company_id = ($store) ? $store['company_id'] : NULL;
				$pickup_location_code = ($store) ? $store['pickup_location_code'] : NULL;

				
				$query .= "UPDATE {$table} SET store_code = '{$store_code}', company_id = '{$company_id}', delivery_pickup = '{$deliverypickup}', pickup_location_code = '{$pickup_location_code}' WHERE item_id = ".$itemId.";";
				

				$response[] = $result;
			}
            
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
		}else{
			$query = "UPDATE {$table} SET store_code = '{$storeCode}', company_id = '{$companyId}', delivery_pickup = '{$deliverypickup}', pickup_location_code = '{$pickupLocation}' WHERE item_id = ".$itemId;
		}

		$writeConnection->query($query);

	}
}