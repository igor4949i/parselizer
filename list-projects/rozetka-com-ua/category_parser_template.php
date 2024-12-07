<?php
set_time_limit(0);
require('./PHPDebug.php');
require('./phpQuery-onefile.php');

// get data from MAIN.JS -- $.post
$url_category = $_POST['parser_data'];

// create list URLs products
$url_product_array = [];

$list_category_page_url = [];
array_push($list_category_page_url, $url_category);

$doc_category_general_html = file_get_contents($url_category);
$doc_category_count = phpQuery::newDocument($doc_category_general_html);
foreach ($doc_category_count->find('.pagination__list .pagination__item .pagination__link:not(.pagination__link_state_active)') as $item) {
  $item = pq($item);
  $cat_page_url = $item->attr('href');
  array_push($list_category_page_url, $cat_page_url);
}

// category_url_product($url_category);
// run parse category
for ($i=0; $i < count($list_category_page_url); $i++) { 
  category_url_product($list_category_page_url[$i]);
}

// run parse each product
for ($i=0; $i < 3; $i++) { // count($url_product_array)
  product_parsing($url_product_array[$i], $i+1);
}

// search URLs products on page category
function category_url_product($url_category) {
  global $url_product_array;
  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('li a.goods-tile__heading') as $item) {
    $item = pq($item);
    $url_product = $item->attr('href');
    array_push($url_product_array, $url_product);
  }
}

// parse each product
function product_parsing($url_item, $number_product) {
  $html = file_get_contents($url_item);
  $doc = phpQuery::newDocument($html);

  //  name_product
  $name_product = $doc->find('.product__title')->text();

  // url product
  $url = $url_item;

  // price
  $price = $doc->find('.product-prices__big')->html();
  $price = preg_replace('/₴/', '', $price); // trim currency symbol
  $price = preg_replace('/[^x\d|*\.]/', '', $price); // trim spaces

  // // // old_price
  // $old_price = $doc->find('.product-prices__small')->html();
  // $old_price = preg_replace('/₴/', '', $old_price);
  // $old_price = preg_replace('/[^x\d|*\.]/', '', $old_price);

  // // sku
  // $sku = $doc->find('.product__code')->html();
 
  // // available
  // $available = $doc->find('.product__status')->text();

  // // desc_full
  // $desc_full = $doc->find('.product-about__description-content.text')->html();
  // $desc_full = preg_replace('/\sstyle=".*?"/', '', $desc_full); // trim styles
  // $desc_full = preg_replace('/\sclass=".*?"/', '', $desc_full); // trim classes
  // // $desc_full = pq($desc_full);
  // // $desc_full->find('a[data-qaid="buy-button"]')->remove(); // remove Prom Buy button

  // $desc_short = $doc->find('.product-about__brief')->html();

  // // category_list
  // $category = $doc->find('.breadcrumbs li a span');
  // $category_list = [];
  // foreach($category as $item) {
  //   $item = pq($item);
  //   $item = trim($item->text());
  //   array_push($category_list, $item);
  // }
  // $category_list = implode('>', $category_list);

  // // category_breadcrumbs_last
  // $category_breadcrumbs_last = $doc->find('.breadcrumbs li a span:last')->text();

  // image url
  $json_template = $doc->find('#rz-client-state')->html();
  $json_reg = '/&q;original&q;:{&q;url&q;:&q;.*?&q;/';
  preg_match_all($json_reg, $json_template, $json_array, PREG_SET_ORDER, 0);
  $json_list_url = [];
  for ($i=0; $i < count($json_array); $i++) {
      $json_array[$i][0] = preg_replace('/&q;original&q;:{&q;url&q;:&q;/', '', $json_array[$i][0]);
      $json_array[$i][0] = preg_replace('/&q;/', '', $json_array[$i][0]);
      array_push($json_list_url, $json_array[$i][0]);
      // echo $json_array[$i][0];
  }
  
  // $main_image_url = $json_list_url[0];
  
  // $additional_images_url = [];
  // for ($i=1; $i < count($json_list_url); $i++) { 
  //   array_push($additional_images_url, $json_list_url[$i]);
  // }
  // $additional_images_url = implode(',', $additional_images_url);
  // $all_images_url = implode(',',$json_list_url);

  // // $characteristics
  // $characteristics_url = $url.'characteristics/';
  // $html_characteristics = file_get_contents($characteristics_url);
  // $doc_characteristics = phpQuery::newDocument($html_characteristics);
  // $characteristics = $doc_characteristics->find('.product-characteristics__list')->html();

  
  echo '<tr>';
    echo '<td class="number_product">'.$number_product.'</td>';
    echo '<td class="name_product">'.$name_product.'</td>';
    echo '<td class="url">'.$url.'</td>';
    echo '<td class="price">'.$price.'</td>';
    // echo '<td class="old_price">'.$old_price.'</td>';
    // echo '<td class="sku">'.$sku.'</td>';
    // echo '<td class="available">'.$available.'</td>';
    // echo '<td class="desc_full">'.$desc_full.'</td>';
    // echo '<td class="desc_full">'.$desc_short.'</td>';
    // echo '<td class="category_list">'.$category_list.'</td>';
    // echo '<td class="category_breadcrumbs_last">'.$category_breadcrumbs_last.'</td>';
    // echo '<td class="characteristics">'.$characteristics.'</td>';
    // echo '<td class="_main_image_url">'.$main_image_url.'</td>';
    // echo '<td class="main_image_url">'.$additional_images_url.'</td>';
    // echo '<td class="json">'.$all_images_url.'</td>';
  echo '</tr>';
}

