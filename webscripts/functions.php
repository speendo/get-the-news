<?php
###########################################################################
### Helper Functions
###########################################################################

function str_lreplace($search, $replace, $subject) {
	$pos = strrpos($subject, $search);

	if($pos == false) {
		return $subject;
	} else {
		return substr_replace($subject, $replace, $pos, strlen($search));
	}
}

function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function sort_desc_by_mtime($file1, $file2) {
	return (filemtime($file2) - filemtime($file1));
}

function sort_asc_by_mtime($file1, $file2) {
	return (filemtime($file1) - filemtime($file2));
}

function encrypt($string, $encryptionKey) {
	if ($encryptionKey == "") {
		return $string;
	} else {
		$result = '';
		for($i=0; $i<strlen ($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($encryptionKey, ($i % strlen($encryptionKey))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}

		return base64_encode($result);
	}
}

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

function startDownload($path) {
	if(!file_exists($path)) {
		// File doesn't exist, output error
		exit('file not found');
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
}
?>
