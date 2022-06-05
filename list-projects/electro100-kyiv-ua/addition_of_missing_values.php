<?php
set_time_limit(0);
require __DIR__ . '/vendor/autoload.php';
require './PHPDebug.php';
require './phpQuery-onefile.php';

// get data from MAIN.JS -- $.post
$url_category = $_POST['parser_data'];

// create list URLs products
$arr_num = [];

$fd = fopen('list_data.txt', 'r') or die('не удалось открыть файл');
while (!feof($fd)) {
	$str = htmlentities(fgets($fd));
	$arr_num[] = $str;
}
fclose($fd);

$arr_num_sorted = [];
$no_empty_value = $arr_num[0];

for ($i = 0; $i < count($arr_num); $i++) { // count($arr_num)
	$value = trim($arr_num[$i]);

	if (!$value) {
		$arr_num_sorted[] = $no_empty_value;
	} else {
		$arr_num_sorted[] = $value;
		$no_empty_value = $value;
	}
}

// run parse each product
for ($i = 0; $i < count($arr_num_sorted); $i++) { // count($arr_num_sorted)
	$value = trim($arr_num_sorted[$i]);

	num_return($arr_num_sorted[$i]);
}

// parse each product
function num_return($number_product)
{
	echo '<tr>';
	echo '<td class="number_product">' . $number_product . '</td>';
	echo '</tr>';
}



// $values_google_sheets = [["1", "2", "3", "4"]];
// GOOGLE SHEETS
$googleAccountKeyFilePath = '0-test-project-credentials.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
$service = new Google_Service_Sheets($client);
// ID таблицы
$spreadsheetId = '1O52jcsfEN88EJxnIM2HGAj1eV6ulhWjtQbUT_zYcJyw';
$response = $service->spreadsheets->get($spreadsheetId);
$range = 'test!A2'; // Letter name Sheet
$response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

$body = new Google_Service_Sheets_ValueRange(['values' => $values_google_sheets]);

// valueInputOption - Determines how input data should be interpreted.
// https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// RAW | USER_ENTERED
$options = array('valueInputOption' => 'USER_ENTERED');
$service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);