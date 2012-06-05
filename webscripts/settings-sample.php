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

###########################################################################
### User Settings
###########################################################################

## Title
$title            = "get-the-news";

## RSS-Language (in the feeds head, "en" for english)
$rssLanguage      = "";

## RSS-Copyright (you can enter your name here)
$rssCopyright     = "Marcel";

## RSS-Generator (change if desired)
$rssGenerator     = "get-the-news";

## RSS-Time-to-live (interval to refresh feed)
$rssTtl           = "180";

## Max items in feed (0 for no limit)
$rssMaxItems      = 0;

## Path to RSS-logo (png, gif, jpg, etc. you could also leave this empty)
$rssLogo          = "";

## Path to RSS-icon (ico, jpg, etc. you could also leave this empty)
$rssIcon          = "";

## RSS-URL
$rssURL           = "";

## absolute path to newspaper-folder
$newspaperFolder  = "";

## Style sheet (not working yet)
$styleSheet       = "";

## Security-Settings

## Encryption key for download-links
## (you could leave this empty, but you shouldn't!)
$encryptionKey  = "narf";

## Security method:
## "http"   - HTTP-authentication (best)
## "phrase" - Passphrase-authentication (not secure but better than nothing if
##            HTTP-authentication is not supported by your server)
## "both"   - HTTP-authentication and passphrase-authentication (to be honest,
##            you don't need both)
## "none"   - no security (you shouldn't do that)
$secMethod = "http";

## HTTP-authentication users and passwords (if you use HTTP-Authentication)
## fill in like this
## $users     = array(
##              "name1" => "password2",
##              "name2" => "password2"
##              );

$users     = array(
             
             );

## Passphrase (if you use passphrase-authentication)
$passPhrase       = "phrase";

###########################################################################
### End
###########################################################################

$httpOn = FALSE;
$phraseOn = FALSE;

switch ($secMethod) {
	case "http":
	$httpOn = TRUE;
	break;

	case "phrase":
	$httpOn = TRUE;
	break;

	case "both":
	$httpOn = TRUE;
	$phraseOn = TRUE;
	break;

	case "none":
	break;

	default:
	exit("No valid value for \$secMethod provided");
}

if ($httpOn == TRUE && !$users) {
	exit("No valid HTTP-users specified");
}

if ($phraseOn == TRUE && $passPhrase == "") {
	exit("No valid passphrase specified");
}

if ($newspaperFolder == "") {
	exit("\$newspaperFolder not specified");
}

?>
