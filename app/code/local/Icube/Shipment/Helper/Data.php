<?php
class Icube_Shipment_Helper_Data extends Mage_Core_Helper_Abstract {
    
    /**
     * update AWB Number
     *
     */
    public function updateAwbNumber($dir, $dirarchive)
    {
        $allFileCsv=array();
        $movedFileCsv=array();


        $files = scandir($dir);
        foreach ($files as $key => $file) 
        { 
            
            if (!in_array($file,array(".","..")) && !is_dir($dir.DS.$file) && substr($file, 0, 4) == "AWB_") 
            { 
                $allFileCsv[]=$file;
                $csv = new Varien_File_Csv();
                $csv->setDelimiter(';');
                $data = $csv->getData($dir.DS.$file);

                Mage::log('Begin Read : ' . $file, null, 'Icube_update_AWB_number.log');
                for($a=1; $a<count($data); $a++)
                {
                    $sap_so_number = $data[$a][0];
                    $awb_number = $data[$a][1];
                    $carrier_code = strtolower($data[$a][2]);
                    $track_title = $data[$a][3] ? $data[$a][3] : Mage::getStoreConfig('carriers/'.$carrier_code.'/method_name');
                    $invoice_status = $data[$a][4];

                    $invoice = Mage::getModel('sales/order_invoice')->load($sap_so_number, 'sap_so_number');
                    if(!$invoice->getId() || $invoice->getDeliveryPickup() != 'delivery' || $invoice->getInvoiceStatus() == $invoice_status || strtolower($invoice->getInvoiceStatus()) == 'delivered') continue;    // check for DC invoice only

                    $invoiceId = $invoice->getIncrementId();
                    echo "Invoice Number #".$invoiceId.", Shipping Status : ".$invoice_status.", SAP SO Number #".$sap_so_number." , AWB Number #".$awb_number.", Shipping Carrier Name : ".$carrier_code."<br>";


                    $order = $invoice->getOrder();

                    // check if the shipping record exists
                    $shipment = Mage::getModel('sales/order_shipment')->load($sap_so_number, 'sap_so_number');
                    if($shipment->getIncrementId()){
                        
                        if(strtolower($invoice_status) != 'delivered') {
                            // update shipping record
                            $trackmodel = Mage::getModel('sales/order_shipment_api')
                                           ->addTrack($shipment->getIncrementId(), $carrier_code, $track_title, $awb_number);
                        }

                        // send shipment email
                        $this->sendEmail($shipment, $invoice_status);

                        Mage::log('Update shipping record SAP SO Number #'.$sap_so_number.' , Shipment ID #'.$shipment->getIncrementId().', AWB number #'.$awb_number.', Shipping Carrier Name : '.$carrier_code, null, 'Icube_update_AWB_number.log');

                    } elseif ($order->canShip())
                    {
                        // get item list
                        foreach($invoice->getAllItems() as $item) 
                        {
                            $itemsarray[$item->getOrderItemId()] = $item->getQty();
                        }

                        $newShipment = Mage::getModel('sales/service_order', $order)->prepareShipment($itemsarray);
                        if($newShipment->getTotalQty()==0) continue;

                        if ($newShipment) {
                            
                            if(strtolower($invoice_status) != 'delivered') {
                                // add tracking number / AWB
                                $arrTracking = array(
                                     'carrier_code' => $carrier_code,
                                     'title' => $track_title,
                                     'number' => $awb_number,
                                     );
                                $track = Mage::getModel('sales/order_shipment_track')->addData($arrTracking);
                                $newShipment->addTrack($track);
                            }

                            $newShipment->register();

                            $newShipment->getOrder()->setIsInProcess(true);
                            $transactionSave = Mage::getModel('core/resource_transaction')
                                ->addObject($newShipment)
                                ->addObject($newShipment->getOrder())
                                ->save();

                            // send shipment email
                            $this->sendEmail($newShipment, $invoice_status);

                            Mage::log('Data : Order ID #'.$order->getIncrementId(). ', Invoice ID #'.$invoiceId.', SAP SO Number #'.$sap_so_number.' , Shipment ID #'.$newShipment->getIncrementId().', AWB number #'.$awb_number.', Shipping Carrier Name : '.$carrier_code, null, 'Icube_update_AWB_number.log');
                            
                            
                            $resource = Mage::getSingleton('core/resource');
                            $writeConnection = $resource->getConnection('core_write');
                            
                            // update 'sales flat shipment' SAP SO number
                            $query = "
                                    UPDATE `sales_flat_shipment` SET `sap_so_number` = '".$sap_so_number."' WHERE increment_id = '".$newShipment->getIncrementId()."';
                                    UPDATE `sales_flat_shipment_grid` SET `sap_so_number` = '".$sap_so_number."' WHERE increment_id = '".$newShipment->getIncrementId()."';
                                    ";
                            $writeConnection->query($query);

                            Mage::log($query, null, 'Icube_update_AWB_number.log');

                        }

                    }

                    $currentTimestamp = Mage::getModel('core/date')->timestamp(time()); 

                    $comment = 'Shipment update to '.$invoice_status.' '.date('d-m-Y h:i:s', $currentTimestamp).' via '.$file;
                    $invoice->setInvoiceStatus($invoice_status);    // update invoice_status
                    $invoice->addComment(
                        $comment,
                        false,       //isset($data['is_customer_notified']),
                        false        //isset($data['is_visible_on_front'])
                    );
                    
                    $invoice->save();

                    Mage::log('Invoice comment : "' . $comment. '"', null, 'Icube_update_AWB_number.log');
                     
                 }

                 Mage::log('End of reading ' . $file, null, 'Icube_update_AWB_number.log');
                 
                 $movedFileCsv[]=$dirarchive.DS.$file;
                 rename($dir.DS.$file, $dirarchive.DS.$file);
             
            }
       }
       echo "<br>CSV files : <br> ";
       print_r($allFileCsv);
       echo "<br>Archive CSV files : <br> ";
       print_r($movedFileCsv);
    }


    /* 
    * Send email with shipment data to customer
    */
    public function sendEmail($shipment, $invoice_status){

            try {

                if(strtolower($invoice_status) == 'shipped') {
                    $shipment->sendEmail(true)
                        ->setEmailSent(true);

                    Mage::log('Send shipment new email', null, 'Icube_update_AWB_number.log');
                } elseif(strtolower($invoice_status) == 'delivered') {
                    $shipment->sendUpdateEmail(true, '');

                    Mage::log('Send update email', null, 'Icube_update_AWB_number.log');
                }
                
                $shipment->save();
                    
                $historyItem = Mage::getResourceModel('sales/order_status_history_collection')
                    ->getUnnotifiedForInstance($shipment, Mage_Sales_Model_Order_Shipment::HISTORY_ENTITY_NAME);
                if ($historyItem) {
                    $historyItem->setIsCustomerNotified(1);
                    $historyItem->save();
                }
                echo 'The shipment has been sent.<br/>';
            } catch (Mage_Core_Exception $e) {
                echo $e->getMessage().'<br/>';
            } catch (Exception $e) {
                echo 'Cannot send shipment information.<br/>';
            }

    }

}