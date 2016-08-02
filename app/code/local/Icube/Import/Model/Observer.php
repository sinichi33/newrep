<?php 
class Icube_Import_Model_Observer 
{
	public function importproducts() {
      	Mage::log("import product started",null,"import.log");
      	$profileTitle = 'Import Product';
    	$model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');		
		$location_path = $model_unirgy->getBaseDir();
	 	if (!$location_path) {
	 		$location_path = Mage::getConfig()->getVarDir('urapidflow/import');
	 	}

		//check csv file inside each folder
		$i=0;
		foreach (glob($location_path.DS.'AH_*.csv') as $filepath) {
			$i++;
			try{
				$filename = str_replace($location_path.DS,"",$filepath);

				//get list of SKUs
				$csv = file_get_contents($filepath);
				$Data = str_getcsv($csv, "\n"); //parse the rows 
				foreach($Data as &$Row) 
				{
					$Row = str_getcsv($Row, ",");
					$skus[] = $Row[0];
				}
				$skus = array_slice($skus, 1);

				$model_unirgy->setFilename($filename);
				$model_unirgy->start()->save()->run();
				$this->enableMultiStock($skus);

				$result = "import data from ".$filename." completed. Run status: ".$model_unirgy->getRunStatus();
				$result .= ". Rows Success: ".$model_unirgy->getRowsSuccess().". Errors: ".$model_unirgy->getNumErrors();
				$result .= ". Warnings: ".$model_unirgy->getNumWarnings(); 
				Mage::log($result,null,"import.log");
			}
			catch (Exception $e) 
			{
				echo $e->getMessage();
			}
		}
		if($i==0) Mage::log("AH_*.csv not found inside folder ".$location_path,null,"import.log");
		 
    }

    public function importccp() {
      	Mage::log("import ccp started",null,"import.log");
      	$profileTitle = 'Import CCP';
    	$model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');		
		$location_path = $model_unirgy->getBaseDir();
	 	if (!$location_path) {
	 		$location_path = Mage::getConfig()->getVarDir('urapidflow/import');
	 	}

		//check csv file inside each folder
		$i=0;
		foreach (glob($location_path.DS.'CCP_*.csv') as $filepath) {
			$i++;
			try{
				$filename = str_replace($location_path.DS,"",$filepath);
				$model_unirgy->setFilename($filename);
				$model_unirgy->start()->save()->run();
				$result = "import data from ".$filename." completed. Run status: ".$model_unirgy->getRunStatus();
				$result .= ". Rows Success: ".$model_unirgy->getRowsSuccess().". Errors: ".$model_unirgy->getNumErrors();
				$result .= ". Warnings: ".$model_unirgy->getNumWarnings(); 
				Mage::log($result,null,"import.log");
			}
			catch (Exception $e) 
			{
				echo $e->getMessage();
			}
		}
		if($i==0) Mage::log("CCP_*.csv not found inside folder ".$location_path,null,"import.log");
		 
    }

    public function mainimage() {
      	Mage::log("import main image started",null,"import.log");
      	$profileTitle = 'Import Product';
    	$model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');		
		$location_path = $model_unirgy->getBaseDir();
	 	if (!$location_path) {
	 		$location_path = Mage::getConfig()->getVarDir('urapidflow/import');
	 	}

		//check csv file inside each folder
		$i=0;
		foreach (glob($location_path.DS.'EC_IMG_*.csv') as $filepath) {
			$i++;
			try{
				$filename = str_replace($location_path.DS,"",$filepath);
				$model_unirgy->setFilename($filename);
				$model_unirgy->start()->save()->run();
				$result = "import data from ".$filename." completed. Run status: ".$model_unirgy->getRunStatus();
				$result .= ". Rows Success: ".$model_unirgy->getRowsSuccess().". Errors: ".$model_unirgy->getNumErrors();
				$result .= ". Warnings: ".$model_unirgy->getNumWarnings(); 
				Mage::log($result,null,"import.log");
			}
			catch (Exception $e) 
			{
				echo $e->getMessage();
			}
		}
		if($i==0) Mage::log("EC_IMG_*.csv not found inside folder ".$location_path,null,"import.log");
		 
    }

