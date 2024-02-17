<?php
set_time_limit(0);
require './vendor/autoload.php';
require './PHPDebug.php';
require './phpQuery-onefile.php';

// get data from MAIN.JS -- $.post
$url_category = $_POST['parser_data'];

// create list URLs products
$data_column_more = [];

//$values_google_sheets = [["test", "test", "test", "test"], ["1", "2", "3", "4"]];
// GOOGLE SHEETS
// $googleAccountKeyFilePath = __DIR__ . '/credentials.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
$service = new Google_Service_Sheets($client);
// ID таблицы
$spreadsheetId = '1O52jcsfEN88EJxnIM2HGAj1eV6ulhWjtQbUT_zYcJyw';

// test
// $spreadsheetId = '1lXkrmFXl-MYbHEhNvb0oMl8IGFEfeRxBhTMHrDWRgZQ';
// $response = $service->spreadsheets->get($spreadsheetId);

$range = 'Test!A1'; // Letter name Sheet

$response = $service->spreadsheets_values->get($spreadsheetId, $range, ['valueRenderOption' => 'FORMATTED_VALUE']);

// $response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

// $body = new Google_Service_Sheets_ValueRange(['values' => $values_google_sheets]);

// valueInputOption - Determines how input data should be interpreted.
// https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// RAW | USER_ENTERED

// $options = array('valueInputOption' => 'USER_ENTERED');
// $service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);

echoResponse($response);

function echoResponse($response)
{
	
	// echo count($response['values']) . '</br>';
	// var_dump($response['values'][1][0]);
	for ($i = 0; $i < count($response['values']); $i++) {
		// echo $response['values'][$i][1] . '</br>';
		echo '<tr>';
		echo '<td class="product-id-' . $response['values'][$i][0] . '">' . $response['values'][$i][1] . '</td>';
		echo '</tr>';
	}
}
