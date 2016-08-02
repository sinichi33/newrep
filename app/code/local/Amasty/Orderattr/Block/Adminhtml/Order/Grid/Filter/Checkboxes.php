<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Adminhtml_Order_Grid_Filter_Checkboxes extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select
{
    public function getCondition()
    {
        return array('finset' => $this->getValue());
    }
}