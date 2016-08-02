<?php
 
class Icube_City_Block_Adminhtml_Sales_Order_Create_Shipping_Address extends Mage_Adminhtml_Block_Sales_Order_Create_Shipping_Address
{
    /**
     * Prepare Form and add elements to form
     *
     * @return Mage_Adminhtml_Block_Sales_Order_Create_Shipping_Address
     */
    protected function _prepareForm()
    {
        $this->setJsVariablePrefix('shippingAddress');
        /* Start parent::_prepareForm(); */
        $fieldset = $this->_form->addFieldset('main', array(
            'no_container' => true
        ));

        /* @var $addressModel Mage_Customer_Model_Address */
        $addressModel = Mage::getModel('customer/address');

        $addressForm = $this->_getAddressForm()
            ->setEntity($addressModel);

        $attributes = $addressForm->getAttributes();
        if(isset($attributes['street'])) {
            Mage::helper('adminhtml/addresses')
                ->processStreetAttribute($attributes['street']);
        }
        $this->_addAttributesToForm($attributes, $fieldset);

        $prefixElement = $this->_form->getElement('prefix');
        if ($prefixElement) {
            $prefixOptions = $this->helper('customer')->getNamePrefixOptions($this->getStore());
            if (!empty($prefixOptions)) {
                $fieldset->removeField($prefixElement->getId());
                $prefixField = $fieldset->addField($prefixElement->getId(),
                    'select',
                    $prefixElement->getData(),
                    '^'
                );
                $prefixField->setValues($prefixOptions);
                if ($this->getAddressId()) {
                    $prefixField->addElementValues($this->getAddress()->getPrefix());
                }
            }
        }

        $suffixElement = $this->_form->getElement('suffix');
        if ($suffixElement) {
            $suffixOptions = $this->helper('customer')->getNameSuffixOptions($this->getStore());
            if (!empty($suffixOptions)) {
                $fieldset->removeField($suffixElement->getId());
                $suffixField = $fieldset->addField($suffixElement->getId(),
                    'select',
                    $suffixElement->getData(),
                    $this->_form->getElement('lastname')->getId()
                );
                $suffixField->setValues($suffixOptions);
                if ($this->getAddressId()) {
                    $suffixField->addElementValues($this->getAddress()->getSuffix());
                }
            }
        }           
                        
        $regionElement = $this->_form->getElement('region_id');
        if ($regionElement) {
            $regionElement->setNoDisplay(true);
        }

        $this->_form->setValues($this->getFormValues());
        
		/* ICUBE */        
		        $cityElement = $this->_form->getElement('city');
		 if ($cityElement) {
			 if ($this->_form->getElement('region_id')->getValue()) {
			 	$cityOptions = $this->helper('city')->getCityOptions($this->_form->getElement('region_id')->getValue());
				 }
				 
				 	$fieldset->removeField($cityElement->getId());
		                $cityField = $fieldset->addField($cityElement->getId(),
		                    'select',
		                    $cityElement->getData(),
		                    $this->_form->getElement('region_id')->getId()
		                );
					if (!empty($cityOptions)) {
						$cityField->setValues($cityOptions);
		                if ($this->getAddressId()) {
		                    $cityField->addElementValues($this->getAddress()->getCity());
		                }
		                
		        	}
		}
		
		$kecElement = $this->_form->getElement('kecamatan');
		 if ($kecElement) {
			 if ($this->_form->getElement('city')->getValue()) {
			 	$kecOptions = $this->helper('city')->getKecamatanOptions($this->_form->getElement('city')->getValue());
				 }
				 	$fieldset->removeField($kecElement->getId()); 
		                $kecField = $fieldset->addField($kecElement->getId(),
		                    'select',
		                   $kecElement->getData(),
		                    $this->_form->getElement('city')->getId()
		                );
					if (!empty($kecOptions)) {
						$kecField->setValues($kecOptions);
		                if ($this->getAddressId()) {
		                    $kecField->addElementValues($this->getAddress()->getKecamatan());
		                }
		                
		        	}
		} 
		                
		$kelElement = $this->_form->getElement('kelurahan');
		if ($kelElement) {
		$fieldset->removeField($kelElement->getId());  
		$fieldset->addField($kelElement->getId(),
		                    'text',
		                    $kelElement->getData(),
		                    $this->_form->getElement('kecamatan')->getId()
		                );        
		}                   
		 /* ICUBE */
        
        if ($this->_form->getElement('country_id')->getValue()) {
            $countryId = $this->_form->getElement('country_id')->getValue();
            $this->_form->getElement('country_id')->setValue(null);
            foreach ($this->_form->getElement('country_id')->getValues() as $country) {
                if ($country['value'] == $countryId) {
                    $this->_form->getElement('country_id')->setValue($countryId);
                }
            }
        }
        if (is_null($this->_form->getElement('country_id')->getValue())) {
            $this->_form->getElement('country_id')->setValue(
                Mage::helper('core')->getDefaultCountry($this->getStore())
            );
        }

        // Set custom renderer for VAT field if needed
        $vatIdElement = $this->_form->getElement('vat_id');
        if ($vatIdElement && $this->getDisplayVatValidationButton() !== false) {
            $vatIdElement->setRenderer(
                $this->getLayout()->createBlock('adminhtml/customer_sales_order_address_form_renderer_vat')
                    ->setJsVariablePrefix($this->getJsVariablePrefix())
            );
        }
		/* End parent::_prepareForm(); */
		
        $this->_form->addFieldNameSuffix('order[shipping_address]');
        $this->_form->setHtmlNamePrefix('order[shipping_address]');
        $this->_form->setHtmlIdPrefix('order-shipping_address_');

        return $this;
    }
}