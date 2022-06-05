<?php

require __DIR__ . '/vendor/autoload.php';
require  __DIR__ . '/phpQuery-onefile.php';
require  __DIR__ . './PHPDebug.php';
// PHPDebug.php
$debug = new PHPDebug();
echo $debug->debug("Start");
$start = microtime(true);

set_time_limit(0);

$url_category = 'https://www.wildberries.ru/brands/ytro';

$time_current_parse = date("Y-m-d", time());

// create list URLs products
$url_product_array = [];

$new_product = [];
$time_in_stock = [];

//    [ [], [], []... ]
$values_google_sheets = [];

// run parse category
category_url_product($url_category);

// run parse each product
for ($i = 0; $i < count($url_product_array); $i++) { // count($url_product_array)
  product_parsing($url_product_array[$i], $i);
}

// search URLs products on each page category
function category_url_product($url_category)
{
  global $url_product_array;
  global $new_product;
  global $time_in_stock;

  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('.dtList.i-dtList.j-card-item') as $item) {
    $item = pq($item);
    $url_product = $item->find('.ref_goods_n_p.j-open-full-product-card')->attr('href');
    array_push($url_product_array, $url_product);
  }

  foreach ($doc_category->find('.dtList.i-dtList.j-card-item') as $item) {
    $item = pq($item);
    $check_new = $item->find('.noveltyImg.c-text-xsm');

    if ($check_new) {
      $new_product[] = $check_new->text();
      $time_in_stock[] = $check_new->attr('title');
    } else {
      $new_product[] = '';
      $time_in_stock[] = '';
    }
  }

  $next = $doc_category->find('.pagination-next')->attr('href');
  // check the next page
  if ($next) {
    $url_category = 'https://www.wildberries.ru' . $next;
    category_url_product($url_category);
  }
}

// parse each product
function product_parsing($url_item, $number_product)
{
  global $values_google_sheets;
  global $time_current_parse;
  global $new_product;
  global $time_in_stock;


  $html = file_get_contents($url_item);
  $doc = phpQuery::newDocument($html);

  $values_product_google_sheets[] = $time_current_parse;

  $number_product = $number_product;
  $values_product_google_sheets[] = $number_product;

  //  name_product
  $name_product = $doc->find('.brand-and-name.j-product-title .name')->text();
  $values_product_google_sheets[] = $name_product;

  $brand = $doc->find('.brand-and-name.j-product-title .brand')->text();
  $values_product_google_sheets[] = $brand;

  $price = $doc->find('.old-price .c-text-base')->text();
  $values_product_google_sheets[] = $price;

  $price_sale = $doc->find('.final-price-block .final-cost')->text();
  $price_sale = preg_replace('/[^x\d|*\.]/', '', $price_sale);
  $values_product_google_sheets[] = $price_sale;

  // url product
  $sku = $doc->find('.article .j-article')->text();
  $values_product_google_sheets[] = $sku;

  $url_item = $url_item;
  $values_product_google_sheets[] = $url_item;

  $main_photo = $doc->find('#photo .preview-photo.j-preview-photo')->attr('src');
  $main_photo = preg_replace('/\/\//', '', $main_photo);
  $main_photo = '=IMAGE("https://' . $main_photo . '")';
  $values_product_google_sheets[] = $main_photo;

  $json_template = $doc->find('body')->html();
  $json_reg = '/(data:\s\{"dataForVisited").*/';
  preg_match($json_reg, $json_template, $matches);
  $matches = $matches[0];

  $json_reg_2 = '/' . $sku . '\,\"ordersCount\":\d*/';
  preg_match($json_reg_2, $json_template, $matches2);
  $orders_count = preg_replace('/.*\"ordersCount\"\:/', '', $matches2[0]);

  $values_product_google_sheets[] = $orders_count;

  $values_product_google_sheets[] = $new_product[$number_product];
  $values_product_google_sheets[] = $time_in_stock[$number_product];

  $values_google_sheets[] = $values_product_google_sheets;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Start Google Sheets
$googleAccountKeyFilePath = __DIR__ . '/credentials.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
$service = new Google_Service_Sheets($client);

// ID таблицы
$spreadsheetId = '1eSFcG5XhLtV0d58EefpDqYEuL5yNQG7U5ftNMtY0-IU';
$response = $service->spreadsheets->get($spreadsheetId);

$range = 'ytro-test2!A2'; // Letter name Sheet
// $response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

$body = new Google_Service_Sheets_ValueRange(['values' => $values_google_sheets]);

// valueInputOption - Determines how input data should be interpreted.
// https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// RAW | USER_ENTERED
$options = array('valueInputOption' => 'USER_ENTERED');
$service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


echo $debug->debug('Finish time: ' . (microtime(true) - $start) . ' секунд');
