<?php 
// $LastChangedBy: slavik $ $Rev: 310 $ $LastChangedDate: 2014-01-24 10:13:37 +0600 (Пт, 24 янв 2014) $
// $Id: makecsr.php 310 2014-01-24 04:13:37Z slavik $

//die("closed due to bugs");
$zippath="/tmp/ssl";
if (!file_exists($zippath)) die("temp directory not exist: $zippath");
if (!is_writable($zippath)) die("temp directory read only: $zippath");

if (isset($_POST['element_1'])) {
// форма заполнена, хуярим	
$keybit=(int)$_POST['element_7'];
$c=$_POST['element_6'];
$m=$_POST['element_5'];
$st=$_POST['element_4'];
$l=$_POST['element_3'];
$o=$_POST['element_2'];
$cn=$_POST['element_1'];
$iszip=(int)$_POST['element_8_1'];

$config = array('digest_alg' => 'sha1', 'private_key_type' => OPENSSL_KEYTYPE_RSA, 'private_key_bits' => $keybit);
//array('private_key_bits' => $keybit);
$dn = array(
		"countryName" => "$c",
		"stateOrProvinceName" => "$st",
		"localityName" => "$l",
		"organizationName" => "$o",
		"organizationalUnitName" => "Security",
		"commonName" => "$cn",
		"emailAddress" => "$m"
);
$_dn=print_r($dn,TRUE);
$_config=print_r($config,TRUE);


$privateKey="";
$csr_req="";
if (FALSE==$privKey = openssl_pkey_new($config))  die('failed to make key');
if (FALSE==$csr = openssl_csr_new($dn, $privKey)) die('failed to make csr');
if (!openssl_pkey_export($privKey, $privateKey, NULL)) die('Failed to retrieve private key.'."\n");
if (!openssl_csr_export($csr, $csr_req)) die('Failed to retrieve csr.'."\n");
#openssl_pkey_export_to_file($privKey,"/tmp/testkey.pem");

if (1==$iszip) {
	$zip = new ZipArchive();
	$filename = "$zippath/key_".time().".zip";
	if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
		die("cant open <$filename>\n");
	}
	$zip->addFromString("certinfo.txt", "$_dn \n$_config\n");
	$zip->addFromString("key_rsa_$keybit.pem", "$privateKey");
	$zip->addFromString("cert.csr", "$csr_req\n");
	$zip->addFromString("$cn", "$c\n");
	$zip->close();
	$simple = "csr_private".time().".zip";
	header('Content-Disposition: attachment; filename='.$simple);
	readfile($filename);
	die(0);
	
}

header("Content-Type: text/plain");
echo "I THINK YOU SHOULD SAVE THIS\n";
echo "\n$_dn\n$_config";
	echo "\n\n$csr_req\n\n$privateKey";
	die(0);
}
// форма не заполнена
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>make CSR request</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"view.css\" media=\"all\">
<script type=\"text/javascript\" src=\"view.js\"></script>

</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"top.png\" alt=\"\">
	<div id=\"form_container\">
	
		<h1><a>make CSR request</a></h1>
		<form id=\"form_574014\" class=\"appnitro\"  method=\"post\" action=\"makecsr.php\">
					<div class=\"form_description\">
			<h2>create certificate request (CSR)</h2>
			<p>provide required data, <b>Remember - no Russian or other fancy languages.</b></p>
		</div>						
			<ul >
			
					<li id=\"li_1\" >
		<label class=\"description\" for=\"element_1\">CN (Common Name)</label>
		<div>
			<input id=\"element_1\" name=\"element_1\" class=\"element text large\" type=\"text\" maxlength=\"255\" value=\"\"/> 
		</div><p class=\"guidelines\" id=\"guide_1\"><small>subject: provide purpose of this certificate, for example \"mr. smith secret\" or \"mybestporn.com\"</small></p> 
		</li>		<li id=\"li_2\" >
		<label class=\"description\" for=\"element_2\">O (organization) </label>
		<div>
			<input id=\"element_2\" name=\"element_2\" class=\"element text large\" type=\"text\" maxlength=\"255\" value=\"CUP\"/> 
		</div><p class=\"guidelines\" id=\"guide_2\"><small>provide name of organization, for example \"CIA\"</small></p> 
		</li>		<li id=\"li_3\" >
		<label class=\"description\" for=\"element_3\">L (city) </label>
		<div>
			<input id=\"element_3\" name=\"element_3\" class=\"element text large\" type=\"text\" maxlength=\"255\" value=\"MOSCOW\"/> 
		</div><p class=\"guidelines\" id=\"guide_3\"><small>provide location, for example \"MOSCOW\"</small></p> 
		</li>		<li id=\"li_4\" >
		<label class=\"description\" for=\"element_4\">ST (state) </label>
		<div>
			<input id=\"element_4\" name=\"element_4\" class=\"element text medium\" type=\"text\" maxlength=\"255\" value=\"MOSCOW\"/> 
		</div><p class=\"guidelines\" id=\"guide_4\"><small>for example \"Washington DC\", or \"EU\" if you are lazy</small></p> 
		</li>		<li id=\"li_5\" >
		<label class=\"description\" for=\"element_5\">emailAddress </label>
		<div>
			<input id=\"element_5\" name=\"element_5\" class=\"element text medium\" type=\"text\" maxlength=\"255\" value=\"support@controlpay.ru\"/> 
		</div><p class=\"guidelines\" id=\"guide_5\"><small>email, sometimes its important to write go@hell.fast to avoid spam</small></p> 
		</li>		<li id=\"li_6\" >
		<label class=\"description\" for=\"element_6\">C </label>
		<div>
			<input id=\"element_6\" name=\"element_6\" class=\"element text medium\" type=\"text\" maxlength=\"255\" value=\"RU\"/> 
		</div><p class=\"guidelines\" id=\"guide_6\"><small>country, two capital letters - for example \"RU\". do not provide trash.</small></p> 
		</li>		<li id=\"li_7\" >
		<label class=\"description\" for=\"element_7\">key </label>
		<div>
		<select class=\"element select large\" id=\"element_7\" name=\"element_7\"> 
			<option value=\"1024\" selected=\"selected\">1024</option>
<option value=\"2048\" >2048</option>
<option value=\"4096\" >4096</option>
<option value=\"512\" >512</option>
<option value=\"384\" >384</option>
		</select>
		</div><p class=\"guidelines\" id=\"guide_7\"><small>select key length, supported only RSA</small></p> 
		</li>
			
			<li id=\"li_8\" >
		<label class=\"description\" for=\"element_8\">what I must do with data?</label>
		<span>
			<input id=\"element_8_1\" name=\"element_8_1\" class=\"element checkbox\" type=\"checkbox\" value=\"1\" checked=\"checked\"/>
<label class=\"choice\" for=\"element_8_1\">gimme zip, im lazy</label>

		</span><p class=\"guidelines\" id=\"guide_3\"><small>I cant use result like a boss</small></p> 
		</li>
		
					<li class=\"buttons\">
			    <input type=\"hidden\" name=\"form_id\" value=\"574014\" />
			    
				<input id=\"saveForm\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"DOIT\" />
		</li>
			</ul>
		</form>	
		<div id=\"footer\">
			Generated by <a href=\"http://www.phpform.org\">pForm</a>
		</div>
	</div>
	<img id=\"bottom\" src=\"bottom.png\" alt=\"\">
	</body>
</html>";
die(0);
?>