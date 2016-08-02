<?php
/**
 * Admin RMA create order grid block
 *
 */

class Icube_Rma_Block_Adminhtml_Rma_New_Tab_Items_Order_Grid
    extends Enterprise_Rma_Block_Adminhtml_Rma_New_Tab_Items_Order_Grid
{
    
    /**
     * Prepare columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('select', array(
            'header'   => Mage::helper('enterprise_rma')->__('Select'),
            'width'    => '40px',
            'type'     => 'checkbox',
            'align'    => 'center',
            'sortable' => false,
            'index'    => 'item_id',
            'values'   => $this->_getSelectedProducts(),
            'name'     => 'in_products',
        ));

        $this->addColumn('product_name', array(
            'header'   => Mage::helper('enterprise_rma')->__('Product Name'),
            'renderer' => 'enterprise_rma/adminhtml_product_bundle_product',
            'index'    => 'name',
            'escape'   => true,
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('enterprise_rma')->__('SKU'),
            'width'  => '80px',
            'type'   => 'text',
            'index'  => 'sku',
            'escape' => true,
        ));

        $this->addColumn('price', array(
            'header'=> Mage::helper('enterprise_rma')->__('Price'),
            'width' => '80px',
            'type'  => 'currency',
            'index' => 'price',
        ));

        $this->addColumn('available_qty', array(
            'header'=> Mage::helper('enterprise_rma')->__('Remaining Qty'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'available_qty',
            'renderer'  => 'enterprise_rma/adminhtml_rma_edit_tab_items_grid_column_renderer_quantity',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('delivery_pickup', array(
            'header' => Mage::helper('enterprise_rma')->__('Method'),
            'width'  => '80px',
            'type'   => 'text',
            'index'  => 'delivery_pickup',
            'escape' => true,
            'column_css_class' => 'deliv-method'
        ));

        $this->addColumn('store_code', array(
            'header' => Mage::helper('enterprise_rma')->__('Store Code'),
            'width'  => '80px',
            'type'   => 'text',
            'index'  => 'store_code',
            'escape' => true,
        ));


        return parent::_prepareColumns();
    }


}
