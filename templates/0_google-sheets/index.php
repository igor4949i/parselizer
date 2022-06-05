<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/phpQuery-onefile.php';
require __DIR__ . '/PHPDebug.php';
set_time_limit(0);

$debug = new PHPDebug();
echo $debug->debug("Start");
$start = microtime(true);

$values_google_sheets = [];

$url_category = "https://prom.ua/ua/Mobilnye-telefony";

// create list URLs products
// $url_product_array = [];
category_url_product($url_category);


function category_url_product($url_category)
{
  // global $url_product_array;
  global $values_google_sheets;
  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('.AZ_mS.eXCM_.agGcv.kZ6Wd.dNbCc.cOknf .DEsLh.C00vD') as $item) {
    $item = pq($item);
    $url_product = $item->attr('href');
    $url_product = preg_replace('/\?token.*/', '', $url_product); // trim styles
    // array_push($url_product_array, "https://prom.ua/" . $url_product);
    $values_google_sheets[] = ["https://prom.ua/" . $url_product];
  }

  // // get next link page
  // $next = $doc_category->find('.b-pager .b-pager__link_type_current')->next()->attr('href');
  // // checking if exist next link page
  // if ($next) {
  //   $url_category = 'https://url-site.com.ua'.$next;
  //     category_url_product($url_category);
  // }
}


// $values_google_sheets = [["1", "2", "3", "4"]];
// GOOGLE SHEETS
// dropbox
$googleAccountKeyFilePath = './../../credentials/credentials-google-sheets.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
$service = new Google_Service_Sheets($client);
// set ID sheet
$spreadsheetId = '';
$response = $service->spreadsheets->get($spreadsheetId);
$range = 'test!A1'; // Letter name Sheet
$response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

$body = new Google_Service_Sheets_ValueRange(['values' => $values_google_sheets]);

// valueInputOption - Determines how input data should be interpreted.
// https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// RAW | USER_ENTERED
$options = array('valueInputOption' => 'USER_ENTERED');
$service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);


// time working php
echo $debug->debug('End. Time: ' . (microtime(true) - $start) . ' секунд');

?>