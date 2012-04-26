<?php
###########################################################################
### User Settings
###########################################################################
$encryptionKey  = "bla";

###########################################################################
### General Settings
###########################################################################
$folderPath = __DIR__ . "/";
$folderURL  = curRootURL() . "/";
$rssDate    = date("r", time());
@$feed      = $_GET['feed'];
@$extension = $_GET['ext'];

###########################################################################
### Computed Variables
###########################################################################

if ($feed == "") {
	$feed = "*";
	$contentFolderPath = $folderPath . $feed . "/";
	$contentFolderURL  = $folderURL;
	$pathLength = strlen($contentFolderPath) - 2;
} else {
	$contentFolderPath = realpath($folderPath . $feed . "/") . "/";
	$contentFolderURL  = $folderURL . $feed . "/";
	$pathLength = strlen($contentFolderPath);
	if (strpos($contentFolderPath, $folderPath) !== 0) {
		$feed = "*";
		$contentFolderPath = $folderPath . $feed . "/";
		$contentFolderURL  = $folderURL;
		$pathLength = strlen($contentFolderPath) - 2;
	}
}

if ($extension == "") {
	$extension = "*.epub";
} else {
	$extension = preg_replace("[^A-Za-z0-9\*]", "", $extension);
	$extension = "*.$extension";
}

$searchStatement = $contentFolderPath . $extension;

###########################################################################
### The items in the feed
###########################################################################
$lArticles = array();
$cArticles = 0;

$fileArray = glob($searchStatement);
usort($fileArray, "sort_desc_by_mtime");

$newestFile = array_slice($fileArray, 0, 1);
$newestFile = $newestFile[0];

## Link
$link = $folderURL . "download.php?path=" . urlencode(encrypt($newestFile, $encryptionKey)) . "&amp;mimeType=" . urlencode($mimeType);

header("Location: $link");
###########################################################################
### Helper Functions
###########################################################################
function curRootURL() {
	$rootURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$rootURL .= "s";
	}
	$rootURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$rootURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
 		$rootURL .= $_SERVER["SERVER_NAME"];
	}
	return $rootURL;
}

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
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function sort_desc_by_mtime($file1, $file2) {
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
?>
