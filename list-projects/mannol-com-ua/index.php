<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/phpQuery-onefile.php';

$id_product = 1;
$values_google_sheets = [];
$url_category_list = ['http://mannol.com.ua/katalog-tovarov/masla/masla-motornye-3/originilnye-masla/', 'mannolcomua'];

$url_category = $url_category_list[0];

category_url_product($url_category);

function category_url_product($url_category)
{
  global $id_product;
  global $values_google_sheets;
  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  $breadcrumbs_arr = [];
  $breadcrumbs = $doc_category->find('.breadcrumbs a');
  foreach ($breadcrumbs as $item) {
    $item = pq($item);
    $item = $item->text();
    if ($item && $item !== 'Каталог продукции') {
      $breadcrumbs_arr[] = $item;
    }
  }
  $breadcrumbs_str = implode('|', $breadcrumbs_arr);

  foreach ($doc_category->find('.ggis-inlinepost') as $item) {
    $item = pq($item);
    $product_data = [];

    $product_data[] = $id_product;
    $product_data[] = $breadcrumbs_str;

    $description = $item->find('.polezn_info .opisnie_tov')->text();

    $table_prod = $item->find('.polezn_info > table');
    $table_prod = preg_replace('/\sclass=".*?"/', '', $table_prod);

    $description = $description . $table_prod;
    $product_data[] = $description;

    $url_image = $item->find('.polezn_info img')->attr('src');

    saveImg($url_image, $id_product);

    $img_name = preg_replace('/.*\//', '', $url_image);

    if (!is_dir('images/' . $id_product)) {
      mkdir('images/' . $id_product, 0777, true);
    }
    // Image path
    $img_name = 'images/' . $id_product . '/' . $img_name;

    $product_data[] = $img_name;

    $id_product = $id_product + 1;
    $values_google_sheets[] = $product_data;
  }

  $next = $doc_category->find('.page-links span:not(.page-links-title) + a')->attr('href');
  // check the next page
  if ($next) {
    $url_category = $next;
    category_url_product($url_category);
  }
}


// SAVE IMAGES FROM URL
// $url = 'http://mannol.com.ua/wp-content/themes/twentythirteen/images/tovar/Oil/motornie/ENERGY-PREMIUM.png';

// $id = '1';
// saveImg($url, $id);
function saveImg($url, $id)
{
  $img_name = preg_replace('/.*\//', '', $url);

  if (!is_dir('images/' . $id)) {
    mkdir('images/' . $id, 0777, true);
  }

  // Image path
  $img_name_path = './images/' . $id . '/' . $img_name;

  // Save image
  $ch = curl_init($url);
  $fp = fopen($img_name_path, 'wb');
  curl_setopt($ch, CURLOPT_FILE, $fp);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_exec($ch);
  curl_close($ch);
  fclose($fp);
}

// for ($i = 0; $i < count($url_category_list); $i++) { // count($url_category_list)
//   ${'url_product_array_' . $i} = [];
//   ${'new_product_' . $i} = [];
//   ${'time_in_stock_' . $i} = [];
//   //    [ ["name1", "image1"], ["name2", "image2"], ... ]
//   ${'values_google_sheets_' . $i} = [];
//   // run parse category / $url_category_list[$i][0]
//   category_url_product($url_category_list[$i][0], $i);

//   for ($j = 0; $j < 1; $j++) { // count(${'url_product_array_' . $i})
//     product_parsing($i, ${'url_product_array_' . $i}[$j], $j, ${'values_google_sheets_' . $j}, ${'new_product_' . $j}, ${'time_in_stock_' . $j});
//   }
// }



// // $values_google_sheets = [["1", "2", "3", "4"]];
// // GOOGLE SHEETS
// $googleAccountKeyFilePath = './../../credentials/credentials-parser-wildberries-LeftoverSKU.json';

// putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
// $client = new Google_Client();
// $client->useApplicationDefaultCredentials();
// $client->addScope('https://www.googleapis.com/auth/spreadsheets');
// $service = new Google_Service_Sheets($client);
// // ID таблицы
// $spreadsheetId = '1Vt7rb5_fLj5mVF1HtUHzI2vkj6RkYXs_lrUhjTldXUo';
// $response = $service->spreadsheets->get($spreadsheetId);
// $range = 'mannolcomua!A2'; // Letter name Sheet
// $response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

// $body = new Google_Service_Sheets_ValueRange(['values' => $values_google_sheets]);

// // valueInputOption - Determines how input data should be interpreted.
// // https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// // RAW | USER_ENTERED
// $options = array('valueInputOption' => 'USER_ENTERED');
// $service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);



















?>