<?php

require __DIR__ . '/vendor/autoload.php';
require  __DIR__ . '/phpQuery-onefile.php';
set_time_limit(0);

$url = 'https://m.trendyol.com/siyah-inci/kadin-murdum-destekli-sutyen-249-p-2652353';
echo $html_category = file_get_contents($url);
$doc_category = phpQuery::newDocument($html_category);



$url_category_list = [
  ["https://m.trendyol.com/siyah-inci/kadin-murdum-destekli-sutyen-249-p-2652353", "test"],
  // ["https://braz.in.ua/g84009183-dvernye-profilya", "test"],
  // ["https://braz.in.ua/g16366312-profilya-dlya-pola", "test"],
  // ["https://braz.in.ua/g16372326-profilya-dlya-stupenej", "test"],
  // ["https://braz.in.ua/g16375803-plitochnye-profilya", "test"],
  // ["https://braz.in.ua/g22641643-standartnye-profilya", "test"],
  // ["https://braz.in.ua/g84240509-led-profilya", "test"],
  // ["https://braz.in.ua/g84284200-pravila", "test"],
  // ["https://braz.in.ua/g84315231-profili-dlya-zaschity", "test"],
  // ["https://braz.in.ua/g84280840-uslugi", "test"]
];

$values_google_sheets = [];

$url_product_array = [];

// for ($i = 0; $i < count($url_category_list); $i++) {
//   category_url_product($url_category_list[$i][0], $i);
// }

// // count($url_product_array)
// for ($i = 0; $i < count($url_product_array); $i++) { //count($url_product_array)
//   product_parsing($url_product_array[$i], $i + 1);
// }


// search url product on page category
function category_url_product($url_category)
{
  global $url_product_array;
  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  var_dump($doc_category);

  // foreach ($doc_category->find('.b-product-gallery__title') as $item) {
  //   $item = pq($item);
  //   $url_product = $item->attr('href');
  //   array_push($url_product_array, $url_product);
  //   // echo $url_product . '</br>';
  // }

  // $next = $doc_category->find('.b-pager__link_type_current + .b-pager__link')->attr('href');
  // // echo $next;
  // // check the next page
  // if ($next) {
  //   $url_category = 'https://braz.in.ua' . $next;
  //   // echo $url_category . '</br>';
  //   category_url_product($url_category);
  // }
}