    public function importcpi() {
      	Mage::log("import cpi started",null,"import.log");
      	$profileTitle = 'Import CPI';
    	$model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');		
		$location_path = $model_unirgy->getBaseDir();
	 	if (!$location_path) {
	 		$location_path = Mage::getConfig()->getVarDir('urapidflow/import');
	 	}

		//check csv file inside each folder
		$i=0;
		foreach (glob($location_path.DS.'IMG_UPLOAD_*.csv') as $filepath) {
			$i++;
			try{
				$filename = str_replace($location_path.DS,"",$filepath);
				$model_unirgy->setFilename($filename);
				$model_unirgy->start()->save()->run();
				$result = "import data from ".$filename." completed. Run status: ".$model_unirgy->getRunStatus();
				$result .= ". Rows Success: ".$model_unirgy->getRowsSuccess().". Errors: ".$model_unirgy->getNumErrors();
				$result .= ". Warnings: ".$model_unirgy->getNumWarnings(); 
				Mage::log($result,null,"import.log");
			}
			catch (Exception $e) 
			{
				echo $e->getMessage();
			}
		}
		if($i==0) Mage::log("IMG_UPLOAD_*.csv not found inside folder ".$location_path,null,"import.log");
		 
    }

    protected function enableMultiStock($skus){
    	foreach ($skus as $sku) {
    		$_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
    		if(!$_product){
    			Mage::log("Error: SKU ".$sku."not found. So, multistock can not be enabled for this sku.",null,"import.log");
    			continue;
    		}
    		$stock = Mage::getModel('advancedinventory/item')->loadByProductId($_product->getId());
	        $status = 1;
	        if($stock->getId() == null) {
	            try {
	                $product_id = $_product->getId();
	                $item = Mage::getModel('advancedinventory/item')->loadByProductId($product_id);

	                if ($status) {
	                    $item->setData(array("id" => $item->getId(), "product_id" => $product_id, "manage_local_stock" => $status))->save();
	                    foreach ($this->getWhPos() as $pos) {
	                        $this->setStockData($product_id, $pos["place_id"]);
	                    }
	                    $inventory = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product_id);
	                    $inventory->setQty(0)->save();
	                } else {
	                    $item->delete();
	                }
	            } catch (Exception $e) {
	                return $e->getMessage();
	            }
	        }
    	}
	    return true;
        
    }

    public function getWhPos() {
        try {
            $array = array();
            foreach (Mage::getModel('pointofsale/pointofsale')->getCollection()as $warehouse) {
                $array[] = $warehouse->getData();
            }
            return $array;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function setStockData($product_id, $warehouse_id, $data = array("manage_stock" => 1, "quantity_in_stock" => 0, "backorder_allowed" => 0, "use_config_setting_for_backorders" => 1)) {
    	try {
            $stock = Mage::getModel('advancedinventory/stock')->getStockByProductIdAndPlaceId($product_id, $warehouse_id);

            $origin_qty = $stock->getQuantityInStock();
            $data["id"] = $stock->getId();
            $data["place_id"] = $warehouse_id;
            $data["product_id"] = $product_id;
            $data["localstock_id"] = Mage::getModel('advancedinventory/item')->loadByProductId($product_id)->getId();

            if ($stock->getQuantity_in_stock() != $data['quantity_in_stock'] || $stock->getUse_config_setting_for_backorders() != $data['use_config_setting_for_backorders'] || $stock->getManageStock() != $data['manage_stock'] || $stock->getBackorder_allowed() != $data['backorder_allowed']) {
                $stock->setData($data)->save();
            }

            $inventory = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product_id);
            $new_qty = $inventory->getQty() - $origin_qty + $data['quantity_in_stock'];

            $inventory->setQty($new_qty);
            $inventory->save();

            return true;
        } catch (Exception $e) {
        	return $e->getMessage();
        }
    }
}