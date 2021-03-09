<?php
/*******Settings for Web Server******************/
//Please add the below settings in another file and add them here using include

$domainname = "neatcomfort"; //Will be useful to generate the file link
$domainhost="github.com";
$domainuser="neatcomfort";
$domainpass="Zesu1970";
$homelink = "neatcomfort/neatcomfort.github.io"; // This is the parent directory in which public_html directory is contained. Will start with /home
$publiclink = "public_html/";
$savedfiledirectory="myfiles"; //folder in which files uploaded by customer will be saved
$destdir=$homelink.$publiclink.$savedfiledirectory;

$tmpName = basename($_FILES['file']['tmp_name']);

/*****Settings***********************/
	 global $publiclink;
	 
	 $uploaded = false;
	 
	 $conn =@ftp_connect($domainhost) or die ("Cannot initiate connection to host");
    $login_result = @ftp_login($conn, $domainuser, $domainpass)or die("Cannot login");
		ftp_pasv($conn, true);
	 @ftp_set_option($conn, FTP_TIMEOUT_SEC, 1000);
	
ftp_chdir($conn, $destdir); 
$file = $_FILES["file"]["tmp_name"];
$remote_file = $_FILES["file"]["name"]; 
	 if(!@ftp_put($conn, $remote_file, $file, FTP_BINARY)){
                $uploaded = false;
                
	 }else{
       
		$uploaded = true;
	 }  
	@ftp_quit($conn);
    @ftp_close($conn)

//Initialize the $query_string variable for later use
$query_string = '';

//If there are POST variables
if ($_POST) {
$_POST['<Replace with field Id of the File Link that we created in Step 1']=$domainname.'/'.$_FILES["file"]["name"];
//Initialize the $kv array for later use
$kv = array();

//For each POST variable as $name_of_input_field =&gt; $value_of_input_field
foreach ($_POST as $key => $value) {

//Set array element for each POST variable (ie. first_name=Arsham)
$kv[] = stripslashes($key).'='.stripslashes($value);

}

//Create a query string with join function separted by &amp;
$query_string = implode ('&', $kv);
}

//Check to see if cURL is installed ...
if (!function_exists('curl_init')){
die('Sorry cURL is not installed!');
}

$curl_connection = curl_init('https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8');
//set options
curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($curl_connection, CURLOPT_USERAGENT,
"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
 
//set data to be posted
curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $query_string);
//perform our request
$result = curl_exec($curl_connection);
//show information regarding the request
print_r(curl_getinfo($curl_connection));
echo curl_errno($curl_connection) . '-' . curl_error($curl_connection);
//close the connection
curl_close($curl_connection);
?>