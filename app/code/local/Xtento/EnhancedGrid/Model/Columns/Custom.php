<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2016-01-04T12:40:01+01:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/Columns/Custom.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_Columns_Custom extends Mage_Core_Model_Abstract
{

    public function getCustomColumns($specificGrid = false)
    {
        $customColumns = array(
            Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER => array(
                'payment_method' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Payment Method'),
                    'id' => 'payment_method',
                    'index' => 'payment_method',
                    'filter_index' => 'payment.method',
                    'join_left' => array(
                        'name' => array('payment' => Mage::getSingleton('core/resource')->getTableName('sales/order_payment')),
                        'cond' => 'main_table.entity_id = payment.parent_id',
                        'cols' => array('payment_method' => 'payment.method')
                    ),
                    'type' => 'options',
                    'options' => Mage::helper('xtcore/payment')->getPaymentMethodList(true, false, false, null),
                    'filter' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Filter_PaymentMethod',
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_PaymentMethod',
                    'change_renderer' => false,
                    'change_filter' => false
                ),
                'payment_po_number' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('PO Number'),
                    'id' => 'payment_po_number',
                    'index' => 'payment_po_number',
                    'filter_index' => 'payment.po_number',
                    'join_left' => array(
                        'name' => array('payment' => Mage::getSingleton('core/resource')->getTableName('sales/order_payment')),
                        'cond' => 'main_table.entity_id = payment.parent_id',
                        'cols' => array('payment_po_number' => 'payment.po_number')
                    ),
                    'type' => 'text'
                ),
                'coupon_code' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Coupon Code'),
                    'id' => 'coupon_code',
                    'index' => 'coupon_code',
                    'filter_index' => 'order.coupon_code',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('coupon_code' => 'order.coupon_code')
                    ),
                    'type' => 'text'
                ),
                'weight' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Order Weight'),
                    'id' => 'weight',
                    'index' => 'weight',
                    'filter_index' => 'order.weight',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('weight' => 'order.weight')
                    ),
                    'type' => 'text'
                ),
                'shipping_description' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Method'),
                    'id' => 'shipping_description',
                    'index' => 'shipping_description',
                    'filter_index' => 'order.shipping_description',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('shipping_description' => 'order.shipping_description')
                    ),
                    'type' => 'text'
                ),
                'shipping_method' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Method Code'),
                    'id' => 'shipping_method',
                    'index' => 'shipping_method',
                    'filter_index' => 'order.shipping_method',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('shipping_method' => 'order.shipping_method')
                    ),
                    'type' => 'text'
                ),
                'customer_email' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Customer E-Mail'),
                    'id' => 'customer_email',
                    'index' => 'customer_email',
                    'filter_index' => 'order.customer_email',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('customer_email' => 'order.customer_email')
                    ),
                    'type' => 'text'
                ),
                'customer_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Customer ID'),
                    'id' => 'customer_id',
                    'index' => 'customer_id',
                    'filter_index' => 'order.customer_id',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('customer_id' => 'order.customer_id')
                    ),
                    'type' => 'text'
                ),
                'customer_firstname' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Customer Firstname'),
                    'id' => 'customer_firstname',
                    'index' => 'customer_firstname',
                    'filter_index' => 'order.customer_firstname',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('customer_firstname' => 'order.customer_firstname')
                    ),
                    'type' => 'text'
                ),
                'customer_lastname' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Customer Lastname'),
                    'id' => 'customer_lastname',
                    'index' => 'customer_lastname',
                    'filter_index' => 'order.customer_lastname',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('customer_lastname' => 'order.customer_lastname')
                    ),
                    'type' => 'text'
                ),
                'customer_group_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Customer Group'),
                    'id' => 'customer_group_id',
                    'index' => 'customer_group_id',
                    'filter_index' => 'order.customer_group_id',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('customer_group_id' => 'order.customer_group_id')
                    ),
                    'type' => 'options',
                    'options' => Mage::getResourceModel('customer/group_collection')
                        #->addFieldToFilter('customer_group_id', array('gt' => 0))
                        ->load()
                        ->toOptionHash(),
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select'
                ),
                'customer_taxvat' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('TAX/VAT Number'),
                    'id' => 'customer_taxvat',
                    'index' => 'customer_taxvat',
                    'filter_index' => 'order.customer_taxvat',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('customer_taxvat' => 'order.customer_taxvat')
                    ),
                    'type' => 'text'
                ),
                'full_billing_address' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Full Billing Address'),
                    'id' => 'full_billing_address',
                    'index' => 'full_billing_address',
                    'filter' => false,
                    'sortable' => false,
                    'change_filter' => false,
                    'change_renderer' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Address',
                ),
                'billing_firstname' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing Firstname'),
                    'id' => 'billing_firstname',
                    'index' => 'billing_firstname',
                    'filter_index' => 'billing_address.firstname',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_firstname' => 'billing_address.firstname')
                    ),
                    'type' => 'text',
                ),
                'billing_lastname' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing Lastname'),
                    'id' => 'billing_lastname',
                    'index' => 'billing_lastname',
                    'filter_index' => 'billing_address.lastname',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_lastname' => 'billing_address.lastname')
                    ),
                    'type' => 'text',
                ),
                'billing_company' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing Company'),
                    'id' => 'billing_company',
                    'index' => 'billing_company',
                    'filter_index' => 'billing_address.company',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_company' => 'billing_address.company')
                    ),
                    'type' => 'text',
                ),
                'billing_street' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing Street'),
                    'id' => 'billing_street',
                    'index' => 'billing_street',
                    'filter_index' => 'billing_address.street',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_street' => 'billing_address.street')
                    ),
                    'type' => 'text',
                ),
                'billing_telephone' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing Telephone'),
                    'id' => 'billing_telephone',
                    'index' => 'billing_telephone',
                    'filter_index' => 'billing_address.telephone',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_telephone' => 'billing_address.telephone')
                    ),
                    'type' => 'text',
                ),
                'billing_postcode' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing Postcode'),
                    'id' => 'billing_postcode',
                    'index' => 'billing_postcode',
                    'filter_index' => 'billing_address.postcode',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_postcode' => 'billing_address.postcode')
                    ),
                    'type' => 'text',
                ),
                'billing_city' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing City'),
                    'id' => 'billing_city',
                    'index' => 'billing_city',
                    'filter_index' => 'billing_address.city',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_city' => 'billing_address.city')
                    ),
                    'type' => 'text',
                ),
                'billing_region' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing Region'),
                    'id' => 'billing_region',
                    'index' => 'billing_region',
                    'filter_index' => 'billing_address.region',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_region' => 'billing_address.region')
                    ),
                    'type' => 'text',
                ),
                'billing_country_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing Country'),
                    'id' => 'billing_country_id',
                    'index' => 'billing_country_id',
                    'filter_index' => 'billing_address.country_id',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_country_id' => 'billing_address.country_id')
                    ),
                    'type' => 'text',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Country',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Country'
                ),
                'full_shipping_address' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Full Shipping Address'),
                    'id' => 'full_shipping_address',
                    'index' => 'full_shipping_address',
                    'filter' => false,
                    'sortable' => false,
                    'change_filter' => false,
                    'change_renderer' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Address',
                ),
                'shipping_firstname' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Firstname'),
                    'id' => 'shipping_firstname',
                    'index' => 'shipping_firstname',
                    'filter_index' => 'shipping_address.firstname',
                    'join_left' => array(
                        'name' => array('shipping_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = shipping_address.parent_id AND shipping_address.address_type="shipping"',
                        'cols' => array('shipping_firstname' => 'shipping_address.firstname')
                    ),
                    'type' => 'text',
                ),
                'shipping_lastname' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Lastname'),
                    'id' => 'shipping_lastname',
                    'index' => 'shipping_lastname',
                    'filter_index' => 'shipping_address.lastname',
                    'join_left' => array(
                        'name' => array('shipping_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = shipping_address.parent_id AND shipping_address.address_type="shipping"',
                        'cols' => array('shipping_lastname' => 'shipping_address.lastname')
                    ),
                    'type' => 'text',
                ),
                'shipping_company' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Company'),
                    'id' => 'shipping_company',
                    'index' => 'shipping_company',
                    'filter_index' => 'shipping_address.company',
                    'join_left' => array(
                        'name' => array('shipping_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = shipping_address.parent_id AND shipping_address.address_type="shipping"',
                        'cols' => array('shipping_company' => 'shipping_address.company')
                    ),
                    'type' => 'text',
                ),
                'shipping_street' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Street'),
                    'id' => 'shipping_street',
                    'index' => 'shipping_street',
                    'filter_index' => 'shipping_address.street',
                    'join_left' => array(
                        'name' => array('shipping_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = shipping_address.parent_id AND shipping_address.address_type="shipping"',
                        'cols' => array('shipping_street' => 'shipping_address.street')
                    ),
                    'type' => 'text',
                ),
                'shipping_telephone' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Telephone'),
                    'id' => 'shipping_telephone',
                    'index' => 'shipping_telephone',
                    'filter_index' => 'shipping_address.telephone',
                    'join_left' => array(
                        'name' => array('shipping_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = shipping_address.parent_id AND shipping_address.address_type="shipping"',
                        'cols' => array('shipping_telephone' => 'shipping_address.telephone')
                    ),
                    'type' => 'text',
                ),
                'shipping_postcode' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Postcode'),
                    'id' => 'shipping_postcode',
                    'index' => 'shipping_postcode',
                    'filter_index' => 'shipping_address.postcode',
                    'join_left' => array(
                        'name' => array('shipping_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = shipping_address.parent_id AND shipping_address.address_type="shipping"',
                        'cols' => array('shipping_postcode' => 'shipping_address.postcode')
                    ),
                    'type' => 'text',
                ),
                'shipping_city' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping City'),
                    'id' => 'shipping_city',
                    'index' => 'shipping_city',
                    'filter_index' => 'shipping_address.city',
                    'join_left' => array(
                        'name' => array('shipping_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = shipping_address.parent_id AND shipping_address.address_type="shipping"',
                        'cols' => array('shipping_city' => 'shipping_address.city')
                    ),
                    'type' => 'text',
                ),
                'shipping_region' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Region'),
                    'id' => 'shipping_region',
                    'index' => 'shipping_region',
                    'filter_index' => 'shipping_address.region',
                    'join_left' => array(
                        'name' => array('shipping_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = shipping_address.parent_id AND shipping_address.address_type="shipping"',
                        'cols' => array('shipping_region' => 'shipping_address.region')
                    ),
                    'type' => 'text',
                ),
                'shipping_country_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Country'),
                    'id' => 'shipping_country_id',
                    'index' => 'shipping_country_id',
                    'filter_index' => 'shipping_address.country_id',
                    'join_left' => array(
                        'name' => array('shipping_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.entity_id = shipping_address.parent_id AND shipping_address.address_type="shipping"',
                        'cols' => array('shipping_country_id' => 'shipping_address.country_id')
                    ),
                    'type' => 'text',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Country',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Country'
                ),
                'base_shipping_amount' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Amount'),
                    'id' => 'base_shipping_amount',
                    'index' => 'base_shipping_amount',
                    'filter_index' => 'order.base_shipping_amount',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('base_shipping_amount' => 'order.base_shipping_amount')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_shipping_incl_tax' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping (Incl. Tax)'),
                    'id' => 'base_shipping_incl_tax',
                    'index' => 'base_shipping_incl_tax',
                    'filter_index' => 'order.base_shipping_incl_tax',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('base_shipping_incl_tax' => 'order.base_shipping_incl_tax')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_subtotal' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Subtotal'),
                    'id' => 'base_subtotal',
                    'index' => 'base_subtotal',
                    'filter_index' => 'order.base_subtotal',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('base_subtotal' => 'order.base_subtotal')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_tax_amount' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Tax Amount'),
                    'id' => 'base_tax_amount',
                    'index' => 'base_tax_amount',
                    'filter_index' => 'order.base_tax_amount',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('base_tax_amount' => 'order.base_tax_amount')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_discount_amount' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Discount Amount'),
                    'id' => 'base_discount_amount',
                    'index' => 'base_discount_amount',
                    'filter_index' => 'order.base_discount_amount',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('base_discount_amount' => 'order.base_discount_amount')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_grand_total_excl' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Grand Total (Excl. Tax)'),
                    'id' => 'base_grand_total_excl',
                    'index' => 'base_grand_total_excl',
                    'filter_index' => '(order.base_grand_total - order.base_tax_amount)',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('base_grand_total_excl' => '(order.base_grand_total - order.base_tax_amount)')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'total_qty_ordered' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Total Qty Ordered'),
                    'id' => 'total_qty_ordered',
                    'index' => 'total_qty_ordered',
                    'filter_index' => 'order.total_qty_ordered',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('total_qty_ordered' => 'order.total_qty_ordered')
                    ),
                    'type' => 'number',
                    'currency' => 'total_qty_ordered',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Number',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'total_offline_refunded' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Total Offline Refunded'),
                    'id' => 'total_offline_refunded',
                    'index' => 'total_offline_refunded',
                    'filter_index' => 'order.total_offline_refunded',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('total_offline_refunded' => 'order.total_offline_refunded')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'total_online_refunded' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Total Online Refunded'),
                    'id' => 'total_online_refunded',
                    'index' => 'total_online_refunded',
                    'filter_index' => 'order.total_online_refunded',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('total_online_refunded' => 'order.total_online_refunded')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'total_refunded' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Total Refunded'),
                    'id' => 'total_refunded',
                    'index' => 'total_refunded',
                    'filter_index' => 'order.total_refunded',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.entity_id = order.entity_id',
                        'cols' => array('total_refunded' => 'order.total_refunded')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'purchased_items' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Purchased Items'),
                    'id' => 'purchased_items',
                    'index' => 'purchased_items',
                    'filter_index' => 'order_item.sku', // item_filter
                    /*'join' => array(
                        'table' => 'sales/order_item',
                        'condition' => '{{table}}.order_id=main_table.entity_id',
                        'field' => array(
                            'item_skus' => new Zend_Db_Expr('group_concat(sales/order_item.sku SEPARATOR ",")'),
                            'item_names' => new Zend_Db_Expr('group_concat(sales/order_item.name SEPARATOR ",")'),
                            //'item_filter' => new Zend_Db_Expr('concat(group_concat(sales/order_item.name SEPARATOR ","), ",", group_concat(sales/order_item.sku SEPARATOR ","))'),
                        )
                    ),*/
                    'change_filter' => false,
                    'change_renderer' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Items',
                    //'filter_condition_callback' => array('Xtento_EnhancedGrid_Block_Widget_Grid_Column_Filter_Items', 'itemFilter'),
                ),
                'backordered_items' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Backordered Items'),
                    'id' => 'backordered_items',
                    'index' => 'backordered_items',
                    'filter' => false,
                    'sortable' => false,
                    'align' => 'center',
                    'change_filter' => false,
                    'change_renderer' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Backordered',
                ),
                'tracking_numbers' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Tracking Numbers'),
                    'id' => 'tracking_numbers',
                    'index' => 'tracking_numbers',
                    'change_filter' => false,
                    'change_renderer' => false,
                    'type' => 'text',
                    'width' => 170,
                    'sortable' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Trackingnumber',
                    'filter' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Trackingnumber'
                ),
                'tracking_table' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Carrier / Tracking'),
                    'id' => 'tracking_table',
                    'index' => 'tracking_table',
                    'change_filter' => false,
                    'change_renderer' => false,
                    'type' => 'text',
                    'width' => 190,
                    'sortable' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Trackingtable',
                    'filter' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Trackingtable'
                ),
                'comment_history' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Comment History'),
                    'id' => 'comment_history',
                    'index' => 'comment_history',
                    'change_filter' => false,
                    'change_renderer' => false,
                    'type' => 'text',
                    'width' => 170,
                    'sortable' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Commenthistory',
                    'filter' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Commenthistory'
                ),
                'store_view_name' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Store View'),
                    'id' => 'store_view_name',
                    'index' => 'store_view_name',
                    'filter_index' => 'main_table.store_id',
                    'join_left' => array(
                        'name' => array('store' => Mage::getSingleton('core/resource')->getTableName('core/store')),
                        'cond' => 'main_table.store_id = store.store_id',
                        'cols' => array('store_view_name' => 'name')
                    ),
                    'type' => 'text',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Store'
                ),
                'invoice_increment_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Invoice #'),
                    'id' => 'invoice_increment_id',
                    'index' => 'invoice_increment_id',
                    'filter_index' => 'invoice.increment_id',
                    'join_left' => array(
                        'name' => array('invoice' => Mage::getSingleton('core/resource')->getTableName('sales/invoice')),
                        'cond' => 'main_table.entity_id = invoice.order_id',
                        'cols' => array('invoice_increment_id' => new Zend_Db_Expr('group_concat(invoice.increment_id SEPARATOR ", ")'))
                    ),
                    'type' => 'text'
                ),
                'invoice_created_at' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Invoice Created At'),
                    'id' => 'invoice_created_at',
                    'index' => 'invoice_created_at',
                    'filter_index' => 'invoice.created_at',
                    'join_left' => array(
                        'name' => array('invoice' => Mage::getSingleton('core/resource')->getTableName('sales/invoice')),
                        'cond' => 'main_table.entity_id = invoice.order_id',
                        'cols' => array('invoice_created_at' => new Zend_Db_Expr('max(invoice.created_at)'))
                    ),
                    'type' => 'datetime',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Datetime',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime',
                ),
                'shipment_increment_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipment #'),
                    'id' => 'shipment_increment_id',
                    'index' => 'shipment_increment_id',
                    'filter_index' => 'shipment.increment_id',
                    'join_left' => array(
                        'name' => array('shipment' => Mage::getSingleton('core/resource')->getTableName('sales/shipment')),
                        'cond' => 'main_table.entity_id = shipment.order_id',
                        'cols' => array('shipment_increment_id' => new Zend_Db_Expr('group_concat(shipment.increment_id SEPARATOR ", ")'))
                    ),
                    'type' => 'text'
                ),
                'shipment_created_at' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipment Created At'),
                    'id' => 'shipment_created_at',
                    'index' => 'shipment_created_at',
                    'filter_index' => 'shipment.created_at',
                    'join_left' => array(
                        'name' => array('shipment' => Mage::getSingleton('core/resource')->getTableName('sales/shipment')),
                        'cond' => 'main_table.entity_id = shipment.order_id',
                        'cols' => array('shipment_created_at' => new Zend_Db_Expr('max(shipment.created_at)'))
                    ),
                    'type' => 'datetime',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Datetime',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime',
                ),
                'creditmemo_increment_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Credit Memo #'),
                    'id' => 'creditmemo_increment_id',
                    'index' => 'creditmemo_increment_id',
                    'filter_index' => 'creditmemo.increment_id',
                    'join_left' => array(
                        'name' => array('creditmemo' => Mage::getSingleton('core/resource')->getTableName('sales/creditmemo')),
                        'cond' => 'main_table.entity_id = creditmemo.order_id',
                        'cols' => array('creditmemo_increment_id' => new Zend_Db_Expr('group_concat(creditmemo.increment_id SEPARATOR ", ")'))
                    ),
                    'type' => 'text'
                ),
                'creditmemo_created_at' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Credit Memo Created At'),
                    'id' => 'creditmemo_created_at',
                    'index' => 'creditmemo_created_at',
                    'filter_index' => 'creditmemo.created_at',
                    'join_left' => array(
                        'name' => array('creditmemo' => Mage::getSingleton('core/resource')->getTableName('sales/creditmemo')),
                        'cond' => 'main_table.entity_id = creditmemo.order_id',
                        'cols' => array('creditmemo_created_at' => new Zend_Db_Expr('max(creditmemo.created_at)'))
                    ),
                    'type' => 'datetime',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Datetime',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime',
                ),
                'order_gift_message' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Gift Message (Order)'),
                    'id' => 'order_gift_message',
                    'index' => 'order_gift_message',
                    'filter_index' => 'giftmessage.message',
                    'join_left' => array(
                        'name' => array('giftmessage' => Mage::getSingleton('core/resource')->getTableName('giftmessage/message')),
                        'cond' => 'giftmessage.gift_message_id = (SELECT ordergift.gift_message_id FROM '.Mage::getSingleton('core/resource')->getTableName('sales/order').' AS ordergift WHERE ordergift.entity_id = main_table.entity_id GROUP BY main_table.entity_id)',
                        'cols' => array('order_gift_message' => 'giftmessage.message')
                    ),
                    'type' => 'text'
                ),
            ),
            Xtento_EnhancedGrid_Model_Grid::GRID_SALES_INVOICE => array(
                'billing_country_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Billing Country'),
                    'id' => 'billing_country_id',
                    'index' => 'billing_country_id',
                    'filter_index' => 'billing_address.country_id',
                    'join_left' => array(
                        'name' => array('billing_address' => Mage::getSingleton('core/resource')->getTableName('sales/order_address')),
                        'cond' => 'main_table.order_id = billing_address.parent_id AND billing_address.address_type="billing"',
                        'cols' => array('billing_country_id' => 'billing_address.country_id')
                    ),
                    'type' => 'text',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Country',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Country'
                ),
                'customer_group_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Customer Group'),
                    'id' => 'customer_group_id',
                    'index' => 'customer_group_id',
                    'filter_index' => 'order.customer_group_id',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.order_id = order.entity_id',
                        'cols' => array('customer_group_id' => 'order.customer_group_id')
                    ),
                    'type' => 'options',
                    'options' => Mage::getResourceModel('customer/group_collection')
                        ->load()
                        ->toOptionHash(),
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select'
                ),
                'customer_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Customer ID'),
                    'id' => 'customer_id',
                    'index' => 'customer_id',
                    'filter_index' => 'order.customer_id',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.order_id = order.entity_id',
                        'cols' => array('customer_id' => 'order.customer_id')
                    ),
                    'type' => 'text'
                ),
                'payment_method' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Payment Method'),
                    'id' => 'payment_method',
                    'index' => 'payment_method',
                    'filter_index' => 'payment.method',
                    'join_left' => array(
                        'name' => array('payment' => Mage::getSingleton('core/resource')->getTableName('sales/order_payment')),
                        'cond' => 'main_table.order_id = payment.parent_id',
                        'cols' => array('payment_method' => 'payment.method')
                    ),
                    'type' => 'options',
                    'options' => Mage::helper('xtcore/payment')->getPaymentMethodList(true, false, false, null),
                    'filter' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Filter_PaymentMethod',
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_PaymentMethod',
                    'change_renderer' => false,
                    'change_filter' => false
                ),
                'purchased_items' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Invoiced Items'),
                    'id' => 'purchased_items',
                    'index' => 'purchased_items',
                    'filter_index' => 'invoice_item.sku', // item_filter
                    'change_filter' => false,
                    'change_renderer' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Items',
                    'is_order_column' => false
                ),
                'base_shipping_amount' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping Amount'),
                    'id' => 'base_shipping_amount',
                    'index' => 'base_shipping_amount',
                    'filter_index' => 'invoice.base_shipping_amount',
                    'join_left' => array(
                        'name' => array('invoice' => Mage::getSingleton('core/resource')->getTableName('sales/invoice')),
                        'cond' => 'main_table.entity_id = invoice.entity_id',
                        'cols' => array('base_shipping_amount' => 'invoice.base_shipping_amount')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_shipping_incl_tax' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipping (Incl. Tax)'),
                    'id' => 'base_shipping_incl_tax',
                    'index' => 'base_shipping_incl_tax',
                    'filter_index' => 'invoice.base_shipping_incl_tax',
                    'join_left' => array(
                        'name' => array('invoice' => Mage::getSingleton('core/resource')->getTableName('sales/invoice')),
                        'cond' => 'main_table.entity_id = invoice.entity_id',
                        'cols' => array('base_shipping_incl_tax' => 'invoice.base_shipping_incl_tax')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_subtotal' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Subtotal'),
                    'id' => 'base_subtotal',
                    'index' => 'base_subtotal',
                    'filter_index' => 'invoice.base_subtotal',
                    'join_left' => array(
                        'name' => array('invoice' => Mage::getSingleton('core/resource')->getTableName('sales/invoice')),
                        'cond' => 'main_table.entity_id = invoice.entity_id',
                        'cols' => array('base_subtotal' => 'invoice.base_subtotal')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_tax_amount' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Tax Amount'),
                    'id' => 'base_tax_amount',
                    'index' => 'base_tax_amount',
                    'filter_index' => 'invoice.base_tax_amount',
                    'join_left' => array(
                        'name' => array('invoice' => Mage::getSingleton('core/resource')->getTableName('sales/invoice')),
                        'cond' => 'main_table.entity_id = invoice.entity_id',
                        'cols' => array('base_tax_amount' => 'invoice.base_tax_amount')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_discount_amount' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Discount Amount'),
                    'id' => 'base_discount_amount',
                    'index' => 'base_discount_amount',
                    'filter_index' => 'invoice.base_discount_amount',
                    'join_left' => array(
                        'name' => array('invoice' => Mage::getSingleton('core/resource')->getTableName('sales/invoice')),
                        'cond' => 'main_table.entity_id = invoice.entity_id',
                        'cols' => array('base_discount_amount' => 'invoice.base_discount_amount')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
                'base_grand_total_excl' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Grand Total (Excl. Tax)'),
                    'id' => 'base_grand_total_excl',
                    'index' => 'base_grand_total_excl',
                    'filter_index' => 'invoice.base_grand_total_excl',
                    'join_left' => array(
                        'name' => array('invoice' => Mage::getSingleton('core/resource')->getTableName('sales/invoice')),
                        'cond' => 'main_table.entity_id = invoice.entity_id',
                        'cols' => array('base_grand_total_excl' => '(invoice.base_grand_total - invoice.base_tax_amount)')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
            ),
            Xtento_EnhancedGrid_Model_Grid::GRID_SALES_SHIPMENT => array(
                'tracking_numbers' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Tracking Numbers'),
                    'id' => 'tracking_numbers',
                    'index' => 'tracking_numbers',
                    'change_filter' => false,
                    'change_renderer' => false,
                    'type' => 'text',
                    'width' => 170,
                    'sortable' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Trackingnumber',
                    'filter' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Trackingnumber'
                ),
                'tracking_table' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Carrier / Tracking'),
                    'id' => 'tracking_table',
                    'index' => 'tracking_table',
                    'change_filter' => false,
                    'change_renderer' => false,
                    'type' => 'text',
                    'width' => 190,
                    'sortable' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Trackingtable',
                    'filter' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Trackingtable'
                ),
                'purchased_items' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Shipped Items'),
                    'id' => 'purchased_items',
                    'index' => 'purchased_items',
                    'filter_index' => 'shipment_item.sku', // item_filter
                    'change_filter' => false,
                    'change_renderer' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Items',
                    'is_order_column' => false
                ),
                'base_grand_total' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Order Grand Total'),
                    'id' => 'base_grand_total',
                    'index' => 'base_grand_total',
                    'filter_index' => 'order.base_grand_total',
                    'join_left' => array(
                        'name' => array('order' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
                        'cond' => 'main_table.order_id = order.entity_id',
                        'cols' => array('base_grand_total' => 'order.base_grand_total')
                    ),
                    'type' => 'number',
                    'currency' => 'base_currency_code',
                    'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range'
                ),
            ),
            Xtento_EnhancedGrid_Model_Grid::GRID_SALES_CREDITMEMO => array(
                'invoice_increment_id' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Invoice #'),
                    'id' => 'invoice_increment_id',
                    'index' => 'invoice_increment_id',
                    'filter_index' => 'invoice.increment_id',
                    'join_left' => array(
                        'name' => array('invoice' => Mage::getSingleton('core/resource')->getTableName('sales/invoice')),
                        'cond' => 'main_table.order_id = invoice.order_id',
                        'cols' => array('invoice_increment_id' => new Zend_Db_Expr('group_concat(invoice.increment_id SEPARATOR ", ")'))
                    ),
                    'type' => 'text'
                ),
            ),
        );

        $thirdPartyColumns = Mage::getModel('xtento_enhancedgrid/columns_thirdparty_columns')->getCustomColumns();
        if (!empty($thirdPartyColumns)) {
            $customColumns = array_merge_recursive($customColumns, $thirdPartyColumns);
        }

        // Get customer custom columns, if available
        if (@Mage::getModel('xtento_enhancedgrid/columns_customer_columns')) {
            $customerColumns = Mage::getModel('xtento_enhancedgrid/columns_customer_columns')->getCustomColumns();
            if (!empty($customerColumns)) {
                $customColumns = array_merge_recursive($customColumns, $customerColumns);
            }
        }

        // Apply default values
        foreach ($customColumns as $grid => $gridColumns) {
            $counter = 0;
            foreach ($gridColumns as $columnIndex => $columnData) {
                $counter++;
                $customColumns[$grid][$columnIndex]['is_system'] = 0;
                $customColumns[$grid][$columnIndex]['is_visible'] = 0;
                $customColumns[$grid][$columnIndex]['sort_order'] = 240 + ($counter * 10);
                $customColumns[$grid][$columnIndex]['origin'] = 'custom';
                if (!array_key_exists('type', $customColumns[$grid][$columnIndex])) {
                    $customColumns[$grid][$columnIndex]['type'] = '';
                }
                if (!array_key_exists('renderer', $customColumns[$grid][$columnIndex])) {
                    $customColumns[$grid][$columnIndex]['renderer'] = '';
                }
                if (!array_key_exists('filter', $customColumns[$grid][$columnIndex])) {
                    $customColumns[$grid][$columnIndex]['filter'] = '';
                }
                if (!array_key_exists('width', $customColumns[$grid][$columnIndex])) {
                    $customColumns[$grid][$columnIndex]['width'] = '';
                }
                if (!array_key_exists('align', $customColumns[$grid][$columnIndex])) {
                    $customColumns[$grid][$columnIndex]['align'] = 'left';
                }
            }
        }

        if (!$specificGrid) {
            return $customColumns;
        } else {
            return $customColumns[$specificGrid];
        }
    }

    public function getOptions($field)
    {
        if ($field == 'payment_method') {
        }
    }

    public function getCustomColumn($grid, $columnIndex)
    {
        $customColumns = $this->getCustomColumns();
        if (array_key_exists($grid, $customColumns)) {
            if (array_key_exists($columnIndex, $customColumns[$grid])) {
                return $customColumns[$grid][$columnIndex];
            }
        }
        return false;
    }

}