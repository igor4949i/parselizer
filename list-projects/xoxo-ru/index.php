<?php
require __DIR__ . '/vendor/autoload.php';
require  __DIR__ . '/phpQuery-onefile.php';
set_time_limit(0);


$id_article = 1;
$values_google_sheets = [];
$url_category_list = [
  ['https://xoxo.ru/2017/07/21/neravnyj-brak-13-zvezdnyh-par-s-bolshoj-raznitsej-v-roste/','test'],['https://xoxo.ru/2020/08/12/9-sovetov-kak-chitat-bystree-i-zapominat-bolshe/','test']
];

// $url_category = $url_category_list[0];

for ($i = 0; $i < count($url_category_list); $i++) { // count($url_category_list)
  category_url_product($url_category_list[$i][0]);
}

// category_url_product($url_category);

function category_url_product($url_category)
{
  global $id_article;
  global $values_google_sheets;
  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);
  $doc_category2 = $doc_category->find('#wpd-post-rating')->remove();
  $doc_category3 = $doc_category->find('.rating__shared__html')->remove();
  $doc_category3 = $doc_category->find('.follows-channel')->remove();
  $doc_category3 = $doc_category->find('.content__actions')->remove();
  $title = $doc_category->find('h1')->remove();
  $title = pq($title);
  $title = $title->html();

  $product_data = [];

  $id_article_new = '' . $id_article;
  $product_data[] = $id_article_new;

  $product_data[] = $title;

  $date_1 = preg_replace('#https://xoxo.ru/#', '', $url_category);
  $date_2 = explode('/', $date_1);

  $date = $date_2[0] . '/' . $date_2[1] . '/' . $date_2[2];
  
  $product_data[] = $date;

  $slug = $date_2[3];

  $product_data[] = $slug;

  $description = $doc_category->find('.single-post-content')->html();
  $description = preg_replace('/\sstyle=".*?"/', '', $description); // trim styles


  $description = pq($description);
  $url_image = $description->find('img');
  $arr_img = [];
  foreach ($url_image as $item) {
    $item = pq($item);
    $single_image_url = $item->attr('src');
    array_push($arr_img, $single_image_url);
  }
  $arr_img_str = implode(',', $arr_img);

  for ($i = 0; $i < count($arr_img); $i++) {
    $original_url_img = $arr_img[$i];
    $test2 = preg_replace('#https://xoxo.ru/wp-content/uploads/#', '', $original_url_img);
    $add_img_arr = explode('/', $test2); // массив картинок год/месяц/название

    saveImg($original_url_img, $add_img_arr[0], $add_img_arr[1]);
  }

  $description = preg_replace('#https://xoxo.ru/#', 'http://xoxoru.beget.tech/', $description); // trim styles
  // echo $description;
  $description = trim($description);
  $product_data[] = $description;

  $product_data[] = $arr_img_str;

  $id_article = $id_article + 1;
  $values_google_sheets[] = $product_data;
}


function saveImg($url, $year, $month)
{
  $img_name = preg_replace('/.*\//', '', $url);

  if (!is_dir('images/' . $year . '/' . $month)) {
    mkdir('images/' . $year . '/' . $month, 0777, true);
  }

  // Image path
  $img_name_path = 'images/' . $year . '/' . $month . '/' . $img_name;

  // Save image
  $ch = curl_init($url);
  $fp = fopen($img_name_path, 'wb');
  curl_setopt($ch, CURLOPT_FILE, $fp);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_exec($ch);
  curl_close($ch);
  fclose($fp);
}



// $values_google_sheets = [["1", "2", "3", "4"]];
// GOOGLE SHEETS
$googleAccountKeyFilePath = '0-test-project-credentials.json';
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