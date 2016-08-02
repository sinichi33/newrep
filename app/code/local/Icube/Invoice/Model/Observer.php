<?php 
class Icube_Invoice_Model_Observer 
{

    public function soNumber() 
    {
		$basedir = Mage::getStoreConfig("icube_invoice/invoice_csv/dir_csv");
		$basedirarchive = Mage::getStoreConfig("icube_invoice/invoice_csv/dir_archive");
		$dir = Mage::getBaseDir('var').DS.$basedir;
		$dirarchive = Mage::getBaseDir('var').DS.$basedirarchive;
		
		$allFileCsv=array();
		$movedFileCsv=array();
		echo "Import SAP Order Number<br>";
		echo "<pre>";
		
		$files = scandir($dir);
		foreach ($files as $key => $file) 
	   { 
	      if (!in_array($file,array(".","..")) && !is_dir($dir.DS.$file) && substr($file, -strlen(".DS_Store")) != ".DS_Store") 
	      { 
		     $allFileCsv[]=$file;
		     $csv = new Varien_File_Csv();
		     $csv->setDelimiter(';');
			 $data = $csv->getData($dir.DS.$file);

			 Mage::log('Begin Read : ' . $file, null, 'SO_Number_Import.log');
		     for($a=1; $a<count($data); $a++)
		     {
			     $id = $data[$a][1];
				 $sap_so_number = $data[$a][2];

				 Mage::log('Data : Order ID #'.$data[$a][0]. ', Invoice ID #'.$id.', SAP SO #'.$sap_so_number, null, 'SO_Number_Import.log');
				 
				 echo "Invoice Number ".$id." ,SAP SO Number ".$sap_so_number.'<br>';
				 
				 $resource = Mage::getSingleton('core/resource');
				 $writeConnection = $resource->getConnection('core_write');
				 $table = "sales_flat_invoice_grid";
				 
				 $query = "UPDATE $table SET sap_so_number = '$sap_so_number' WHERE increment_id = '$id'";
				 $writeConnection->query($query);
				 
				 Mage::log($query, null, 'SO_Number_Import.log');

				 $table = "sales_flat_invoice";

				 $query = "UPDATE $table SET sap_so_number = '$sap_so_number' WHERE increment_id = '$id'";
				 $writeConnection->query($query);
				 
				 Mage::log($query, null, 'SO_Number_Import.log');
				 
		     }
		     Mage::log('End of reading ' . $file, null, 'SO_Number_Import.log');
		     
		     $movedFileCsv[]=$dirarchive.DS.$file;
			 rename($dir.DS.$file, $dirarchive.DS.$file);
		     
	      }
	   }
	   echo "<br>CSV files : <br> ";
	   print_r($allFileCsv);
	   echo "<br>Archive CSV files : <br> ";
	   print_r($movedFileCsv);
	}

	public function splitInvoiceTrigger(Varien_Event_Observer $observer){
		$order = $observer->getEvent()->getOrder();
		$paymentMtd = $order->getPayment()->getMethodInstance()->getCode();
		if($paymentMtd == 'free')
		{
			//Icube Update - call Model split invoice
			Mage::getModel('icube_invoice/service_split')->invoiceSplitOrder($order);
		}
	}
    
}