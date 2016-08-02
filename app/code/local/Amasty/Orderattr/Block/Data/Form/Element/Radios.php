<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Data_Form_Element_Radios extends Varien_Data_Form_Element_Abstract
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('radio');
        $this->setExtType('radio');
    }

    public function getHtmlAttributes()
    {
        return array('type', 'name', 'class', 'style', 'checked', 'onclick', 'onchange', 'disabled');
    }

    public function getElementHtml()
    {
        $value = $this->getValue();

        $html  = '<ul class="radios">';
        if ($values = $this->getValues()) {
            foreach ($values as $option) {
                $html .= $this->_optionToHtml($option, $value);
            }
        }
        $html .= '</ul>'
            . $this->getAfterElementHtml();

        return $html;
    }

    public function getChecked($value)
    {
        if ($checked = $this->getValue()) {
        }
        elseif ($checked = $this->getData('checked')) {
        }
        else {
            return ;
        }
        if (!is_array($checked)) {
            $checked = array(strval($checked));
        }
        else {
            foreach ($checked as $k => $v) {
                $checked[$k] = strval($v);
            }
        }
        if (in_array(strval($value), $checked)) {
            return 'checked';
        }
        return ;
    }



    protected function _optionToHtml($option, $selected)
    {
        $id = $this->getHtmlId().'_'.$this->_escape($option['value']);

        $html = '<li><input id="'.$id.'"';
        foreach ($this->getHtmlAttributes() as $attribute) {
            if ($value = $this->getDataUsingMethod($attribute, $option['value'])) {
                $html .= ' '.$attribute.'="'.$value.'"';
            }
        }
        $html .= ' value="'.$option['value'].'" />'
            . ' <label for="'.$id.'">' . $option['label'] . '</label></li>'
            . "\n";
        return $html;

    }
}