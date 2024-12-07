<?php

require __DIR__ . '/credentials/credentials.php';

/**** configuration variables to set ****/
// AWS API keys
$aws_access_key_id = $your_aws_access_key_id;
$aws_secret_access_key = $your_aws_secret_access_key;

// S3 bucket
$bucket_name = $your_bucket_name;

// aws region
// example : 'us-east-1', 'us-east-2' etc
$aws_region = $your_aws_region;

// path of file in bucket
// example : 'records.json', 'files/test.png'
$file_name_path = $your_file_name_path;

// downloaded file name
// example : 'records.json', 'files/test.png'
$download_name = $your_download_name;

/**** other variables that are automatically set ****/
// bucket host name
$host_name = $bucket_name . '.s3.amazonaws.com';

// service name for S3
$aws_service_name = 's3';

// payload
// no payload in this API
$content = '';

// UTC timestamp and date
$timestamp = gmdate('Ymd\THis\Z');
$date = gmdate('Ymd');

/**** Task 1 : create canonical request for aws signature 4 ****/
// HTTP request headers as key & value
$request_headers = array();
$request_headers['Host'] = $host_name;
$request_headers['Date'] = $timestamp;
$request_headers['x-amz-content-sha256'] = hash('sha256', $content);
// sort it in ascending order
ksort($request_headers);

// canonical headers
$canonical_headers = [];
foreach ($request_headers as $key => $value) {
	$canonical_headers[] = strtolower($key) . ":" . $value;
}
$canonical_headers = implode("\n", $canonical_headers);

// signed headers
$signed_headers = [];
foreach ($request_headers as $key => $value) {
	$signed_headers[] = strtolower($key);
}
$signed_headers = implode(";", $signed_headers);

// cannonical request
$canonical_request = [];
$canonical_request[] = "GET";
$canonical_request[] = "/" . $file_name_path;
$canonical_request[] = "";
$canonical_request[] = $canonical_headers;
$canonical_request[] = "";
$canonical_request[] = $signed_headers;
$canonical_request[] = hash('sha256', $content);
$canonical_request = implode("\n", $canonical_request);
$hashed_canonical_request = hash('sha256', $canonical_request);

/**** Task 2 : creating a string to sign for aws signature 4 ****/
// AWS scope
$scope = [];
$scope[] = $date;
$scope[] = $aws_region;
$scope[] = $aws_service_name;
$scope[] = "aws4_request";

// string to sign
$string_to_sign = [];
$string_to_sign[] = "AWS4-HMAC-SHA256";
$string_to_sign[] = $timestamp;
$string_to_sign[] = implode('/', $scope);
$string_to_sign[] = $hashed_canonical_request;
$string_to_sign = implode("\n", $string_to_sign);

/**** Task 3 : calculating signature for aws signature 4 ****/
// signing key
$kSecret = 'AWS4' . $aws_secret_access_key;
$kDate = hash_hmac('sha256', $date, $kSecret, true);
$kRegion = hash_hmac('sha256', $aws_region, $kDate, true);
$kService = hash_hmac('sha256', $aws_service_name, $kRegion, true);
$kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);

// signature
$signature = hash_hmac('sha256', $string_to_sign, $kSigning);

/**** Task 4 : Add signature to HTTP request ****/
// authorization
$authorization = [
	'Credential=' . $aws_access_key_id . '/' . implode('/', $scope),
	'SignedHeaders=' . $signed_headers,
	'Signature=' . $signature,
];
$authorization = 'AWS4-HMAC-SHA256' . ' ' . implode(',', $authorization);

/**** send HTTP request ****/
// curl headers
$curl_headers = ['Authorization: ' . $authorization];
foreach ($request_headers as $key => $value) {
	$curl_headers[] = $key . ": " . $value;
}

$url = 'https://' . $host_name . '/' . $file_name_path;
// $url = 'https://powerbelt-datastore.s3-eu-west-1.amazonaws.com/uniteh_ukraina';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http_code != 200) {
	exit('Error : Failed to download file');
}

$success = file_put_contents($download_name, $response);
if (!$success) {
	exit('Error : Failed to save file to directory');
}

// Upload file FTP
$name = $your_name;
$filename = $your_filename;

//-- Connection Settings
$ftp_server = $your_ftp_server; // Address of FTP server.
$ftp_user_name = $your_ftp_user_name; // Username
$ftp_user_pass = $your_ftp_user_pass; // Password

$destination_file = "/"; //where you want to throw the file on the webserver (relative to your login dir)

$conn_id = ftp_connect($ftp_server) or die("<span style='color:#FF0000'><h2>Couldn't connect to $ftp_server</h2></span>"); // set up basic connection

$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<span style='color:#FF0000'><h2>You do not have access to this ftp server!</h2></span>"); // login with username and password, or give invalid user message

if ((!$conn_id) || (!$login_result)) { // check connection
	// wont ever hit this, b/c of the die call on ftp_login
	echo "<span style='color:#FF0000'><h2>FTP connection has failed! <br />";
	echo "Attempted to connect to $ftp_server for user $ftp_user_name</h2></span>";
	exit;
} else {
	//echo "Connected to $ftp_server, for user $ftp_user_name <br />";
}

$upload = ftp_put($conn_id, $destination_file . $name, $filename, FTP_BINARY); // upload the file
if (!$upload) { // check upload status
	echo "<span style='color:#FF0000'><h2>FTP upload of $filename has failed!</h2></span> <br />";
} else {
	echo "<span style='color:#339900'><h2>Uploading $name Completed Successfully!</h2></span><br /><br />";
}

ftp_close($conn_id); // close the FTP stream