<?php
 
class Icube_Warehouse_Block_Adminhtml_Dcdashboard_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('dc_order_grid');
        $this->setDefaultSort('increment_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }


    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_invoice_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass())
            ->addAttributeToFilter('delivery_pickup', 'delivery')
            ->addAttributeToFilter('store_code', 'DC')
            ->addAttributeToFilter('o.status',  array('neq' => 'canceled'))
            ->addExpressionFieldToSelect(
                'customer_name',
                'CONCAT({{customer_firstname}}, \' \', {{customer_lastname}})',
                array('customer_firstname' => 'o.customer_firstname', 'customer_lastname' => 'o.customer_lastname'))
            ->addExpressionFieldToSelect(
                'track_numbers',
                '(SELECT GROUP_CONCAT(\' \', x.track_number)
                    FROM sales_flat_shipment_track x
                    WHERE {{shipment_id}} = x.parent_id)',
                array('shipment_id' => 's.entity_id')
            );
        $collection->getSelect()
            ->join(array('o' => 'sales_flat_order'), 'o.entity_id = main_table.order_id ', array( 'order_inc_id'    => 'o.increment_id', 'customer_email'    => 'o.customer_email' ))
            ->joinLeft(array('s' => 'sales_flat_shipment'), 'o.entity_id = s.order_id AND main_table.sap_so_number = s.sap_so_number', array(
                'shipment_id' => 's.entity_id'
            ))
        ;
 
        $this->setCollection($collection);
        $this->addExportType('*/*/exportIcubeCsv', Mage::helper('icube_warehouse')->__('CSV'));
        parent::_prepareCollection();
        return $this;
    }
 
    protected function _prepareColumns()
    {
        $helper = Mage::helper('icube_warehouse');
        $currency = (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
 
        $this->addColumn('order_inc_id', array(
            'header' => $helper->__('Order #'),
            'width' => '100px',
            'type'  => 'text',
            'index'  => 'order_inc_id',
            'filter_index' => 'o.increment_id'
        ));
 
        $this->addColumn('increment_id', array(
            'header' => $helper->__('Invoice #'),
            'width' => '100px',
            'type'  => 'text',
            'index'  => 'increment_id',
            'filter_index'  => 'main_table.increment_id'
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('sales')->__('Invoice Date'),
            'index'     => 'created_at',
            'type'      => 'datetime',
            'filter_index'  => 'main_table.created_at'
        ));
 
        $this->addColumn('sap_so_number', array(
            'header' => $helper->__('SAP SO Number'),
            'index'  => 'sap_so_number',
            'filter_index'  => 'main_table.sap_so_number'
        ));
 
        $this->addColumn('invoice_status', array(
            'header' => $helper->__('Shipping Status'),
            'width' => '140px',
            'type'  => 'options',
            'index'  => 'invoice_status',
            'options' => $this->shippingStatusOptions(),
            'filter_index'  => 'main_table.invoice_status'
        ));

        $this->addColumn('customer_email', array(
            'header'       => $helper->__('Customer Email'),
            'index'        => 'customer_email',
            'filter_index' => 'o.customer_email'
        ));
 
        $this->addColumn('customer_name', array(
            'header'       => $helper->__('Customer Name'),
            'index'        => 'customer_name',
            'filter_condition_callback' => array($this, 'customerNameFilter'),
        ));

        $this->addColumn('track_numbers', array(
            'header'       => $helper->__('Tracking Number'),
            'width'        => '300px',
            'index'        => 'track_numbers',
            'type'      => 'text',
            'renderer'      =>  'Icube_Warehouse_Block_Adminhtml_Dcdashboard_Renderer_Tracknumbers',
            'filter_condition_callback' => array($this, 'trackNumbersFilter'),
        ));
 
        return parent::_prepareColumns();
    }

    // get shipping status options array
    public function shippingStatusOptions(){
        $shippingStatus = array(
                    'pending shipment' => Mage::helper('icube_warehouse')->__('PENDING SHIPMENT'),
                    'shipped' => Mage::helper('icube_warehouse')->__('SHIPPED'),
                    'delivered' => Mage::helper('icube_warehouse')->__('DELIVERED'),
                    'canceled' => Mage::helper('icube_warehouse')->__('CANCELED'),
                );

        return $shippingStatus;
    }

    public function customerNameFilter($collection, $column){
        $filterValue = $column->getFilter()->getValue();
        if(!is_null($filterValue)){
           $filterValue = trim($filterValue);
           $filterValue = preg_replace('/[\s]+/', ' ', $filterValue);

           $whereArr = array();
           $whereArr[] = $collection->getConnection()->quoteInto("customer_firstname LIKE '%".$filterValue."%'");
           $whereArr[] = $collection->getConnection()->quoteInto("customer_lastname LIKE '%".$filterValue."%'");
           $whereArr[] = $collection->getConnection()->quoteInto("CONCAT(customer_firstname, ' ', customer_lastname) LIKE '%".$filterValue."%'");
           $where = implode(' OR ', $whereArr);
           $collection->getSelect()->where($where);
        }
    }

    public function trackNumbersFilter($collection, $column){
        $filterValue = $column->getFilter()->getValue();
        if(!is_null($filterValue)){
           $filterValue = trim($filterValue);
           $filterValue = preg_replace('/[\s]+/', ' ', $filterValue);

           $whereArr = array();
           $whereArr[] = $collection->getConnection()->quoteInto("(SELECT GROUP_CONCAT(' ', x.track_number)
                    FROM sales_flat_shipment_track x
                    WHERE s.entity_id  = x.parent_id) LIKE '%".$filterValue."%'");
           $where = implode(' OR ', $whereArr);
           $collection->getSelect()->where($where);
        }
    }

    public function getRowUrl($row)
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/invoice')) {
            return false;
        }

        return $this->getUrl('*/sales_invoice/view',
            array(
                'invoice_id'=> $row->getId(),
                'dcdashboard'=> true
            )
        );
    }
 
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}