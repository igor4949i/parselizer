<?php

require __DIR__ . '/vendor/autoload.php';
require  __DIR__ . '/phpQuery-onefile.php';
set_time_limit(0);

$url_category_list = [
  // ["https://prolm.com.ua/linejnye-napravlyayushchie", "Все товары"],
  // ["https://prolm.com.ua/ehnergocepi-i-gofrozashchita", "Все товары"],
  // ["https://prolm.com.ua/shvp-i-opory-shvp", "Все товары"],
  // ["https://prolm.com.ua/shpindeli-ehlektroshpindeli", "Все товары"],
  // ["https://prolm.com.ua/servoprivody", "Все товары"],
  // ["https://prolm.com.ua/shagovye-privody", "Все товары"],
  // ["https://prolm.com.ua/podshipniki-i-podshipnikovye-uzly", "Все товары"],
  // ["https://prolm.com.ua/remni-shkivy-mufty", "Все товары"],
  // ["https://prolm.com.ua/zubchatye-peredachi-i-reduktory", "Все товары"],
  // ["https://prolm.com.ua/trapeceidalnye-vinty-i-gajki", "Все товары"],
  // ["https://prolm.com.ua/instrument-dlya-servisa", "Все товары"],
  // ["https://prolm.com.ua/sharikovye-vtulki-i-valy", "Все товары"],
  // ["https://prolm.com.ua/smazki-i-instrument-dlya-smazyvaniya", "Все товары"],
  // ["https://prolm.com.ua/povorotnye-stoly-i-prinadlezhnosti", "Все товары"],
  // ["https://prolm.com.ua/linejnye-aktuatory", "Все товары"],
  // ["https://prolm.com.ua/linejnye-dvigateli", "Все товары"],
  // ["https://prolm.com.ua/stanki-i-prinadlezhnosti", "Все товары"],
  // ["https://prolm.com.ua/linejnye-moduli-i-komplektuyushchie-k-nim", "Все товары"],
  ["https://prolm.com.ua/prochee", "test"],
  // ["https://prolm.com.ua/linejnye-napravlyayushchie/rolikovye-napravlyayushchie", "Все товары"],
  
];

$values_google_sheets = [['URL', 'Наименование', 'Цена', 'Остаток общий', 'Опис', 'Склад 04', 'Склад 03']];
$char_arr = [];

for ($i = 0; $i < count($url_category_list); $i++) { // count($url_category_list)
  $url_product_array = [];
  // [ ["name1", "count1"], ["name2", "count2"], ... ]
  // $values_google_sheets = [];

  ${'counter_pages_' . $i} = '';
  //
  ${'category_page_link_' . $i} = '';
  ${'$next_cat_page_url_' . $i} = [];
  
    
  // $next_url = category_url_product($url_category_list[$i][0], $i);
  category_url_product($url_category_list[$i][0], $i);

  $next_cat_page_url = ${'category_page_link_' . $i};
  // echo $next_cat_page_url . '</br>';

  // echo ${'counter_pages_' . $i};
  // if (${'counter_pages_' . $i} > 1) {
    
  // }
  // echo ${'counter_pages_' . $i} .'</br>';
  for ($m=2; $m < ${'counter_pages_' . $i}+1; $m++) { 
    ${'$next_cat_page_url_' . $i}[] = $next_cat_page_url . $m;
  }

  for ($t=0; $t < count(${'$next_cat_page_url_' . $i}); $t++) { 
    // echo ${'$next_cat_page_url_' . $i}[$t] . '</br>';
    nextPageCategory(${'$next_cat_page_url_' . $i}[$t], $i);
  }
  
  for ($j=0; $j < count($url_product_array); $j++) { // count($url_product_array)
    // echo $j . ' -- '.$url_product_array[$j] . '</br>';
    product_parsing($url_product_array[$j], $j);
  }

  // // // echo count($values_google_sheets) . '</br>';
  // for ($n=0; $n < 5; $n++) { //count($url_product_array)
  //   // echo $values_google_sheets[$n][0] . '</br>';
  //   // echo $values_google_sheets[$n][1] . '</br>';
  //   // echo $values_google_sheets[$n][2] . '</br>';
  //   // echo $values_google_sheets[$n][3] . '</br>';
  //   // echo $values_google_sheets[$n][4] . '</br>';
  //   // echo $values_google_sheets[$n][5] . '</br>';
  //   if ($char_arr[$n]['Обозначение:']) {
  //     echo $n . '---' .$char_arr[$n]['Обозначение:'] . '</br>';
  //   } else {
  //     echo $n . '---' . '</br>';
  //   }
  // }

}

