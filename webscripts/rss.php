<?php
###########################################################################
### get-the-news (v0.9.2) 
###
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
###
### Acknowledgment
### ==============
### * RSS Feed Folder (http://www.rssfeedfolder.com/)
###    A lot of this script was inspired by their work.
###########################################################################

include("functions.php");

###########################################################################
### General Settings
###########################################################################
$folderPath = __DIR__ . "/";
$folderURL  = dirname(curPageURL()) . "/";
$rssDate    = date("r", time());
@$feed      = $_GET['feed'];
@$extension = $_GET['ext'];
@$short     = $_GET['short'];
@$pass      = $_GET['pass'];
$dc         = "http://purl.org/dc/elements/1.1/";

###########################################################################
### Computed Variables
###########################################################################
## folderPath
if ($newspaperFolder != "") {
	$folderPath = rtrim($newspaperFolder, "/") . "/";
}

## other Variables
if ($feed == "") {
	$title = "RSS-Feed";
	$feed = "*";
	$contentFolderPath = $folderPath . $feed . "/";
	$pathLength = strlen($contentFolderPath) - 2;
} else {
	$title = ucwords($feed);
	$contentFolderPath = realpath($folderPath . $feed . "/") . "/";
	$pathLength = strlen($contentFolderPath);
	if (strpos($contentFolderPath, $folderPath) !== 0) {
		$title = "RSS-Feed";
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

$searchStatement       = $contentFolderPath . $extension;
$searchStatementOutput = "Search results for \"" . $feed . "/" . $extension . "\"";
## Mime Type (selected by extension - at the moment just epub)
$mimeType              = "application/epub+zip";

# Show a compact list - yes or no
$compactList = FALSE;
$trueValues = array("true", "t", "1");
if (in_array(strtolower($short), $trueValues)) {
	$compactList = TRUE;
}

## check passphrase
checkPassPhrase();

###########################################################################
### The items in the feed
###########################################################################
$lArticles = array();
$cArticles = 0;

$fileArray = glob($searchStatement);
usort($fileArray, "sort_desc_by_mtime");

foreach($fileArray as $strFile) {
	$cArticles++;

	## see if there is a corresponding .opf-file and open it
	$strOpfFile = str_lreplace(".epub", ".opf", $strFile);
	if (file_exists($strOpfFile)) {
		$opf = simplexml_load_file($strOpfFile);
	} else {
		$opf = false;
	}
	## test .opf and store values
	if($opf) {
		## Fallback
		## Article Title
		$strTitle       = substr($strFile, $pathLength);
		## Article Description
		$strDescription = "No description for " . substr($strFile, $pathLength);
		## Article Date (can be improved)
		$strPubDate     = date("r", filemtime($strFile));

		$opf_dc = $opf->metadata->children($dc);

		## Article Title
		$strTitle       = htmlspecialchars($opf_dc->title);
		## Article Description
		$strDescription = htmlspecialchars($opf_dc->description);
		## Article Date
		$strPubDate     = date("r", strtotime($opf_dc->date));
	} else {
		## Article Title
		$strTitle       = substr($strFile, $pathLength);
		## Article Description
		$strDescription = "No description for " . substr($strFile, $pathLength);
		## Article Date (can be improved)
		$strPubDate     = date("r", filemtime($strFile));
	}

	## Article Link
	$strLink = $folderURL . "download.php?path=" . urlencode(encrypt($strFile, $encryptionKey)) . "&amp;mimeType=" . urlencode($mimeType) . "&amp;pass=" . urlencode($pass);

	# The Feeds last update
	$rssDate = $strPubDate;
	###################################################################
	### Prepare the item info
	###################################################################
	$strArticle  = "";
	$strArticle .= "    <item>\r\n";
	$strArticle .= "      <title>$strTitle</title>\r\n";
	if (!$compactList) {
		$strArticle .= "      <description>$strDescription</description>\r\n";
	}
	$strArticle .= "      <link>$strLink</link>\r\n";
	$strArticle .= "      <enclosure url=\"$strLink\" length=\"" . filesize($strFile) . "\" type=\"" . $mimeType . "\"/>\r\n";
	$strArticle .= "      <guid isPermaLink=\"true\">$strLink</guid>\r\n";
	$strArticle .= "      <pubDate>$strPubDate</pubDate>\r\n";
	$strArticle .= "      <source url=\"$rssURL\">$rssCopyright</source>\r\n";
	$strArticle .= "    </item>\r\n";
	$lArticles[] = $strArticle;
}

$lastArticle = $cArticles - 1;
$firstArticle = 0;

if ($rssMaxItems > 0) {
	if ($lastArticle >= $rssMaxItems) {
		$firstArticle = $lastArticle - ($rssMaxItems - 1);
	}
}

###########################################################################
### Start the Feed
###########################################################################
header('Content-Type: application/xml');
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\r\n";
if ($styleSheet != "") {
	if (substr_compare($styleSheet, ".css", -4, 4, true) == 0) {
		echo "<?xml-stylesheet type=\"text/css\" href=\"$styleSheet\" ?>\r\n";
	} elseif (substr_compare($styleSheet, ".xsl", -4, 4, true) == 0){
		echo "<?xml-stylesheet type=\"text/xsl\" href=\"$styleSheet\" ?>\r\n";
	}
}
echo "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\r\n";
echo "  <channel>\r\n";

###########################################################################
### Feed owner details
###########################################################################
echo "    <title>$title</title>\r\n";
echo "    <description>$searchStatementOutput</description>\r\n";
echo "    <link>$rssURL</link>\r\n";
echo "    <language>$rssLanguage</language>\r\n";
echo "    <copyright>$rssCopyright</copyright>\r\n";
echo "    <pubDate>$rssDate</pubDate>\r\n";
echo "    <lastBuildDate>$rssDate</lastBuildDate>\r\n";
echo "    <generator>$rssGenerator</generator>\r\n";
echo "    <ttl>$rssTtl</ttl>\r\n";
echo "    <atom:link href=\"" . $folderURL . "rss.php\" rel=\"self\" type=\"application/rss+xml\" />\r\n";
if ($rssLogo != "") {
	echo "    <image>\r\n";
	echo "      <url>$rssLogo</url>\r\n";
	echo "      <title>$title</title>\r\n";
	echo "      <link>$rssURL</link>\r\n";
	echo "    </image>\r\n";
}
if ($rssIcon != "") {
	echo "    <icon>$rssIcon</icon>\r\n";
}

###########################################################################
### The feed's heart ;-)
###########################################################################
for ($iArticle = $firstArticle; $iArticle <= $lastArticle; $iArticle++) {
	echo $lArticles[$iArticle];
}

###########################################################################
### Finish the Feed
###########################################################################
echo "  </channel>\r\n";
echo "</rss>\r\n";
?>

