<?php
class Icube_City_Adminhtml_City_IndexController extends Mage_Core_Controller_Front_Action
{
	public function getBEOrderCityCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$citylist = Mage::helper('city')->getCityList($post['region']);
		
		$result = '<select id="order-'.$post['type'].'_address_city" name="order['.$post['type'].'_address][city]" title="City" class="cities select required-entry">';
		$result .= "<option>Please select city</option>";
		foreach($citylist as $city){
			$result .= "<option value='".$city->getCity()."'>".$city->getCity()."</option>";
		}
	
		$result .= "</select>";
		echo $result;
		
	}

	public function getBECityCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$citylist = Mage::helper('city')->getCityList($post['region_id']);

		$tagNameAttribute = '';
		if (strcmp($post['status'], 'new') == 0) {
			$tagNameAttribute .= '_item';
		}

		$result = '<select id="_item'.$post['form_id'].'city" name="address['.$tagNameAttribute.$post['form_id'].'][city]" title="City" class="required-entry cities select">';
		$result .= "<option>Please select city</option>";
		foreach($citylist as $city){
			$result .= "<option value='".$city->getCity()."'>".$city->getCity()."</option>";
		}
	
		$result .= "</select>";
		echo $result;
		
	}
	
	public function getAdminNewOrderCityCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$citylist = Mage::helper('city')->getCityList($post['region']);
		foreach($citylist as $city){
			$result .= "<option value='".$city->getCity()."'>".$city->getCity()."</option>";
		}
		echo $result;
		
	}
	
	public function getAdminNewOrderKecamatanCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$kecamatanList = Mage::helper('city')->getKecamatanList($post['city']);
		foreach($kecamatanList as $kecamatan){
			$result .= "<option value='".$kecamatan->getKecamatan()."'>".$kecamatan->getKecamatan()."</option>";
		}
		echo $result;
		
	}

	public function getBEOrderKecamatanCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$kecamatanList = Mage::helper('city')->getKecamatanList($post['city']);
		
		$result = '<select id="order-'.$post['type'].'_address_kecamatan" name="order['.$post['type'].'_address][kecamatan]" title="Kecamatan" class="required-entry select">';
		$result .= "<option>Please select kecamatan</option>";
		foreach($kecamatanList as $kecamatan){
			$result .= "<option value='".$kecamatan->getKecamatan()."'>".$kecamatan->getKecamatan()."</option>";
		}
	
		$result .= "</select>";
		echo $result;
		
	}

	public function getBEKecamatanCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$kecamatanList = Mage::helper('city')->getKecamatanList($post['city']);

		$tagNameAttribute = '';
		if (strcmp($post['status'], 'new') == 0) {
			$tagNameAttribute .= '_item';
		}

		$result = '<select id="_item'.$post['form_id'].'kecamatan" name="address['.$tagNameAttribute.$post['form_id'].'][kecamatan]" title="Kecamatan" class="required-entry kecamatan select">';
		$result .= "<option>Please select kecamatan</option>";
		foreach($kecamatanList as $kecamatan){
			$result .= "<option value='".$kecamatan->getKecamatan()."'>".$kecamatan->getKecamatan()."</option>";
		}
	
		$result .= "</select>";
		echo $result;
		
	}
	
}