<?php

class Fooman_Surcharge_Helper_Compatibility extends Mage_Core_Helper_Abstract
{

    public function escapeHtmlByVersion($input, $allowedTags = null)
    {
        $helper = Mage::helper('core');
        if (method_exists($helper, 'escapeHtml')) {
            return Mage::helper('core')->escapeHtml($input, $allowedTags);
        } else {
            return Mage::helper('core')->htmlEscape($input, $allowedTags);
        }
    }
}