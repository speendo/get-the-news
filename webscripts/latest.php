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

include("functions.php");

## start php-session
startSessionNoCookies();
$_SESSION['startTime'] = time();

###########################################################################
### General Settings
###########################################################################
$rssDate      = date("r", time());
@$feed        = $_GET['feed'];
@$extension   = $_GET['ext'];
if ($phraseOn) {
	@$pass = $_GET['pass'];
}

###########################################################################
### Computed Variables
###########################################################################

## folderPath
$folderPath = rtrim($newspaperFolder, "/") . "/";

## other Variables
if ($feed == "") {
	$feed = "*";
	$contentFolderPath = $folderPath . $feed . "/";
	$pathLength = strlen($contentFolderPath) - 2;
} else {
	$contentFolderPath = realpath($folderPath . $feed . "/") . "/";
	$pathLength = strlen($contentFolderPath);
	if (strpos($contentFolderPath, $folderPath) !== 0) {
		$feed = "*";
		$contentFolderPath = $folderPath . $feed . "/";
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

## as this script is providing .epubs only, we set this mime type
$mimeType = "application/epub+zip"; // because it's an .epub in this case

## authenticate
authenticate();

###########################################################################
### The items in the feed
###########################################################################
$fileArray = glob($searchStatement);
usort($fileArray, "sort_desc_by_mtime");

$newestFile = array_slice($fileArray, 0, 1);
$newestFile = $newestFile[0];

## start download
$strLink = "";

$strFileName = replaceFirst($newestFile, $folderPath, "");
$strLink = $folderURL . "download.php?path=" . urlencode(encrypt($strFileName, $encryptionKey)) . "&mimeType=" . urlencode($mimeType) . "&" . session_name() . "=" . session_id();

header("Location: $strLink");

?>
