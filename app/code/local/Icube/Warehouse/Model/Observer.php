<?php

class Icube_Warehouse_Model_Observer
{

    /**
     * change URL in Back Buttons
     *
     */
    public function customButton($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $dcdashboard = Mage::app()->getRequest()->getParam('dcdashboard'); 
        
        // check if invoice view  come from icube_dcdashboard
        if ($dcdashboard && $block instanceof Mage_Adminhtml_Block_Sales_Order_Invoice_View) {
            
            $block->removeButton('back');
            $block->addButton(
                'back',
                array(
                    'label'     => Mage::helper('sales')->__('Back'),
                    'class'     => 'back',
                    'onclick'   => 'setLocation(\'' . $block->getUrl('adminhtml/dcdashboard/') . '\')',
                )
            );
        }
    }


}