// PDO
// $servername = "yuntar.mysql.tools";
// $database = "database";
// $username = "username";
// $password = "";

// $dsn = "mysql:host=yuntar.mysql.tools;port=3306;dbname=username;charset=utf8";
// $options = [
  //   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  //   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  // ];

  // $pdo = new PDO($dsn, $username, $password, $options);

  // $stmt = $pdo->prepare('UPDATE name_table_sql SET desc_full=:desc_full WHERE id=:id');
  // $stmt->bindParam(desc_full, $desc_full, PDO::PARAM_STR);
  // $id = 1;
  // $stmt->bindParam(id, $id, PDO::PARAM_STR);
  // $stmt->execute();

  // $stmt = $pdo->prepare('INSERT INTO name_table_sql(name_product, price, old_price, sku, available, desc_full, images_url, category_list, category_breadcrumbs_last)
  //                                            VALUES(:name_product, :price, :old_price, :sku, :available, :desc_full, :images_url, :category_list, :category_breadcrumbs_last)');
  // $stmt->bindParam(name_product,  $name_product,  PDO::PARAM_STR);
  // $stmt->bindParam(price,         $price,         PDO::PARAM_STR);
  // $stmt->bindParam(old_price,     $old_price,     PDO::PARAM_STR);
  // $stmt->bindParam(sku,           $sku,           PDO::PARAM_STR);
  // $stmt->bindParam(available,     $available,     PDO::PARAM_STR);
  // $stmt->bindParam(desc_full,     $desc_full,     PDO::PARAM_STR);
  // $stmt->bindParam(images_url,    $images_url,    PDO::PARAM_STR);
  // $stmt->bindParam(category_list, $category_list, PDO::PARAM_STR);
  // $stmt->bindParam(category_breadcrumbs_last, $category_breadcrumbs_last, PDO::PARAM_STR);

  // $stmt->execute();

  // $stmt = $pdo->query('SELECT desc_full FROM name_table_sql');
  // $results = $stmt->fetchAll(PDO::FETCH_OBJ);
  // var_dump (get_object_vars($results["0"])); // Возвращает ассоциативный массив нестатических свойств объекта object


  // time working php -- PHPDebug
  // echo $debug->debug('Finish time: ' . (microtime(true) - $start) . ' секунд');