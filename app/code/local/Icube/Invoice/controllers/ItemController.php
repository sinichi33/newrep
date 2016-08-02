<?php 
class Icube_Invoice_ItemController extends Mage_Core_Controller_Front_Action 
{
	public function orderAction()
	{
		$basedir = Mage::getStoreConfig("icube_invoice/flatorder/dir_csv");
		$basedirarchive = Mage::getStoreConfig("icube_invoice/flatorder/dir_archive");
		$dir = Mage::getBaseDir('var').DS.$basedir;
		$dirarchive = Mage::getBaseDir('var').DS.$basedirarchive;
		
		$allFileCsv=array();
		$movedFileCsv=array();
		echo "Import Order Flat Item Number<br>";
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

		     for($a=1; $a<count($data); $a++)
		     {
			     $id = $data[$a][0];
				 $sku = $data[$a][1];
				 $zcom = $data[$a][2];
				 $zdmm= $data[$a][3];
				 $zpur = $data[$a][4];
				 $zdot= $data[$a][5];
				 $zpu1 = $data[$a][6];
				 $zpu2 = $data[$a][7];
				 $zban = $data[$a][8];
				 
				 echo "Order Flat Item id ".$id." ,SKU ".$sap_so_number.'<br>';
				 
				 $resource = Mage::getSingleton('core/resource');
				 $writeConnection = $resource->getConnection('core_write');
				 $table = "sales_flat_order_item";
				 
				 $query = "UPDATE $table SET zcom = '$zcom',
				 							 zdmm = '$zdmm',
				 							 zpur = '$zpur',
				 							 zdot = '$zdot',
				 							 zpu1 = '$zpu1',
				 							 zpu2 = '$zpu2',
				 							 zban = '$zban'
				  		   WHERE order_id = '$id'";

				 $writeConnection->query($query);
				 
				 Mage::log($query, null, 'SO_Order_Flat_Item.log');
				 
		     }
		     
		     $movedFileCsv[]=$dirarchive.DS.$file;
			 rename($dir.DS.$file, $dirarchive.DS.$file);
		     
	      }
	   }
	   echo "<br>CSV files : <br> ";
	   print_r($allFileCsv);
	   echo "<br>Archive CSV files : <br> ";
	   print_r($movedFileCsv);
	}
}