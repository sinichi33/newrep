<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Adminhtml_Order_Grid_Renderer_Datetime extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime
{
    public function render(Varien_Object $row)
    {
        if ($data = $this->_getValue($row)) {
            if ($data === '0000-00-00' ||
                $data === '0000-00-00 00:00:00') {
                return '';
            }
            $format = $this->_getFormat();
            try {
                $data = Mage::app()->getLocale()->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT, null, false)->toString($format);
            }
            catch (Exception $e)
            {
                $data = Mage::app()->getLocale()->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT, null, false)->toString($format);
            }
            return $data;
        }
        return $this->getColumn()->getDefault();
    }
}