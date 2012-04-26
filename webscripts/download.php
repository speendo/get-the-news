<?php

$encryptionKey = "bla";

$path = decrypt($_GET['path'], $encryptionKey);
$mimeType = $_GET['mimeType'];

if(!file_exists($path)) {
	// File doesn't exist, output error
	die('file not found');
} else {
	$size = filesize($path);
	$file = basename($path);

	// Set headers
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
	header("Content-Description: File Transfer");
	header("Content-Disposition: attachment; filename=\"$file\"");
	header("Content-Type: $mimeType");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $size");
	// Read the file from disk
	readfile($path);
}

exit();

function decrypt($string, $encryptionKey) {
	if ($encryptionKey == "") {
		return $string;
	} else {
		$result = '';
		$string = base64_decode($string);

		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($encryptionKey, ($i % strlen($encryptionKey))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}

	return $result;
	}
}
?>
