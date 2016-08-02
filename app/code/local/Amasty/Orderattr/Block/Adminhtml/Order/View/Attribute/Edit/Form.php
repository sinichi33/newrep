<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Adminhtml_Order_View_Attribute_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _getStoreId()
    {
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order')->getStoreId();
        }
        if (Mage::registry('current_invoice')) {
            return Mage::registry('current_invoice')->getStoreId();
        }
        if (Mage::registry('current_shipment')) {
            return Mage::registry('current_shipment')->getStoreId();
        }
    }
    
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setUseContainer(true);
        
        $orderAttributes = Mage::registry('order_attributes');
        
        
        $fieldset = $form->addFieldset('base_fieldset',
            array('legend'=>Mage::helper('catalog')->__('Attributes\' Values'))
        );
        
        $attributes = Mage::getModel('eav/entity_attribute')->getCollection();
        $attributes->addFieldToFilter('entity_type_id', Mage::getModel('eav/entity')->setType('order')->getTypeId());
		$attributes->getSelect()->where('main_table.is_user_defined = ?', 1);
        $attributes->getSelect()->order('sorting_order');
        $orderData =  $orderAttributes->getData();
        $currentStore = $this->_getStoreId();
        
        foreach ($attributes as $attribute)
        {
            $storeIds = explode(',', $attribute->getData('store_ids'));
            if (!in_array($currentStore, $storeIds) && !in_array(0, $storeIds)) {
                continue;
            }
            
            if ($inputType = $attribute->getFrontend()->getInputType())
        	{
        		$fieldType      = $inputType;
                $rendererClass  = $attribute->getFrontend()->getInputRendererClass();
                if (!empty($rendererClass)) {
                    $fieldType  = $inputType . '_' . $attribute->getAttributeCode();
                    $fieldset->addType($fieldType, $rendererClass);
                }
                
        		$translations = $attribute->getStoreLabels();
                if (isset($translations[Mage::app()->getStore()->getId()]))
                {
                    $attributeLabel = $translations[Mage::app()->getStore()->getId()];
                } else 
                {
                    $attributeLabel = $attribute->getFrontend()->getLabel();
                }
                $elementOptions=  array(
                        'name'      => $attribute->getAttributeCode(),
                        'label'     => $attributeLabel,
                        'class'     => $attribute->getFrontend()->getClass(),
                        'required'  => $attribute->getIsRequired(),
                    );
                if ('date' == $inputType) {
                    $elementOptions['readonly'] = 1;
                    if ($orderData[$attribute->getAttributeCode()] === '0000-00-00' ||
                        $orderData[$attribute->getAttributeCode()] === '0000-00-00 00:00:00') {
                        $orderData[$attribute->getAttributeCode()] = '';
                    }
                }
                    
                if('checkboxes'==$inputType || 'radios'==$inputType ) {
                    $elementOptions['name']  .= '[]';
                    if (isset($orderData[$attribute->getAttributeCode()])) {
                        $orderData[$attribute->getAttributeCode()] = explode(',', $orderData[$attribute->getAttributeCode()]);
                    } 
                    $elementOptions['values'] = $attribute->getSource()->getAllOptions(false, true );
                }

                $formElementType = $fieldType;
                if ('date' == $formElementType)
                {
                	$formElementType = 'text';
                }
                $element = $fieldset->addField($attribute->getAttributeCode(), $fieldType,$elementOptions)
                                    ->setEntityAttribute($attribute);
                 
                
        		if ($inputType == 'select' || $inputType == 'multiselect') {
                    
                    // getting values translations
                    $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                        ->setAttributeFilter($attribute->getId())
                        ->setStoreFilter(Mage::app()->getStore()->getId(), false)
                        ->load();
                    foreach ($valuesCollection as $item) {
                        $values[$item->getId()] = $item->getValue();
                    }
                    
                    // applying translations
                    $options = $attribute->getSource()->getAllOptions(true, true);
                    foreach ($options as $i => $option) {
                        if (isset($values[$option['value']])) {
                            $options[$i]['label'] = $values[$option['value']];
                        }
                    }
                    
                    $element->setValues($options);
                } elseif ($inputType == 'boolean') {
                    $options = $attribute->getSource()->getAllOptions(true, true);
                    $element->setValues($options);
                } elseif ($inputType == 'date' && 'time' != $attribute->getNote()) {
                    $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
                    $element->setFormat(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
                } elseif ($inputType == 'date' && 'time' == $attribute->getNote())
                {
                    $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
                    $element->setFormat(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT) . ' HH:mm');
                    $element->setTime(true);
                }
        	}
        }
       $form->setValues($orderData);
       $this->setForm($form);
       parent::_prepareForm();
       return $this;               
    }
}
