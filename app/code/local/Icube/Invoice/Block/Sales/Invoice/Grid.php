<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Adminhtml sales orders grid
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Icube_Invoice_Block_Sales_Invoice_Grid extends Mage_Adminhtml_Block_Sales_Invoice_Grid
{

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('increment_id', array(
            'header'    => Mage::helper('sales')->__('Invoice #'),
            'index'     => 'increment_id',
            'type'      => 'text',
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('sales')->__('Invoice Date'),
            'index'     => 'created_at',
            'type'      => 'datetime',
        ));

        $this->addColumn('order_increment_id', array(
            'header'    => Mage::helper('sales')->__('Order #'),
            'index'     => 'order_increment_id',
            'type'      => 'text',
        ));

        $this->addColumn('order_created_at', array(
            'header'    => Mage::helper('sales')->__('Order Date'),
            'index'     => 'order_created_at',
            'type'      => 'datetime',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('state', array(
            'header'    => Mage::helper('sales')->__('Status'),
            'index'     => 'state',
            'type'      => 'options',
            'options'   => Mage::getModel('sales/order_invoice')->getStates(),
        ));

        $this->addColumn('grand_total', array(
            'header'    => Mage::helper('customer')->__('Amount'),
            'index'     => 'grand_total',
            'type'      => 'currency',
            'align'     => 'right',
            'currency'  => 'order_currency_code',
        ));

        $this->addColumn('sap_so_number', array(
            'header'    => Mage::helper('sales')->__('SAP SO Number'),
            'index'     => 'sap_so_number',
            'type'      => 'text',
            // 'fiter_index' => 'invoicemain.sap_so_number'
        ));

        $this->addColumn('pickup_voucher', array(
            'header'    => Mage::helper('sales')->__('Pickup Voucher'),
            'index'     => 'pickup_voucher',
            'type'      => 'text',
        ));

        $this->addColumn('handling_voucher', array(
            'header'    => Mage::helper('sales')->__('Handling Voucher'),
            'index'     => 'handling_voucher',
            'type'      => 'text',
        ));

        $this->addColumn('delivery_pickup', array(
            'header'    => Mage::helper('sales')->__('Shipping'),
            'index'     => 'delivery_pickup',
            'type'      => 'options',
            'options'   => Mage::helper('icube_invoice')->shippingOptions(),
        ));

        $this->addColumn('invoice_status', array(
            'header'    => Mage::helper('sales')->__('Shipping Status'),
            'index'     => 'invoice_status',
            'type'      => 'text',
        ));


        $this->addColumn('action',
            array(
                'header'    => Mage::helper('sales')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('sales')->__('View'),
                        'url'     => array('base'=>'*/sales_invoice/view'),
                        'field'   => 'invoice_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }
}
