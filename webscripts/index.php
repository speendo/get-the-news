<?php
include("functions.php");

@$pass = $_GET['pass'];
## check passphrase
checkPassPhrase();

$theTitle = $title;
if ($rssCopyright != "") {
$theTitle .= " by $rssCopyright";
}

## folderPath
if ($newspaperFolder != "") {
	$folderPath = rtrim($newspaperFolder, "/") . "/";
}

$fileArray = glob($folderPath . "*");
$dirArray = array();
foreach($fileArray as $strFile) {
	if(is_dir($strFile)) {
		$dirArray[] = $strFile;
	}
}
usort($dirArray, "sort_desc_by_mtime");

$list = array();
foreach($dirArray as $curDir) {
	$curFeed = replaceFirst($curDir, $folderPath, "");
	$output = "
		      <li>$curFeed 
		<a href=\"latest.php?feed=$curFeed&pass=$pass\">[Download latest file]</a> 
		<a href=\"rss.php?feed=$curFeed&pass=$pass\">[RSS-Feed]</a>
		</li>
	";
	$list[] = $output;
}
$list[] = "
      <li>Show all files 
	<a href=\"rss.php?pass=$pass\">[RSS-Feed]</a>
	</li>";

echo("
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
<html>
  <head>
    <title>$theTitle</title>
  </head>
  <body>
    <h1>$theTitle</h1>
    <ul>
");
for ($i = 0; $i < sizeof($list); $i++) {
	echo $list[$i];
}
echo("
    </ul>
  </body>
</html>
");
?>
