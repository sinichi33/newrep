<?php
	
	// Get CSV File
	ini_set('auto_detect_line_endings',TRUE);
	$path = getenv('MAGENTO_ROOT').'/var/urapidflow/import/';
	$files = glob($path.'CP_*.csv');
	
	foreach($files as $row){
				
		$handle = fopen($row,'r');
		$array_sku = array();
		$index = 0; // Headers
		$delimiter = ",";	

		while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
			if($index > 0 && !empty($data[1])){ // start from second line 
				$array_sku[] = $data[1];
			}
			$index ++; 
		}
		
		$file = file_get_contents("config.cfg");
		$parts = explode('=', $file);

		$username=substr($parts[1], 0, strrpos($parts[1], PHP_EOL));
		$servername=substr($parts[3], 0, strrpos($parts[3], PHP_EOL));
		$password=substr($parts[2], 0, strrpos($parts[2], PHP_EOL));
		$dbname=substr($parts[4], 0, strrpos($parts[4], PHP_EOL));

		// Connect to ruparupa database
		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		
		// Update EAV Status
		foreach($array_sku as $sku){
			$sql = "update catalog_product_entity_int set value = 2 where attribute_id = 96 and entity_id = (
					select entity_id from catalog_product_entity where sku = '".$sku."'
				)";
			$result = $conn->query($sql);
		}

		// get filename
		$files_name = explode('/',$row);
		$last_index = count($files_name) - 1;
		
		// move to archive
		rename($path.$files_name[$last_index],$path.'archive/'.$files_name[$last_index]);
		
	}

	ini_set('auto_detect_line_endings',FALSE);
?>

