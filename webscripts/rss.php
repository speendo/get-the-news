<?php
###########################################################################
### Newspaper Feed (v0.9.1) 
### by Marcel Jira (m2m@gmx.at)
###
### Acknowledgment
### ==============
### * RSS Feed Folder (http://www.rssfeedfolder.com/)
###    A lot of this script is based on (I avoid to say stolen from) RSS
###    Feed Folder
### * Fast Icon (http://www.fasticon.com/freeware/)
###    They provided the icon - I think it fits very well ;-) !DON'T FORGET TO CONTACT THEM BEFORE RELEASE!!!
###########################################################################

###########################################################################
### User Settings
###########################################################################
$rssLanguage    = "de";
$rssCopyright   = "Marcel";
$rssGenerator   = "ripped off rssFeedFolder.com";
$rssTtl         = "180";
#improve me
$rssLogo        = "http://marcel.suuf.cc/img/Newspaper_Feed.png";
$rssIcon        = "http://marcel.suuf.cc/img/Newspaper_Feed.ico";
$rssURL         = "http://suuf.cc/";
$rssMaxItems    = 200;
$styleSheet     = "http://www.petefreitag.com/rss/simple_style.css";
$encryptionKey  = "bla";

###########################################################################
### General Settings
###########################################################################
$folderPath = __DIR__ . "/";
$folderURL  = curRootURL() . "/";
$rssDate    = date("r", time());
@$feed      = $_GET['feed'];
@$extension = $_GET['ext'];
@$short     = $_GET['short'];
$dc         = "http://purl.org/dc/elements/1.1/";

###########################################################################
### Computed Variables
###########################################################################
if ($feed == "") {
	$title = "RSS-Feed";
	$feed = "*";
	$contentFolderPath = $folderPath . $feed . "/";
	$contentFolderURL  = $folderURL;
	$pathLength = strlen($contentFolderPath) - 2;
} else {
	$title = ucwords($feed);
	$contentFolderPath = realpath($folderPath . $feed . "/") . "/";
	$contentFolderURL  = $folderURL . $feed . "/";
	$pathLength = strlen($contentFolderPath);
	if (strpos($contentFolderPath, $folderPath) !== 0) {
		$title = "RSS-Feed";
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

$searchStatement       = $contentFolderPath . $extension;
$searchStatementOutput = "Search results for \"" . $folderURL . $feed . "/" . $extension . "\"";
## Mime Type (selected by extension - at the moment just epub)
$mimeType              = "application/epub+zip";

# Show a compact list - yes or no
$compactList = FALSE;
$trueValues = array("true", "t", "1");
if (in_array(strtolower($short), $trueValues)) {
	$compactList = TRUE;
}

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
	$strLink = $folderURL . "download.php?path=" . urlencode(encrypt($strFile, $encryptionKey)) . "&amp;mimeType=" . urlencode($mimeType);
	
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

if ($lastArticle >= $rssMaxItems) {
	$firstArticle = $lastArticle - ($rssMaxItems - 1);
}

###########################################################################
### Start the Feed
###########################################################################
header('Content-Type: application/xml');
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\r\n";
echo "<?xml-stylesheet type=\"text/css\" href=\"$styleSheet\" ?>\r\n";
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
echo "    <image>\r\n";
echo "      <url>$rssLogo</url>\r\n";
echo "      <title>$title</title>\r\n";
echo "      <link>$rssURL</link>\r\n";
echo "    </image>\r\n";

###########################################################################
### The feed's heart ;-)
###########################################################################
for ($iArticle = $lastArticle; $iArticle >= $firstArticle; $iArticle--) {
	echo $lArticles[$iArticle];
}

###########################################################################
### Finish the Feed
###########################################################################
echo "  </channel>\r\n";
echo "</rss>\r\n";

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

