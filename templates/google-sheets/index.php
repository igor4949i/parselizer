<?php

require __DIR__ . '/vendor/autoload.php';
set_time_limit(0);


$values_google_sheets = [["1", "2", "3", "4"]];
// GOOGLE SHEETS
$googleAccountKeyFilePath = 'credentials-google-sheets.json';
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

?>