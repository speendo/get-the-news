<?php
###########################################################################
### Copyright: (2012) Marcel Jira
###
### License: GPL-3
###
### This file is part of get-the-news.
###
### get-the-news is free software: you can redistribute it and/or modify
### it under the terms of the GNU General Public License as published by
### the Free Software Foundation, either version 3 of the License, or
### (at your option) any later version.
###
### This program is distributed in the hope that it will be useful,
### but WITHOUT ANY WARRANTY; without even the implied warranty of
### MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
### GNU General Public License for more details.
###
### You should have received a copy of the GNU General Public License
### along with this program.  If not, see <http://www.gnu.org/licenses/>.
###########################################################################


include("settings.php");

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
	header("Connection: Keep-Alive");
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
