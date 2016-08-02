<?php

class Icube_Warehouse_Block_Adminhtml_Dcdashboard_Renderer_Tracknumbers extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		//return '<span style="color:red;">'.$value.'</span>';
		$link = $this->getUrl('*/sales_shipment/view',
            array(
                'shipment_id'=> $row->getShipmentId(),
            )
        );
		return '<a href="'.$link.'" title="'.$this->__('View Shipment').'" class="home-link">'.$value.'</a>';
	}
 
}
?>