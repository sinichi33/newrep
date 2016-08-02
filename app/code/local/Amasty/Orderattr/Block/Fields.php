<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Fields extends Mage_Core_Block_Template        
{
    protected $_entityTypeId; 
    
    protected $_formElements = array();
    
    protected $_requiredCheckboxes = array();
    
    protected $_step;
    
    protected $_attributeCode;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amasty/amorderattr/fields.phtml');
        
        $this->_entityTypeId = Mage::getModel('eav/entity')->setType('order')->getTypeId();
    }
    
    public function setStep($step)
    {
        $this->_step = $step;
        return $this;
    }
    
    public function setAttributeCode($code)
    {
        $this->_attributeCode = $code;
        return $this;
    }
    
    public function getStep()
    {
        return $this->_step;
    }
    
    public function getAttributeCode()
    {
        return $this->_attributeCode;
    }
    
    public function getStepCode()
    {
        switch ($this->getStep())
        {
            case 'billing':
                return 2;
            break;
            case 'shipping':
                return 3;
            break;
            case 'shipping_method':
                return 4;
            break;
            case 'payment':
                return 5;
            break; 
            case 'review':
                return 6;
            break;
            default:
                return 0;
            break;
        }
    }
    
    public function getFormElements()
    {
        if ($this->_formElements)
        {
            return $this->_formElements;
        }
        
        $collection = Mage::getModel('eav/entity_attribute')->getCollection();        
        $collection->addFieldToFilter('is_visible_on_front', 1);
        $collection->addFieldToFilter('entity_type_id', $this->_entityTypeId);
        
        if ($this->getStepCode() > 0){
        $collection->addFieldToFilter('checkout_step', $this->getStepCode());
        }
        
        if ($this->getAttributeCode()){
            $collection->addFieldToFilter('attribute_code', $this->getAttributeCode());
        }
        
        $collection->getSelect()->order('sorting_order');
          
        $customerId = Mage::getSingleton('customer/session')->getId();
        $filterByGroup = array("finset"=>'0');
        
        if($customerId)
        {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $filterByGroup = array("finset"=>$customer->getGroupId());
        }
        
        $filterAll = array("eq"=>'');
        $collection->addFieldToFilter('customer_groups', array($filterByGroup, $filterAll));

        $attributes = $collection->load();

        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('amorderattr', array());
        $fieldset->addType('radios' , 'Amasty_Orderattr_Block_Data_Form_Element_Radios');
        foreach ($attributes as $attribute)
        {
            $currentStore = Mage::app()->getStore()->getId();
            $storeIds = explode(',', $attribute->getData('store_ids'));
            if (!in_array($currentStore, $storeIds) && !in_array(0, $storeIds))
            {
                continue;
            }

            if ($inputType = $attribute->getFrontend()->getInputType())   
            {
                $afterElementHtml = '<div type="anchor" id="anchor_' . $attribute->getAttributeCode() . '"></div>'; //USING IN SCHEKOUT
                $fieldType      = $inputType;

               
                $rendererClass  = $attribute->getFrontend()->getInputRendererClass();
                if (!empty($rendererClass)) {
                    $fieldType  = $inputType . '_' . $attribute->getAttributeCode();
                    $fieldset->addType($fieldType, $rendererClass);
                }
                
                // global name space, will merge from all steps
                $fieldName = 'amorderattr[' . $attribute->getAttributeCode(). ']';
                                    
                // default_value
                $attributeValue = Mage::helper('amorderattr')->getAttributeValue($attribute);
                
                // applying translations
                $translations = $attribute->getStoreLabels();
                if (isset($translations[Mage::app()->getStore()->getId()]))
                {
                    $attributeLabel = $translations[Mage::app()->getStore()->getId()];
                } else 
                {
                    $attributeLabel = $attribute->getFrontend()->getLabel();
                }

                $required = 0;
                if ($attribute->getIsRequired() || $attribute->getRequiredOnFrontOnly()) {
                    $required = 1;
                }

                $elementOptions = array(
                    'name'      => $fieldName,
                    'label'     => $attributeLabel,
                    'class'     => $attribute->getFrontend()->getClass(),
                    'required'  => $required,
                    'note'      => $attribute->getNote(),
                    'value'     => $attributeValue
                );
                
                if ('date' == $inputType) {
                    $elementOptions['readonly'] = 1;
                    $elementOptions['onclick'] = 'amorderattr_trig(' . $attribute->getAttributeCode() . '_trig)';
                    $afterElementHtml .= '<script type="text/javascript">'
                                         . 'function amorderattr_trig(id)'
                                         . '{ $(id).click(); }'
                                         . '</script>';
                }
                
                if ($inputType == 'select' ||
                    $inputType == 'multiselect' ||
                    'checkboxes' == $inputType ||
                    $inputType =='boolean' ||
                    $inputType == 'radios'
                ) {
                    
                    $values = array();
                    // getting values translations
                    $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                        ->setAttributeFilter($attribute->getId())
                        ->setStoreFilter(Mage::app()->getStore()->getId(), true)
                        ->load();
                    foreach ($valuesCollection as $item) {
                        $values[$item->getId()] = $item->getValue();
                    }
                    // applying translations
                    $options = $attribute->getSource()->getAllOptions(true, true);
                    foreach ($options as $i => $option)
                    {
                        if (isset($values[$option['value']])&& ('' != $values[$option['value']]))
                        {
                            $options[$i]['label'] = $values[$option['value']];
                        }
                        else {
                            if ('checkboxes' == $inputType || 'radios' == $inputType)
                            {
                                unset($options[$i]);
                            }
                        }
                    }
                    
                }
                if('checkboxes' == $inputType){
                    if ($required) {
                         $this->_requiredCheckboxes[] = $attribute->getAttributeCode();
                         $elementOptions['class'] .= ' validate-checkboxgroup-required';
                    }
                    
                    $elementOptions['name'] .= '[]';
                    $elementOptions['values'] = $options;
                    $elementOptions['value'] = explode(',',$attributeValue);
                }
                if( 'radios' == $inputType){
                    if ($required) {
                        $this->_requiredCheckboxes[] = $attribute->getAttributeCode();
                        $elementOptions['class'] = ' validate-checkboxgroup-required';
                    }

                    $elementOptions['name'] .= '[]';
                    $elementOptions['values'] = $options;
                }
                else {
                     $afterElementHtml .= '<div style="padding: 4px;"></div>';
                }
                $element = $fieldset->addField($attribute->getAttributeCode(), $fieldType, $elementOptions)
                                    ->setEntityAttribute($attribute);

                if ($inputType == 'select' || $inputType == 'multiselect' || $inputType=='boolean' || $inputType=='radios') {
                    
                    if (($inputType == 'select') && $attribute->getParentDropdown())
                    {
                        $parentAttribute = Mage::getModel('eav/entity_attribute')->load($attribute->getParentDropdown());
                        $url = $this->getUrl('amorderattr/dropdown/getChildData');
                        if (isset($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS'])
                        {
                            $url = str_replace('http:', 'https:', $url);
                        }
                        if (Mage::getStoreConfig('amorderattr/dropdowns_ajax/enabled')){
                             $afterElementHtml .= '<script type="text/javascript">' . 
                                                    'peditGrid' . $attribute->getId() . ' = new amLinkedFieldsAjax("' . 
                                                    $parentAttribute->getAttributeCode() . '", "' . $attribute->getAttributeCode() . '","'.
                                                    $url.'","'.$this->__('Loading...').'",'.
                                                    $parentAttribute->getAttributeId() .');'.
                                                   '</script>';
                        }
                        else{
                            $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                                                ->setAttributeFilter($attribute->getId())
                                                ->load();
                            if ($valuesCollection->getSize() > 0)
                            {
                                $linkedRelationship = array();
                                foreach ($valuesCollection as $value)
                                {
                                    foreach ($options as $option)
                                    {
                                        if ($option['value'] == $value->getOptionId())
                                        {
                                            $linkedRelationship[$option['value']] = $value->getParentOptionId();
                                        }
                                    }
                                }
                                $optionsJson = Zend_Json::encode($options);
                                $relationsJson = Zend_Json::encode($linkedRelationship);
                                $afterElementHtml .= '<script type="text/javascript">' . 
                                                          'peditGrid' . $attribute->getId() . ' = new amLinkedFields("' .
                                                          $parentAttribute->getAttributeCode() . '", "' . $attribute->getAttributeCode() .
                                                          '", ' . $optionsJson . ', ' . $relationsJson . ');' .
                                                      '</script>';
                                if ($attributeValue)
                                {
                                    // applying saved for future checkout value
                                    $afterElementHtml .= '<script type="text/javascript">' . 
                                                             '$$("#' . $attribute->getAttributeCode() . ' option[value=' . 
                                                             $attributeValue . ']").each(function(elem){ elem.selected = true; }); ' .
                                                         '</script>';
                                } 
                            }
                        }
                    } 
                    else 
                    {
                        $element->setValues($options);
                    }
                } elseif ($inputType == 'date' && 'time' != $attribute->getNote()) {
                    $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
                    $element->setFormat(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
                } elseif ($inputType == 'date' && 'time' == $attribute->getNote())
                {
                    $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
                    $element->setFormat(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT) . ' HH:mm');
                    $element->setTime(true);
                }

                $element->setAfterElementHtml($afterElementHtml);
            }
        }
        $this->_formElements = $form->getElements();
        
        return $this->_formElements;
    }

    protected function _toHtml()
    {
        if (!$this->getFormElements())
        {
            return '';
        }
        $html = parent::_toHtml();
        // including JS once
        if (!Mage::registry('amorderattr_js_added'))
        {
            $jsSrc = '<script type="text/javascript" src="' . Mage::getBaseUrl('js') . 'amasty/amorderattr/payment.js">' . '</script>
                <script type="text/javascript" src="' . Mage::getBaseUrl('js') . 'amasty/amorderattr/conditions.js">' . '</script>
            ';
            $html = $jsSrc . $html;
            Mage::register('amorderattr_js_added', true);
        }
        
        $html = str_replace('</label>', '</label><div style="clear: both;"></div>', $html);
        foreach ($this->_requiredCheckboxes as $key=>$value) {
                 $pattern = '/(input id="'.$value.'_[0-9]*")/';
                 $replacement = '${1} class="validate-checkboxgroup-required"';
                 $html = preg_replace($pattern, $replacement, $html);
        }

        return $html;
    }
    
    function getShippingMethods ()
    {
        $ret = array();
        $model = Mage::getModel('amorderattr/shipping_methods');
        
        foreach($model->getCollection() as $method){
            
            $attribute = Mage::getModel('eav/entity_attribute')->load($method->getAttributeId());
            $attributeCode = $attribute->getAttributeCode();
            $shippingMethod = $method->getShippingMethod();
            
            if (!isset($ret[$shippingMethod]))
                $ret[$shippingMethod] = array();
                    
            $ret[$shippingMethod][$attributeCode] = $attributeCode;
            
        }
        
        return $ret;
        
    }
    
}