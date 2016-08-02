<?php
class Icube_Invoice_Model_Api extends Mage_Api_Model_Resource_Abstract
{

    public function items($filters)
    {
        $collection = Mage::getModel('sales/invoice')->getCollection()
            ->addAttributeToSelect('*');

        if (is_array($filters)) {
            try {
                foreach ($filters as $field => $value) {
                    $collection->addFieldToFilter($field, $value);
                }
            } catch (Mage_Core_Exception $e) {
                $this->_fault('filters_invalid', $e->getMessage());
                // If we are adding filter on non-existent attribute
            }
        }

        $result = array();
        foreach ($collection as $invoice) {
            $result[] = $invoice->toArray();
        }

        return $result;
    }

    public function updatestatus($awbnumber,$status, $carrier_code)
    {
        $tracking = Mage::getModel('sales/order_shipment_track')->getCollection()
                    ->addFieldToFilter('track_number', $awbnumber)
                    ->addFieldToFilter('carrier_code', strtolower($carrier_code))
                    ->getFirstItem();

        $shipment = Mage::getModel('sales/order_shipment')->load($tracking->getParentId());
        if (!$shipment->getId()) {
            Mage::log('No Shipment Found! AWB Number : '.$awbnumber, null, '3pl-api.log');
            $this->_fault('not_exists');
            // No invoice found
            return false;
        }

        $invoice = Mage::getModel('sales/order_invoice')->load($shipment->getSapSoNumber(), 'sap_so_number');
        
        if (!$invoice->getId()) {
            Mage::log('No Invoice Found! AWB Number : '.$awbnumber.', SAP SO Number: '.$shipment->getSapSoNumber, null, '3pl-api.log');
            $this->_fault('not_exists');
            // No invoice found
            return false;
        }
        if (strtolower($invoice->getInvoiceStatus()) == strtolower($status) || ((strtolower($status) == 'shipped') && (strtolower($invoice->getInvoiceStatus()) == 'delivered'))) {
            Mage::log('Status is already: .'.$invoice->getInvoiceStatus().'. AWB Number : '.$awbnumber.', SAP SO Number: '.$shipment->getSapSoNumber, null, '3pl-api.log');
            $this->_fault('already_set');
            // No invoice found
            return false;
        }

        $invoice->setInvoiceStatus($status)->save();
        $date = Mage::getModel('core/date')->date('Y-m-d H:i:s');
        $shipment->setDeliveredDate($date)->save();
        $result = 'Invoice and Shipment Updated! AWB Number: '.$awbnumber.', SAP SO Number : '.$shipment->getSapSoNumber().', status: '.$status.', Delivered Date: '.$date;
        $this->sendEmail($shipment,$status);
        Mage::log($result, null, '3pl-api.log');
        return $result;
    }

    /* 
    * Send email with shipment data to customer
    */
    public function sendEmail($shipment, $status){
            try {
                $shipment->sendEmail(true,'Shipment status changed to : '.$status)
                    ->setEmailSent(true)
                    ->save();
                    
                $historyItem = Mage::getResourceModel('sales/order_status_history_collection')
                    ->getUnnotifiedForInstance($shipment, Mage_Sales_Model_Order_Shipment::HISTORY_ENTITY_NAME);
                if ($historyItem) {
                    $historyItem->setIsCustomerNotified(1);
                    $historyItem->save();
                }
                echo 'The shipment email has been sent.<br/>';
            } catch (Mage_Core_Exception $e) {
                echo $e->getMessage().'<br/>';
            } catch (Exception $e) {
                echo 'Cannot send shipment information email.<br/>';
            }
    }
}