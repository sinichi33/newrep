<?php

$file = file_get_contents("config.cfg");
$parts = explode('=', $file);

$username=substr($parts[1], 0, strrpos($parts[1], PHP_EOL));
$servername=substr($parts[3], 0, strrpos($parts[3], PHP_EOL));
$password=substr($parts[2], 0, strrpos($parts[2], PHP_EOL));
$dbname=substr($parts[4], 0, strrpos($parts[4], PHP_EOL));

//connect to ruparupa database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT
  a.increment_id,
  a.order_increment_id,
  a.store_code,
  d.pic_email
FROM
  sales_flat_invoice_grid a
JOIN sales_flat_order b ON b.entity_id = a.order_id
JOIN pointofsale c ON c.store_code = a.store_code
JOIN pickuppoint d ON d.pickup_code = c.pickup_location_code
WHERE
  b.state = 'processing'
AND b.status = 'processing'
AND a.invoice_status = 'PENDING'
AND a.delivery_pickup = 'pickup'
ORDER BY d.pic_email,a.increment_id;";
//echo $sql;

$result = $conn->query($sql);

ini_set('display_errors', '1');
error_reporting(E_ALL);

// Bootstrap Magento
require '../app/Mage.php';
Mage::app('admin', 'store');

//email sending
$date = date("d/m/Y");

try{
	if ($result->num_rows > 0) {
    	$bulk_arr = array();

	    while($row = $result->fetch_assoc()) {
			$increment_id = $row["increment_id"];
			$invoice = Mage::getModel('sales/order_invoice');
			$invoice = $invoice->loadByIncrementId($increment_id);
			$pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf(array($invoice));

			// generate semua filenya dulu
			file_put_contents("../../shared/media/bopis_email/$increment_id.pdf", $pdf->render(),FILE_BINARY);

			// kumpulin semua data ke array
			array_push($bulk_arr, array("email" => $row["pic_email"], "increment_id" =>$increment_id));
	    }

	    // invoice id group by email
	    $new_bulk_arr = array();
		foreach ($bulk_arr as $bulk) {
		  $email = $bulk['email'];
		  $new_bulk_arr[$email][] = $bulk['increment_id'];
		}

		// echo "<pre>";
	 	// print_r($new_bulk_arr);

		// array untuk mendelete file yg sudah tidak pending lagi
		$bulk_files = array();

		foreach ($new_bulk_arr as $key => $value) {
			$mailto = $key;

			$files = array();
		 	foreach ($new_bulk_arr[$key] as $value) {
		    	array_push($files, "/var/public/www.ruparupa.com/shared/media/bopis_email/$value.pdf");
		    	array_push($bulk_files, "$value.pdf");
		 	}

		 	// print_r($files);
		 	// echo "<br><br>";

		 	$message = "Terlampir Orderan Pending per Tanggal: $date";

		    $path = "/var/public/www.ruparupa.com/shared/media/bopis_email/";
		    $from_mail = "help@ruparupa.com";
		    $from_name = "Admin ruparupa.com";
		    $subject = "Daftar Orderan Pending";

		    $success = mail_attachment($files, $mailto, $from_mail, $from_name, $subject, $message);
		    if ($success) {
	            echo "mail sent to $mailto!";
		    } else {
		    	print_r(error_get_last());
	            echo "mail could not be sent!";
		    }
		}

		// delete file yang sudah tidak dipake
		$dir    = '../../shared/media/bopis_email';
		$files1 = scandir($dir);
		// print_r($files1);
		foreach ($files1 as $value) {
			if(!in_array($value, $bulk_files)){
				$path = "../../shared/media/bopis_email/$value";
				unlink($path);
			}
		}
  	}

  	$conn->close();
} catch (Exception $e) {
    die($e);
    $conn->close();
}

function mail_attachment($files, $mailto, $from_mail, $from_name, $subject, $message){
	$from = $from_mail;
	$headers = "From: $from_name <$from_mail>";

	// boundary
	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

	// headers for attachment
	$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

	// multipart boundary
	$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
	$message .= "--{$mime_boundary}\n";

	// preparing attachments
	for($x=0;$x<count($files);$x++){
		$file = fopen($files[$x],"rb");
		$data = fread($file,filesize($files[$x]));
		fclose($file);
		$data = chunk_split(base64_encode($data));

		$part = explode("/",$files[$x]);

		$message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
		"Content-Disposition: attachment;\n" . " filename=\"$part[7]\"\n" .
		"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
		$message .= "--{$mime_boundary}\n";
	}

	return @mail($mailto, $subject, $message, $headers);
}