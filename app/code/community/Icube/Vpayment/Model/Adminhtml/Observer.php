<?php
class Icube_Vpayment_Model_Adminhtml_Observer
{
    public function addColumnToResource(Varien_Event_Observer $observer) {
        $block = $observer->getEvent()->getBlock();
        $this->_block = $block;


        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid) {
            $statusOptions = array();
            $statusOptions['new'] = 'New';
            $statusOptions['captured'] = 'Captured';

            $payments = Mage::getSingleton('payment/config')->getActiveMethods();
            $methods = array();
            foreach ($payments as $paymentCode=>$paymentModel)
            {
                $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
                $methods[$paymentCode] = Mage::helper('vpayment')->__($paymentTitle);
            }

            $block->addColumnAfter('payment_method', array(
                'header' => Mage::helper('vpayment')->__('Payment Method'),
                'index' => 'payment_method',
                'filter_index' => 'op.method',
                'type'  => 'options',
                'width' => '70px',
                'options' => $methods,
                'sortable' => false,
            ), 'status');

            $block->addColumnAfter('veritrans_id', array(
                'header' => Mage::helper('vpayment')->__('VT-Direct Order #'),
                'index' => 'veritrans_id',
                'type'  => 'text',
                'sortable' => false,
            ), 'payment_method');
            $block->addColumnAfter('vtstatus', array(
                'header' => Mage::helper('vpayment')->__('VT-Direct Status'),
                'index' => 'vtstatus',
                'type' => 'options',
                'options' => $statusOptions,
                'sortable' => false,
            ), 'veritrans_id');

        }
    }

    public function prepareCollection($observer) {
        $collection = $observer->getOrderGridCollection();
        if ($collection instanceof Mage_Sales_Model_Resource_Order_Collection) {
            $collection->getSelect()->joinLeft(
                array('vt' => 'veritrans'),
                'main_table.increment_id=vt.order_id',
                array('veritrans_id'=>'veritrans_id','vtstatus'=>'vt.vtstatus')
            )
            ->joinLeft(
                array('op'=>'sales_flat_order_payment'), 
                'main_table.entity_id = op.parent_id',
                array('payment_method' => 'op.method')
            );
            $collection->addFilterToMap('status', 'main_table.status');
            $collection->addFilterToMap('vtstatus', 'vt.vtstatus');
            $collection->addFilterToMap('veritrans_id', 'vt.veritrans_id');
            $collection->addFilterToMap('payment_method', 'op.method');

            parse_str(urldecode(base64_decode(Mage::app()->getRequest()->getParam('filter'))), $data);
            if (isset($data["vtstatus"])) {
                $collection->addFieldToFilter('vtstatus', array('eq' => $data["vtstatus"]));
            }
            $params = Mage::app()->getRequest()->getParams();
            if (isset($data["veritrans_id"])) {
                $collection->addFieldToFilter('veritrans_id', array('eq' => $data["veritrans_id"]));
            }
            if (isset($data["payment_method"])) {
                $collection->addFieldToFilter('payment_method', array('eq' => $data["payment_method"]));
            }

            if (isset($params["sort"]) && $params["sort"] == 'vtstatus') {
                if(isset($params["dir"])) {
                    $collection->setOrder('vtstatus',$params["dir"]);
                } else {
                    $collection->setOrder('vtstatus');
                }
            }

            return $collection;
        }
    }
}
?>