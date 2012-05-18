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

## RSS-Language (in the feeds head, "en" for english)
$rssLanguage    = "";
## RSS-Copyright (you can enter your name here)
$rssCopyright   = "";
## RSS-Generator (change if desired)
$rssGenerator   = "get-the-news";
## RSS-Time-to-live (interval to refresh feed)
$rssTtl         = "180";
## Path to RSS-logo (png, gif, jpg, etc. you could also leave this empty)
$rssLogo        = "";
## Path to RSS-icon (ico, jpg, etc. you could also leave this empty)
$rssIcon        = "";
## RSS-URL
$rssURL         = "";
## Max items in feed (0 for no limit)
$rssMaxItems    = 200;
## Style sheet (not working yet)
$styleSheet     = "";
## Encryption key for download-links
## (you could leave this empty, but you shouldn't!)
$encryptionKey  = "encryptionKey";

#### If you use get-the-news to download newspapers (which you probably
#### want to do), you might get problems with the content providers,
#### namely the newspaper-owners, if you provide the newspapers public on
#### the web. The following settings give you the possibility to keep your
#### newspapers private. For this we use a pass phrase for the php-scripts.
#### Additionally, you have to provide the full path of your
#### newspaper-folder.
####
#### If you still want to provide the news public, just leave these
#### settings empty.

## absolute path to newspaper-folder
$newspaperFolder  = "";
## Pass phrase for php-Scripts (leave empty if they should be public)
$passPhrase       = "";

###########################################################################
### End
###########################################################################
?>
