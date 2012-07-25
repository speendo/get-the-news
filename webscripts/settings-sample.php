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

## Title (the installations title)
$title            = "Get the News";
## RSS-Language (in the feeds head, "en" for english)
$rssLanguage      = "";
## RSS-Copyright (you can enter your name here)
$rssCopyright     = "";
## RSS-Generator (change if desired)
$rssGenerator     = "get-the-news";
## RSS-Time-to-live (interval to refresh feed)
$rssTtl           = "180";
## Max items in feed (0 for no limit)
$rssMaxItems      = 0;
## Path to RSS-logo (png, gif, jpg, etc. you could also leave this empty)
$rssLogo        = "";
## Path to RSS-icon (ico, jpg, etc. you could also leave this empty)
$rssIcon          = "";
## RSS-URL (the URL the rss-feed should link to)
$rssURL           = "";
## absolute path to newspaper-folder
$newspaperFolder  = "";
## Style sheet (not working yet)
$styleSheet       = "";

## Security-Settings
## Encryption key for download-links
## (you could leave this empty, but you shouldn't!)
$encryptionKey  = "encryptionKey";

## Security method:
## "http"   - HTTP-authentication (best)
## "phrase" - Passphrase-authentication (not secure but better than nothing if
##            HTTP-authentication is not supported by your server)
## "both"   - HTTP-authentication and passphrase-authentication (to be honest,
##            you don't need both)
## "none"   - no security (you shouldn't do that)
$secMethod = "http";

## HTTP-authentication users and passwords (if you use HTTP-Authentication):
## enter users like this:
## "user1" => "pass1",
## "user2" => "pass2"
$users     = array(
             );

## Passphrase (if you use passphrase-authentication)
$passPhrase       = "phrase";

## Session Timeout in seconds(600 seconds should be alright)
## $sessionTimeout   = -1; => disable sessions
## Sessions are used to let download links expire after $sessionTimeout
## for security reasons - if a download link gets public somehow it is only
## usable for a limited amount of time
$sessionTimeout   = 600;

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

$sessionsOn = TRUE;
if ($sessionTimeout < 0) {
	$sessionsOn = FALSE;
}

?>
