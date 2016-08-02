<?php

/**
 * RMA Helper
 *
 * @category    Enterprise
 * @package     Enterprise_Rma
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Icube_Rma_Helper_Data extends Enterprise_Rma_Helper_Data
{
    protected $_aviableProductTypes = array(
        Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
        Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE,
        Mage_Catalog_Model_Product_Type::TYPE_GROUPED,
        Mage_Catalog_Model_Product_Type::TYPE_BUNDLE
    );

    /**
     * Gets available order items collection for RMA creating
     *
     * @param  int|Mage_Sales_Model_Order $orderId
     * @param  bool $onlyParents If needs only parent items (only for backend)
     * @param  bool $isAdmin whether need to check admin access to the product
     * @throws Mage_Core_Exception
     * @return Mage_Sales_Model_Resource_Order_Item_Collection
     */
    public function getOrderItems($orderId, $onlyParents = false, $isAdmin = false)
    {
        if ($orderId instanceof Mage_Sales_Model_Order) {
            $orderId = $orderId->getId();
        }
        if (!is_numeric($orderId)) {
            Mage::throwException($this->__('It isn\'t valid order'));
        }
        if (is_null($this->_orderItems) || !isset($this->_orderItems[$orderId])) {
            if (!$isAdmin) {
                $this->_orderItems[$orderId] = $this->getOrderItemsCollection($orderId);
            } else {
                $this->_orderItems[$orderId] =
                        Mage::getResourceModel('enterprise_rma/item')->getOrderItemsForAdmin($orderId);
            }
        }

        if ($onlyParents) {
            foreach ($this->_orderItems[$orderId] as &$item) {
                if ($item->getParentItemId()) {
                    $this->_orderItems[$orderId]->removeItemByKey($item->getId());
                }
                if ($item->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                    $productOptions = $item->getProductOptions();
                    $item->setName($productOptions['simple_name']);
                }
            }
        }

        return $this->_orderItems[$orderId];
    }

    public function getOrderItemsCollection($orderId)
    {
        $collection = Mage::getModel('sales/order_item')
            ->getCollection()
            ->addExpressionFieldToSelect(
                'available_qty',
                '(qty_shipped - qty_returned)',
                array('qty_shipped', 'qty_returned')
            )
            ->addFieldToFilter('main_table.order_id', $orderId)
            ->addFieldToFilter('product_type', array("in" => $this->_aviableProductTypes))
            ->addAvailableFilter();

        $collection->getSelect()
            ->joinLeft( array('invoice'=> sales_flat_invoice), 'invoice.order_id = main_table.order_id', array('invoice.increment_id'))
            ->joinLeft( array('invoice_item'=> sales_flat_invoice_item), 'invoice_item.parent_id = invoice.entity_id', array('invoice_item.entity_id'))
            ->where('invoice.increment_id IS NOT NULL AND main_table.order_id = '.$orderId)
            ->group('main_table.item_id');
        
        return $collection;
    }


}
