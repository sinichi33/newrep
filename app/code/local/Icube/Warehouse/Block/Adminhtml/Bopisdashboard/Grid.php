<?php
 
class Icube_Warehouse_Block_Adminhtml_Bopisdashboard_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('bopis_order_grid');
        $this->setDefaultSort('increment_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('increment_id');
        $this->getMassactionBlock()->setFormFieldName('invoice_id');
   
        $this->getMassactionBlock()->addItem('receipt_id', array(
            'label'        => Mage::helper('icube_warehouse')->__('Set Receipt ID'),
            'url'          => $this->getUrl('*/*/setReceiptId'),
            'additional'   => array(
                'receipt_id'    => array(
                    'name'     => 'receipt_id',
                    'type'     => 'text',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('icube_warehouse')->__('Receipt ID')
                )
            )
        ));
        
        $this->getMassactionBlock()->addItem('ready_for_pickup', array(
        'label'=> Mage::helper('icube_warehouse')->__('Set Status to Ready for Pickup'),
        'url'  => $this->getUrl('*/*/setReadyForPickup')
        ));

        $this->getMassactionBlock()->addItem('picked_up', array(
        'label'=> Mage::helper('icube_warehouse')->__('Set Status to Picked Up by Customer'),
        'url'  => $this->getUrl('*/*/setPickedup')
        ));
        
        return $this;
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
        $admin_user_session = Mage::getSingleton('admin/session');
        $adminuserId = $admin_user_session->getUser()->getUserId();
        $role_data = Mage::getModel('admin/user')->load($adminuserId)->getRole()->getRoleName();

        $collection = Mage::getResourceModel($this->_getCollectionClass())
            ->addAttributeToFilter('delivery_pickup', 'pickup')
            ->addAttributeToFilter('o.status',  array('neq' => 'canceled'))
            ->addExpressionFieldToSelect(
                'customer_name',
                'CONCAT({{customer_firstname}}, \' \', {{customer_lastname}})',
                array('customer_firstname' => 'o.customer_firstname', 'customer_lastname' => 'o.customer_lastname'))
            ;

        if($role_data != 'Administrators') {
            $collection->addAttributeToFilter('pickup_location_code', $role_data);
        }

        $collection->getSelect()
            ->join(array('o' => 'sales_flat_order'), 'o.entity_id = main_table.order_id ', array( 'order_inc_id'    => 'o.increment_id', 'customer_email'    => 'o.customer_email' ))
        ;
        
        $this->setCollection($collection);
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

        $this->addColumn('store_code', array(
           'header' => $helper->__('Store Code'),
           'width' => '100px',
           'type'  => 'text',
           'index'  => 'store_code',
           'filter_index'  => 'main_table.store_code'
       ));

        $this->addColumn('receipt_id', array(
            'header' => $helper->__('Receipt ID'),
            'width' => '100px',
            'type'  => 'text',
            'index'  => 'receipt_id',
            'filter_index'  => 'main_table.receipt_id'
        ));
 
        return parent::_prepareColumns();
    }

    // get shipping status options array
    public function shippingStatusOptions(){
        $shippingStatus = array(
                    'PENDING' => Mage::helper('icube_warehouse')->__('PENDING'),
                    'READY FOR PICKUP' => Mage::helper('icube_warehouse')->__('READY FOR PICKUP'),
                    'PICKED UP BY CUSTOMER' => Mage::helper('icube_warehouse')->__('PICKED UP BY CUSTOMER'),
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

    // public function trackNumbersFilter($collection, $column){
    //     $filterValue = $column->getFilter()->getValue();
    //     if(!is_null($filterValue)){
    //        $filterValue = trim($filterValue);
    //        $filterValue = preg_replace('/[\s]+/', ' ', $filterValue);

    //        $whereArr = array();
    //        $whereArr[] = $collection->getConnection()->quoteInto("(SELECT GROUP_CONCAT(' ', x.track_number)
    //                 FROM sales_flat_shipment_track x
    //                 WHERE s.entity_id  = x.parent_id) LIKE '%".$filterValue."%'");
    //        $where = implode(' OR ', $whereArr);
    //        $collection->getSelect()->where($where);
    //     }
    // }

    public function getRowUrl($row)
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('sales/invoice')) {
            return false;
        }

        return $this->getUrl('*/sales_invoice/view',
            array(
                'invoice_id'=> $row->getId(),
                'bopisdashboard'=> true
            )
        );
    }
 
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}