function product_parsing($url_product_item, $j)
{
  global $values_google_sheets;

  $product_data_arr = [];

  $html = file_get_contents($url_product_item);
  $doc = phpQuery::newDocument($html);

  $code_product = $doc->find('.b-product-data__item_type_sku span')->text();
  $product_data_arr[] = $code_product;

  $product_name = $doc->find('.b-product__name span')->text();
  $product_data_arr[] = $product_name;

  $keywords = ''; // Ключевые_слова
  $product_data_arr[] = $keywords;

  $desc = $doc->find('.b-user-content')->html();
  $desc = trim($desc);
  $product_data_arr[] = $desc;

  $type = ''; //Тип_товара
  $product_data_arr[] = $type;

  $price = $doc->find('.b-product-cost__price span[data-qaid="product_price"]')->text();
  $product_data_arr[] = $price;
  $currency = $doc->find('.b-product-cost__price span[data-qaid="currency"]')->text();
  $product_data_arr[] = $currency;
  $measure_unit = $doc->find('.b-product-cost__price span[data-qaid="measure_unit"]')->text();
  $measure_unit = preg_replace('#/#', '', $measure_unit);
  $product_data_arr[] = $measure_unit;

  $min_order = $doc->find('.b-product-cost__min-order')->text(); // Минимальный_объем_заказа
  $product_data_arr[] = $min_order;

  $wholesale_price = $doc->find('#product-wholesale-prices .b-data-list__name-wrap .b-data-list__name span[data-qaid="wholesale_price"]')->text();
  $product_data_arr[] = $wholesale_price;

  $eligible_quantity = $doc->find('#product-wholesale-prices .b-data-list__value')->text();
  $product_data_arr[] = $eligible_quantity;

  $images = $doc->find('.b-product-view img');
  $images_url = [];
  foreach ($images as $item) {
    $item = pq($item);
    $item = preg_replace('/_w.*?_h.*?_/', '_', $item->attr('src')); // trim width/height sizes -- get full size image
    array_push($images_url, $item);
  }
  $images_url = implode(', ', $images_url);
  $product_data_arr[] = $images_url;

  $stock = "'+"; // Наличие
  $product_data_arr[] = $stock;

  $sale = ''; // Скидка
  $product_data_arr[] = $sale;

  $manufacturer = '';
  $manufacturer_country = '';

  $manufacturer_name = [];
  $manufacturer_value = [];
  $characteristics_block_name = $doc->find('.b-product-info tr td:nth-child(1)');
  $characteristics_block_value = $doc->find('.b-product-info tr td:nth-child(2)');
  foreach ($characteristics_block_name as $item_name) {
    $item_name = pq($item_name);
    $item_name = $item_name->text();
    $manufacturer_name[] = $item_name;
  }
  foreach ($characteristics_block_value as $item) {
    $item = pq($item);
    $item = $item->text();
    $manufacturer_value[] = $item;
  }

  for ($i = 0; $i < count($manufacturer_name); $i++) {
    $manufacturer_name[$i] = strtr($manufacturer_name[$i], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
    $manufacturer_name[$i] = trim($manufacturer_name[$i], chr(0xC2) . chr(0xA0)); // trim &nbsp
    $manufacturer_name[$i] = trim($manufacturer_name[$i]);

    if ($manufacturer_name[$i] == 'Производитель') {
      $manufacturer = $manufacturer_value[$i];
    }
    if ($manufacturer_name[$i] == 'Страна производитель') {
      $manufacturer_country = $manufacturer_value[$i];
    }
  }
  $product_data_arr[] = $manufacturer;
  $product_data_arr[] = $manufacturer_country;

  $breadcrumbs = $doc->find('.b-path .b-path__item a:last')->html();
  $number_group = '';

  switch ($breadcrumbs) {
    case 'Плинтуса':
      $number_group = '1234567';
      break;
    case 'Фурнитура к плинтусам':
      $number_group = '1234568';
      break;
    case 'Дверные профиля':
      $number_group = '1234569';
      break;
    case 'Профиля для пола':
      $number_group = '1234570';
      break;
    case 'Профиля для ступеней':
      $number_group = '1234571';
      break;
    case 'Плиточные профиля':
      $number_group = '1234572';
      break;
    case 'Стандартные профиля':
      $number_group = '1234573';
      break;
    case 'Полосы':
      $number_group = '1234574';
      break;
    case 'Т-образные профиля':
      $number_group = '1234575';
      break;
    case 'Трубы круглые':
      $number_group = '1234576';
      break;
    case 'Трубы квадратные':
      $number_group = '1234577';
      break;
    case 'Трубы прямоугольные':
      $number_group = '1234578';
      break;
    case 'П-образные профиля':
      $number_group = '1234579';
      break;
    case 'Углы равносторонние':
      $number_group = '1234580';
      break;
    case 'Углы разносторонние':
      $number_group = '1234581';
      break;
    case 'LED профиля':
      $number_group = '1234582';
      break;
    case 'Правила':
      $number_group = '1234583';
      break;
    case 'Профили для защиты от скольжения и грязи':
      $number_group = '1234584';
      break;
    case 'Услуги':
      $number_group = '1234585';
      break;
    }

  $product_data_arr[] = $number_group;
  $product_data_arr[] = $breadcrumbs;
  $product_data_arr[] = '';  $product_data_arr[] = '';  $product_data_arr[] = '';  $product_data_arr[] = '';  $product_data_arr[] = '';  $product_data_arr[] = '';  $product_data_arr[] = '';  $product_data_arr[] = '';  $product_data_arr[] = '';

  // $manufacturer_name
  // $manufacturer_value
  $measure_characteristics = '';
  for ($i=0; $i < count($manufacturer_name); $i++) { 
    $product_data_arr[] = $manufacturer_name[$i];
    $product_data_arr[] = $measure_characteristics;
    $product_data_arr[] = $manufacturer_value[$i];
  }

  $values_google_sheets[] = $product_data_arr;
}



// // $values_google_sheets = [["1", "2", "3", "4"]];
// // GOOGLE SHEETS
// $googleAccountKeyFilePath = __DIR__ . '/credentials.json';
// $googleAccountKeyFilePath = './../../credentials/credentials-wildberries.json';

// putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
// $client = new Google_Client();
// $client->useApplicationDefaultCredentials();
// $client->addScope('https://www.googleapis.com/auth/spreadsheets');
// $service = new Google_Service_Sheets($client);
// // ID таблицы
// $spreadsheetId = '1eSFcG5XhLtV0d58EefpDqYEuL5yNQG7U5ftNMtY0-IU';
// $response = $service->spreadsheets->get($spreadsheetId);
// $range = 'test!A2'; // Letter name Sheet
// $response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

// $body = new Google_Service_Sheets_ValueRange(['values' => $values_google_sheets]);

// // valueInputOption - Determines how input data should be interpreted.
// // https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// // RAW | USER_ENTERED
// $options = array('valueInputOption' => 'USER_ENTERED');
// $service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);
