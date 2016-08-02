<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2015-06-17T15:12:10+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/Columns/Thirdparty/Columns.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_Columns_Thirdparty_Columns extends Mage_Core_Model_Abstract
{
    /*
     * Get columns added by third party modules
     */
    public function getCustomColumns()
    {
        $customColumns = array();

        // One Step Checkout
        if (Mage::helper('xtcore/utils')->isExtensionInstalled('Idev_OneStepCheckout')) {
            $customColumns[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER]['onestepcheckout_customercomment'] = array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Customer Comment (OneStepCheckout)'),
                'id' => 'onestepcheckout_customercomment',
                'index' => 'onestepcheckout_customercomment',
                'filter_index' => 'order.onestepcheckout_customercomment',
                'join_left' => array(
                    'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                    'cond' => 'main_table.entity_id = order.entity_id',
                    'cols' => array('onestepcheckout_customercomment' => 'order.onestepcheckout_customercomment')
                ),
                'type' => 'text',
                'change_filter' => false,
                'change_renderer' => false,
            );
        }

        // GoMage Light Checkout
        if (Mage::helper('xtcore/utils')->isExtensionInstalled('GoMage_Checkout')) {
            $customColumns[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER]['gomage_checkout_customer_comment'] = array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Customer Comment (GoMage)'),
                'id' => 'gomage_checkout_customer_comment',
                'index' => 'gomage_checkout_customer_comment',
                'filter_index' => 'order.gomage_checkout_customer_comment',
                'join_left' => array(
                    'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                    'cond' => 'main_table.entity_id = order.entity_id',
                    'cols' => array('gomage_checkout_customer_comment' => 'order.gomage_checkout_customer_comment')
                ),
                'type' => 'text',
                'change_filter' => false,
                'change_renderer' => false,
            );
        }

        // M2EPro
        if (Mage::helper('xtcore/utils')->isExtensionInstalled('Ess_M2ePro')) {
            $customColumns[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER]['order_source'] = array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Order Source (M2EPro)'),
                'id' => 'order_source',
                'index' => 'order_source',
                'filter' => false,
                'sortable' => false,
                'align' => 'center',
                'join_left' => array(
                    'name' => array('payment' => Mage::getSingleton('core/resource')->getTableName('sales/order_payment')),
                    'cond' => 'main_table.entity_id = payment.parent_id',
                    'cols' => array('payment_method2' => 'payment.method')
                ),
                'change_filter' => false,
                'change_renderer' => false,
                'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_OrderSource'
            );
            $customColumns[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER]['ebay_user_id'] = array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('eBay User ID (M2EPro)'),
                'id' => 'ebay_user_id',
                'index' => 'ebay_user_id',
                'filter_index' => 'ebay_order.buyer_user_id',
                'align' => 'center',
                'join_left' => array(
                    'name' => array('ebay_order' => Mage::getSingleton('core/resource')->getTableName('M2ePro/Ebay_Order')),
                    'cond' => 'ebay_order.order_id = (SELECT id from '.Mage::getSingleton('core/resource')->getTableName('m2epro_order') . ' WHERE magento_order_id = main_table.entity_id)',
                    'cols' => array('ebay_user_id' => 'ebay_order.buyer_user_id')
                ),
                'change_filter' => false
            );
            $customColumns[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER]['amazon_buyer_name'] = array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Amazon Buyer Name (M2EPro)'),
                'id' => 'amazon_buyer_name',
                'index' => 'amazon_buyer_name',
                'filter_index' => 'amazon_order.buyer_name',
                'align' => 'center',
                'join_left' => array(
                    'name' => array('amazon_order' => Mage::getSingleton('core/resource')->getTableName('M2ePro/Amazon_Order')),
                    'cond' => 'amazon_order.order_id = (SELECT id from '.Mage::getSingleton('core/resource')->getTableName('m2epro_order') . ' WHERE magento_order_id = main_table.entity_id)',
                    'cols' => array('amazon_buyer_name' => 'amazon_order.buyer_name')
                ),
                'change_filter' => false
            );
            $customColumns[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER]['amazon_buyer_email'] = array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Amazon Buyer Email (M2EPro)'),
                'id' => 'amazon_buyer_email',
                'index' => 'amazon_buyer_email',
                'filter_index' => 'amazon_order.buyer_email',
                'align' => 'center',
                'join_left' => array(
                    'name' => array('amazon_order' => Mage::getSingleton('core/resource')->getTableName('M2ePro/Amazon_Order')),
                    'cond' => 'amazon_order.order_id = (SELECT id from '.Mage::getSingleton('core/resource')->getTableName('m2epro_order') . ' WHERE magento_order_id = main_table.entity_id)',
                    'cols' => array('amazon_buyer_email' => 'amazon_order.buyer_email')
                ),
                'change_filter' => false
            );
            $customColumns[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER]['amazon_order_id'] = array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Amazon Order ID (M2EPro)'),
                'id' => 'amazon_order_id',
                'index' => 'amazon_order_id',
                'filter_index' => 'amazon_order.amazon_order_id',
                'align' => 'center',
                'join_left' => array(
                    'name' => array('amazon_order' => Mage::getSingleton('core/resource')->getTableName('M2ePro/Amazon_Order')),
                    'cond' => 'amazon_order.order_id = (SELECT id from '.Mage::getSingleton('core/resource')->getTableName('m2epro_order') . ' WHERE magento_order_id = main_table.entity_id)',
                    'cols' => array('amazon_order_id' => 'amazon_order.amazon_order_id')
                ),
                'change_filter' => false
            );
        }

        // Aitoc Delivery Date
        if (Mage::helper('xtcore/utils')->isExtensionInstalled('AdjustWare_Deliverydate')) {
            $customColumns[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER]['delivery_date'] = array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Delivery Date (Aitoc)'),
                'id' => 'delivery_date',
                'index' => 'delivery_date',
                'filter_index' => 'order.delivery_date',
                'join_left' => array(
                    'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                    'cond' => 'main_table.entity_id = order.entity_id',
                    'cols' => array('delivery_date' => 'order.delivery_date')
                ),
                'type' => 'datetime',
                #'change_filter' => false,
                #'change_renderer' => false,
            );
            $customColumns[Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER]['delivery_comment'] = array(
                'header' => Mage::helper('xtento_enhancedgrid')->__('Delivery Comment (Aitoc)'),
                'id' => 'delivery_comment',
                'index' => 'delivery_comment',
                'filter_index' => 'order.delivery_comment',
                'join_left' => array(
                    'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                    'cond' => 'main_table.entity_id = order.entity_id',
                    'cols' => array('delivery_comment' => 'order.delivery_comment')
                ),
                'type' => 'text',
                'change_filter' => false,
                'change_renderer' => false,
            );
        }

        return $customColumns;
    }

    /*
     *
     * Various helper functions
     *
     */
}