function category_url_product($url_category, $i)
{
  global $url_product_array;
  global ${'category_page_link_' . $i};
  global ${'counter_pages_' . $i};

  $ch = curl_init($url_category);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($ch, CURLOPT_POST, true);
  // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  // curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
  // curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
  $html_category = curl_exec($ch);
  curl_close($ch);
  // var_dump($html_category);

  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('.product__name.link') as $item) {
    $item = pq($item);
    $url_product = $item->attr('href');
    array_push($url_product_array, $url_product);
  }

  $counter = $doc_category->find('.block-description .counter')->text();
  $counter = preg_replace('/(\()|(\))/', '', $counter);
  // echo $counter_p = ceil($counter / 15);
  if ($counter > 15) {
    $counter_p = ceil($counter / 15);
  } else {
    $counter_p = 1;
  }
  
  ${'counter_pages_' . $i} = $counter_p;

  $next = $doc_category->find('.pagination__link.pagination__link_next')->attr('href');
  if($next) {
    $next_url = $next;
    $json_reg_link = '/.*?page=/';
    preg_match($json_reg_link, $next_url, $link);
    ${'category_page_link_' . $i} = $link[0];
  }
}

function nextPageCategory($next_url_cat_page, $i) {
  global $url_product_array;

  $cookies = curl_init($next_url_cat_page);
  curl_setopt($cookies, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($cookies, CURLOPT_POST, true);
  // curl_setopt($cookies, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
  // curl_setopt($cookies, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
  $result = curl_exec($cookies);
  curl_close($cookies);

  $result = json_encode($result);
  $check_next = $result;

  preg_match_all('/product\_\_name.*?html/', $result, $list_url_match);
  // var_dump($list_url_match);

  $json_reg = '/product__name.*?html/';
  preg_match_all($json_reg, $result, $json_array, PREG_SET_ORDER, 0);
  for ($j=0; $j < count($json_array); $j++) {
    $json_array[$j][0] = preg_replace('/\\\/', '', $json_array[$j][0]);
    $json_array[$j][0] = preg_replace('/product__name link\" href=\"/', '', $json_array[$j][0]);
    array_push($url_product_array, $json_array[$j][0]);
    // echo $json_array[$j][0] . '</br>';
  }

  // $json_reg_2 = '/pagination__link_next.*page=\d+/';
  // preg_match($json_reg_2, $check_next, $check_next_page_json);
  // $check_next_page_json[0] = preg_replace('/pagination__link_next.*href=/', '', $check_next_page_json[0]);
  // $check_next_page_json[0] = preg_replace('/\\\/', '', $check_next_page_json[0]);
  // $check_next_page_json[0] = preg_replace('/\"/', '', $check_next_page_json[0]);

  // ${'category_page_link_' . $i}[] = $check_next_page_json[0];
  
  // if ($check_next_page_json[0]) {
  //   // echo $check_next_page_json[0] . '</br>';
  //   nextPageCategory($check_next_page_json[0], $i);
  // }
}



function product_parsing($url_product_item, $j) {
  // sleep(1);
  $num_product = $j;
  global $url_product_array;
  global $values_google_sheets;
  global $char_arr;

  $product_data_arr = [];

  $ch = curl_init($url_product_item);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  // curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
  // curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
  $html_product = curl_exec($ch);
  curl_close($ch);

  $doc_product = phpQuery::newDocument($html_product);

  $url_product_f = $url_product_item;
  $product_data_arr[] = $url_product_f;

  $product_name = $doc_product->find('.content__header h1 span')->text();
  $product_data_arr[] = $product_name;

  $price = $doc_product->find('.product-card__price-info .price')->text();
  $price = trim($price);
  $product_data_arr[] = $price;

  $card__stock = $doc_product->find('.text-count-products-stockrooms')->text();
  $card__stock = preg_replace('/В наличии:/', '', $card__stock);
  $card__stock = preg_replace('/-/', '', $card__stock);
  $card__stock = trim($card__stock);
  $product_data_arr[] = $card__stock;

  $desc = $doc_product->find('#product .block-description-product')->html();
  $desc = trim($desc);
  // $desc = preg_replace('/\sclass=".*?"/', '', $desc); // trim classes

  $product_data_arr[] = $desc;

  $stock_list = $doc_product->find('.stock__wrapper .stock__item');
  $stock_list = pq($stock_list);

  $array_count_item = [];
  $array_number_stock = [];
  $arr_final_stock = [];
  $arr_final_stock_to_str = [];

  $product_data_arr[] = '';
  $product_data_arr[] = '';

  foreach ($stock_list as $item) {
    $item = pq($item);
    $item_count = $item->find('.col.number')->text();
    $item_number_stock = $item->find('.col.number + .col')->text();
    $item_number_stock = trim($item_number_stock);

    if ($item_number_stock === '04') {
      $product_data_arr[5] = $item_count;
    }
    if ($item_number_stock === '03') {
      $product_data_arr[6] = $item_count;
    }
  }

  // characteristics block
  $char_list = $doc_product->find('.characteristic .characteristic__item');
  // $array_char_item = [];
  // $array_char_value = [];
  $arr_final_char = [];
  // $arr_final_char_to_str = [];
  foreach ($char_list as $item) {
    $item = pq($item);
    $item_name = $item->find('b:not(.addon)')->text();
    $item_value = $item->find('.addon')->text();

    // $arr_final_char[$item_name] = $item_value;
    // $arr_final_char_to_str[] = $item_name . '' . $item_value;

    $arr_final_stock[$item_name] = $item_value; 
  }
  // $char_str = implode('|', $arr_final_char_to_str);

  $char_arr[] = $arr_final_stock;

  $values_google_sheets[] = $product_data_arr;
}






// PARSE characteristics
$char_arr_copy = $char_arr;
$count_product = count($char_arr_copy);
$header_char = [];

for ($i=0; $i < $count_product; $i++) { 
  foreach ($char_arr_copy[$i] as $key => $value) {
    array_push($header_char, $key);
  }
}
$header_char = array_unique($header_char);

$empty_arr = [];
for ($i=0; $i < count($header_char); $i++) { 
  array_push($empty_arr, '');
}

$new_char_table_product = [];
for ($i=0; $i < $count_product; $i++) {
  $new_char_table_product[] = $empty_arr;
}


for ($i=0; $i < $count_product; $i++) { 
  foreach ($char_arr_copy[$i] as $key => $value) {
    for ($j=0; $j < count($header_char); $j++) { 
      if ($header_char[$j] === $key) {
        $new_char_table_product[$i][$j] = $value;
      }
    }
  }
}

array_unshift($new_char_table_product, $header_char);

for ($i=0; $i < count($values_google_sheets); $i++) { 
  $values_google_sheets[$i] = array_merge($values_google_sheets[$i] , $new_char_table_product[$i]);

}


// var_dump($new_char_table_product);



// // $values_google_sheets = [['1', '2', '3'], ['1', '2', '3']];
// ////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Start Google Sheets
$googleAccountKeyFilePath = __DIR__ . '/credentials.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
$service = new Google_Service_Sheets($client);

// ID таблицы
$spreadsheetId = '1mANGzH3T_-2PVRearDVVIrWQxOSrIlRW-She69bh7m4';
$response = $service->spreadsheets->get($spreadsheetId);

$range = $url_category_list[0][1].'!A1:AM'; // Letter name Sheet
$response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

$body = new Google_Service_Sheets_ValueRange(['values' => $values_google_sheets]);

// valueInputOption - Determines how input data should be interpreted.
// https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// RAW | USER_ENTERED
$options = array('valueInputOption' => 'USER_ENTERED');
$service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);
////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////




// // CSV FILE
$file_name =  'products-proml';
// $assocDataArray = $values_google_sheets;
export_data_to_csv($values_google_sheets,$filename ,$delimiter = ';',$enclosure = '"');

function export_data_to_csv($data,$filename='products-proml',$delimiter = ';',$enclosure = '"')
{
    // Tells to the browser that a file is returned, with its name : $filename.csv
    header("Content-disposition: attachment; filename=$filename.csv");
    // Tells to the browser that the content is a csv file
    header("Content-Type: text/csv");

    // I open PHP memory as a file
    $file_open_name =  __DIR__ . '/products-proml.csv';
    $fp = fopen($file_open_name, 'w');

    // Insert the UTF-8 BOM in the file
    fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

    // I add the array keys as CSV headers
    fputcsv($fp,array_keys($data[0]),$delimiter,$enclosure);

    // Add all the data in the file
    foreach ($data as $fields) {
        fputcsv($fp, $fields,$delimiter,$enclosure);
    }

    // Close the file
    fclose($fp);

    // Stop the script
    die();
}





echo 'Конец парсинга!';


?>