<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
if (Mage::helper('core')->isModuleEnabled('Amasty_Deliverydate')) {
    class Amasty_Orderattr_Model_Sales_Order_Api_Pure extends Amasty_Deliverydate_Model_Sales_Order_Api {}
} else {
    class Amasty_Orderattr_Model_Sales_Order_Api_Pure extends Mage_Sales_Model_Order_Api {}
}
