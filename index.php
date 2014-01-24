<?php
// $LastChangedBy: slavik $ $Rev: 208 $ $LastChangedDate: 2013-04-10 18:46:18 +0600 (Ср, 10 апр 2013) $
// $Id: index.php 208 2013-04-10 12:46:18Z slavik $
$zippath="/tmp/ssl";
if (!file_exists($zippath)) die("ERROR: temp directory not exist $zippath");
if (!is_writable($zippath)) die("ERROR: temp directory read only $zippath");

echo "
<html>
<head>
  <meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">
  <title>sslmanager index page</title>
</head>

<BODY><center>
<a href=\"makecsr.php\">make csr or self signed cert</a>
		<br>
		<a href=\"makep12.php\">make p12 from private key and cert</a></body></html>";
die(0);
?>