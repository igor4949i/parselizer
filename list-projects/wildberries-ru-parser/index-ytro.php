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
  ${'new_product_' . $i} = [];
  ${'time_in_stock_' . $i} = [];
  //    [ ["name1", "image1"], ["name2", "image2"], ... ]
  ${'values_google_sheets_' . $i} = [];
  // run parse category / $url_category_list[$i][0]
  category_url_product($url_category_list[$i][0], $i);

  ${'count_url_product_array_' . $i} = count(${'url_product_array_' . $i});
  for ($j = 0; $j < ${'count_url_product_array_' . $i}; $j++) { // count(${'url_product_array_' . $i})
    product_parsing($i, ${'url_product_array_' . $i}[$j], $j, ${'values_google_sheets_' . $j}, ${'new_product_' . $j}, ${'time_in_stock_' . $j});
  }
}


function category_url_product($url_category, $i)
{
  $item_num = $i;
  global ${'url_product_array_' . $i};
  global ${'new_product_' . $i};
  global ${'time_in_stock_' . $i};

  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('.dtList.i-dtList.j-card-item') as $item) {
    $item = pq($item);
    $url_product = $item->find('.ref_goods_n_p.j-open-full-product-card')->attr('href');
    array_push(${'url_product_array_' . $i}, $url_product);
  }

  foreach ($doc_category->find('.dtList.i-dtList.j-card-item') as $item) {
    $item = pq($item);
    $check_new = $item->find('.noveltyImg.c-text-xsm');
    if ($check_new) {
      ${'new_product_' . $i}[] = $check_new->text();
      ${'time_in_stock_' . $i}[] = $check_new->attr('title');
    } else {
      ${'new_product_' . $i}[] = '';
      ${'time_in_stock_' . $i}[] = '';
    }
  }

  $next = $doc_category->find('.pagination-next')->attr('href');
  // check the next page
  if ($next) {
    $url_category = 'https://www.wildberries.ru' . $next;
    category_url_product($url_category, $item_num);
  }
}


function product_parsing($i, $url_item, $j, $new_product, $time_in_stock)
{
  $num_cat = $i;
  $url_item = $url_item;
  $number_product = $j;
  $new_product = $new_product;
  $time_in_stock = $time_in_stock;
  global ${'new_product_' . $num_cat};
  global ${'time_in_stock_' . $num_cat};
  global ${'values_google_sheets_' . $num_cat};
  // $values_google_sheets = $values_google_sheets;

  $html = file_get_contents($url_item);
  $doc = phpQuery::newDocument($html);

  $time_current_parse = date("Y-m-d", time());
  $values_product_google_sheets[] = $time_current_parse;

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

  $values_product_google_sheets[] = ${'new_product_' . $num_cat}[$number_product];
  $values_product_google_sheets[] = ${'time_in_stock_' . $num_cat}[$number_product];

  ${'values_google_sheets_' . $num_cat}[] = $values_product_google_sheets;
}


for ($i = 0; $i < $count_url_category_list; $i++) {
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
  $range = $url_category_list[$i][1] . '!A2'; // Letter name Sheet
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

echo '<audio autoplay>
<source src="./resources/sound.mp3" type="audio/ogg; codecs=vorbis">
<source src="./resources/sound.mp3" type="audio/mpeg">
Тег audio не поддерживается вашим браузером. <a href="./resources/sound.mp3">Скачайте музыку</a>.
</audio>';