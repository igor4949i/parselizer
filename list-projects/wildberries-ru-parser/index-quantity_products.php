<?php

require __DIR__ . '/vendor/autoload.php';
require  __DIR__ . '/phpQuery-onefile.php';
set_time_limit(0);

$url_category_list = [];
$fd = fopen("./list_category_wildberries.csv", 'r') or die("не удалось открыть файл");
while(!feof($fd))
{
    $str = htmlentities(fgets($fd));
    $arr_cat = explode(',', $str);
    if (trim($arr_cat[2]) == "on") {
      $arr_item = [$arr_cat[0], $arr_cat[1]];
      $url_category_list[] = $arr_item;
    }
}
fclose($fd);
$count_url_category_list = count($url_category_list);
for ($i = 0; $i < $count_url_category_list; $i++) { // $count_url_category_list
  ${'url_product_array_' . $i} = [];
  ${'table_size_array_' . $i} = [];
  // [ ["name1", "count1"], ["name2", "count2"], ... ]
  ${'values_google_sheets_' . $i} = [];
  // // run parse category / $url_category_list[$i][0]
  category_url_product($url_category_list[$i][0], $i);

  for ($j = 0; $j < count(${'url_product_array_' . $i}); $j++) { // count(${'url_product_array_' . $i})
    // var_dump(count(${'url_product_array_' . $i}[$j]));
    if (${'url_product_array_' . $i}[$j]) {
      product_parsing($i, ${'url_product_array_' . $i}[$j], $j, ${'values_google_sheets_' . $j});
    }
  }


}

function category_url_product($url_category, $i)
{
  $item_num = $i;
  global ${'url_product_array_' . $i};

  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('.dtList.i-dtList.j-card-item') as $item) {
    $item = pq($item);
    $url_product = $item->find('.ref_goods_n_p.j-open-full-product-card')->attr('href');
    array_push(${'url_product_array_' . $i}, $url_product);
  }

  $next = $doc_category->find('.pagination-item.active + .pagination-item')->attr('href');
  // check the next page
  if ($next) {
    $url_category = 'https://www.wildberries.ru' . $next;
    category_url_product($url_category, $item_num);
  }
}


function product_parsing($i, $url_item, $j)
{
  sleep(1);
  $num_cat = $i;
  $url_item = $url_item;
  $number_product = $j;
  global ${'table_size_array_' . $number_product};
  global ${'values_google_sheets_' . $num_cat};

  $html = file_get_contents($url_item);
  $doc = phpQuery::newDocument($html);

  $time_current_parse = date("Y-m-d", time());
  $values_product_google_sheets[] = $time_current_parse;

  $values_product_google_sheets[] = $number_product;

  //  name_product
  $name_product = $doc->find('.brand-and-name.j-product-title .name')->text();
  $values_product_google_sheets[] = $name_product;

  // url product
  $sku = $doc->find('.article .j-article')->text();
  $values_product_google_sheets[] = $sku;

  $size_name_arr = [];
  $size_name = $doc->find('.j-size-list label');
  foreach ($size_name as $item) {
    $item = pq($item);
    $name = $item->find('span')->text();
    array_push($size_name_arr, $name);
  }

  $characteristic_id = [];
  $size_id = $doc->find('.j-size-list label');
  foreach ($size_id as $item) {
    $item = pq($item);
    $id = $item->find('input')->attr('value');
    array_push($characteristic_id, $id);
  }

  ${'table_size_array_' . $number_product}[] = $size_name;
  ${'table_size_array_' . $number_product}[] = $characteristic_id;

  for ($i = 0; $i < count($size_name_arr); $i++) {
    ${'additional_arr_' . $i} = $values_product_google_sheets;
    ${'additional_arr_' . $i}[] = $size_name_arr[$i];
    ${'additional_arr_' . $i}[] = $characteristic_id[$i];

    // echo $characteristic_id[$i] . '</br>';

    ${'data_' . $i} = [
      'cod1S' => $sku,
      'characteristicId' => $characteristic_id[$i],
      'quantity' => '1'
    ];

    ${'quantity_' . $i} = get_value_left(${'data_' . $i});

    while (${'quantity_' . $i} === '') {
      echo 'yes' . '</br>';
      ${'quantity_' . $i} =  get_value_left(${'data_' . $i});
    }

    ${'additional_arr_' . $i}[] = ${'quantity_' . $i};

    ${'values_google_sheets_' . $num_cat}[] = ${'additional_arr_' . $i};
  }

  // ${'values_google_sheets_' . $num_cat}[] = $values_product_google_sheets;
}

function get_value_left($data)
{
  // add_product_to_cart($url_add_product_to_cart);
  add_product_to_cart($data);
  set_time_limit(500);
  add_product_to_cart($data);
  // set_time_limit(100);

  $get_cart_content = get_content_cart();

  $pattern = '/\"maxQuantity\":\d*/';
  preg_match($pattern, $get_cart_content, $matches);
  $matches = preg_replace('/\"maxQuantity\":/', '', $matches);
  $maxQuantity = $matches[0]; // final value

  file_put_contents(__DIR__ . '/cookies.txt', '');
  return $maxQuantity;
}


function add_product_to_cart($data = [])
{
  $url_add_product_to_cart = 'https://lk.wildberries.ru/product/addtobasket';
  // add cookies
  $ch_cookies = curl_init($url_add_product_to_cart);
  curl_setopt($ch_cookies, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch_cookies, CURLOPT_POST, true);
  curl_setopt($ch_cookies, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
  curl_setopt($ch_cookies, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
  curl_exec($ch_cookies);
  curl_close($ch_cookies);
  // add product to cart - need - SKU and ID characteristics
  $ch = curl_init($url_add_product_to_cart);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
  curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
  curl_exec($ch);
  curl_close($ch);
}


function get_content_cart()
{
  $url_cart = 'https://lk.wildberries.ru/basket';
  // update cart
  $ch2 = curl_init($url_cart);
  curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch2, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
  curl_setopt($ch2, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
  $result_cart = curl_exec($ch2);
  curl_close($ch2);
  return $result_cart;
}



for ($i = 0; $i < $count_url_category_list; $i++) {
  // Start Google Sheets
  $googleAccountKeyFilePath = __DIR__ . '/credentials-parser-wildberries-LeftoverSKU.json';
  putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
  $client = new Google_Client();
  $client->useApplicationDefaultCredentials();
  $client->addScope('https://www.googleapis.com/auth/spreadsheets');
  $service = new Google_Service_Sheets($client);
  // ID таблицы
  $spreadsheetId = '1Vt7rb5_fLj5mVF1HtUHzI2vkj6RkYXs_lrUhjTldXUo';
  $response = $service->spreadsheets->get($spreadsheetId);
  $range = $url_category_list[$i][1] . '!A2:G11000'; // Letter name Sheet
  $response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

  $body = new Google_Service_Sheets_ValueRange(['values' => ${'values_google_sheets_' . $i}]);

  // valueInputOption - Determines how input data should be interpreted.
  // https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
  // RAW | USER_ENTERED
  $options = array('valueInputOption' => 'USER_ENTERED');
  $service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);
}

echo '<script>document.title = "Finish";</script>';

echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">';
echo '<div class="alert alert-success" role="alert"><h1>Конец парсинга</h1></div>';