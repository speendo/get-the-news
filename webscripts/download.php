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

## check passphrase (as some Android-devices have troubles with downloading
## files locked with HTTP-authentication, only passphrase authentication is
## switched on for this file. When an encryptionKey is specified this is not
## really a security issue)

$path = decrypt($_GET['path'], $encryptionKey);
$mimeType = $_GET['mimeType'];

startDownload($path, $mimeType);

?>
