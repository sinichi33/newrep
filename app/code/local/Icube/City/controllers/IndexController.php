<?php
class Icube_City_IndexController extends Mage_Core_Controller_Front_Action
{
	/* Onepage Checkout */
	public function getCityCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$citylist = Mage::helper('city')->getCityList($post['region']);
		
		$result = '<select id="'.$post["step"].':city" name="'.$post["step"].'[city]" title="City" class="validate-select" onchange="getKecamatanAjax()">';
		if($citylist->count() == 0){
			$result .= "<option value=''></option>";
		}
		foreach($citylist as $city){
			$result .= "<option value='".$city->getCity()."'";
			if($post['defaultvalue'] == $city->getCity())
			{
				$result .= "' selected='selected'";
			}
			$result .= ">".$city->getCity()."</option>";
		}
	
		$result .= "</select>";
		echo $result;
		
	}
	
	/* Onepage Checkout */	
	public function getKecamatanCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$kecamatanList = Mage::helper('city')->getKecamatanList($post['city']);
		
		$result = '<select id="'.$post["step"].':kecamatan" name="'.$post["step"].'[kecamatan]" title="Kecamatan" class="validate-select" onchange="getKodeAjax()">';
		if($kecamatanList->count() == 0){
			$result .= "<option value=''></option>";
		}
		foreach($kecamatanList as $kecamatan){			
			$result .= "<option value='".$kecamatan->getKecamatan()."'";
			if($post['defaultvalue'] == $kecamatan->getKecamatan())
			{
				$result .= "' selected='selected'";
			}
			$result .= ">".$kecamatan->getKecamatan()."</option>";
		}
	
		$result .= "</select>";
		echo $result;
		
	}
	
	/* Onepage Checkout and My Account */	
	public function getKodeAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$kode = Mage::helper('city')->getKode($post['city'],$post['kecamatan']);		
		
		echo json_encode($kode);
		
	}
	
	/* onepage checkout */
	public function setKodeAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$kode = Mage::helper('city')->getKode($post['city'],$post['kecamatan']);		
		
		$quote = Mage::getModel('sales/quote')->load($post['quote']);
		$billingaddress = $quote->getBillingAddress();
		$billingaddress->setKodejalur($kode['kodejalur'])
			->setKodekecamatan($kode['kodekecamatan'])
			->save();
		$shippingaddress = $quote->getShippingAddress();
		$shippingaddress->setKodejalur($kode['kodejalur'])
			->setKodekecamatan($kode['kodekecamatan'])
			->save();

		echo json_encode(array('result' => 'success saving "kode"'));
		
	}

	// My Account	
	public function getMyAccountCityCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$citylist = Mage::helper('city')->getCityList($post['region']);
		
		$result = '<select id="city" name="city" title="City" class="validate-select" onchange="getKecamatanAjax()">';
		if($citylist->count() == 0){
			$result .= "<option value=''></option>";
		}
		foreach($citylist as $city){
			$result .= "<option value='".$city->getCity()."'";
			if($post['defaultvalue'] == $city->getCity())
			{
				$result .= "' selected='selected'";
			}
			$result .= ">".$city->getCity()."</option>";
		}
	
		$result .= "</select>";
		echo $result;
		
	}
	
	// My Account
	public function getMyAccountKecamatanCollectionAjaxAction()
	{
		$post = $this->getRequest()->getPost();
		$kecamatanList = Mage::helper('city')->getKecamatanList($post['city']);
		
		$result = '<select id="kecamatan" name="kecamatan" title="Kecamatan" class="validate-select" onchange="getKodeAjax()">';
		if($kecamatanList->count() == 0){
			$result .= "<option value=''></option>";
		}
		foreach($kecamatanList as $kecamatan){			
			$result .= "<option value='".$kecamatan->getKecamatan()."'";
			if($post['defaultvalue'] == $kecamatan->getKecamatan())
			{
				$result .= "' selected='selected'";
			}
			$result .= ">".$kecamatan->getKecamatan()."</option>";
		}
	
		$result .= "</select>";
		echo $result;
		
	}	
}