<?php
set_time_limit(0);
require __DIR__ . '/vendor/autoload.php';
require './PHPDebug.php';
require './phpQuery-onefile.php';

// get data from MAIN.JS -- $.post
$url_category = $_POST['parser_data'];

// create list URLs products
$data_column_more = [];

$fd_more = fopen('list_data_compare_more.txt', 'r') or die('не удалось открыть файл');
while (!feof($fd_more)) {
	$str = htmlentities(fgets($fd_more));
	$data_column_more[] = trim($str);
}
fclose($fd_more);

$data_column_less = [];

$fd_more = fopen('list_data_compare_less.txt', 'r') or die('не удалось открыть файл');
while (!feof($fd_more)) {
	$str = htmlentities(fgets($fd_more));
	$data_column_less[] = trim($str);
}
fclose($fd_more);

$arr_final_sort = [];

for ($i = 0; $i < count($data_column_more); $i++) {
	$temporary_arr = [];
	$temporary_arr[0] = $data_column_more[$i];
	$temporary_arr[1] = "";
	for ($j = 0; $j < count($data_column_less); $j++) {
		if (trim($data_column_more[$i]) === trim($data_column_less[$j])) {
			$temporary_arr[1] = $data_column_more[$i];
		}
	}

	$arr_final_sort[] = $temporary_arr;
}


for ($i = 0; $i < count($arr_final_sort); $i++) { // count($arr_final_sort)
	data_compare($arr_final_sort[$i][0], $arr_final_sort[$i][1]);
}

// parse each product
function data_compare($arr_column_more, $arr_column_less)
{
	echo '<tr>';
	echo '<td class="number_product">' . $arr_column_more . '</td>';
	echo '<td class="number_product">' . $arr_column_less . '</td>';
	echo '</tr>';
}

// function saveImg($url, $year, $month)
// {
//   $img_name = preg_replace('/.*\//', '', $url);

//   if (!is_dir('images/' . $year . '/' . $month)) {
//     mkdir('images/' . $year . '/' . $month, 0777, true);
//   }

//   // Image path
//   $img_name_path = 'images/' . $year . '/' . $month . '/' . $img_name;

//   // Save image
//   $ch = curl_init($url);
//   $fp = fopen($img_name_path, 'wb');
//   curl_setopt($ch, CURLOPT_FILE, $fp);
//   curl_setopt($ch, CURLOPT_HEADER, 0);
//   curl_exec($ch);
//   curl_close($ch);
//   fclose($fp);
// }

// $values_google_sheets = [["1", "2", "3", "4"]];
$values_google_sheets = $arr_final_sort;
// GOOGLE SHEETS
$googleAccountKeyFilePath = __DIR__ . '/0-test-project-credentials.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
$service = new Google_Service_Sheets($client);
// ID таблицы
$spreadsheetId = '1O52jcsfEN88EJxnIM2HGAj1eV6ulhWjtQbUT_zYcJyw';
$response = $service->spreadsheets->get($spreadsheetId);
$range = 'Аркуш1!A2'; // Letter name Sheet
$response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

$body = new Google_Service_Sheets_ValueRange(['values' => $values_google_sheets]);

// valueInputOption - Determines how input data should be interpreted.
// https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// RAW | USER_ENTERED
$options = array('valueInputOption' => 'USER_ENTERED');
$service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);
