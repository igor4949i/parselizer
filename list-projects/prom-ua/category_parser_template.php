<?php
set_time_limit(0);
require('./PHPDebug.php');
require('./phpQuery-onefile.php');

// PHPDebug.php
// $debug = new PHPDebug();
// echo $debug->debug("Start");
// $start = microtime(true);



// get data from MAIN.JS -- $.post
$url_category = $_POST['parser_data'];

// create list URLs products
$url_product_array = [];

$list_category_page_url = [];
// array_push($list_category_page_url, $url_category);


// category_url_product($url_category);

// // run parse each product
// for ($i = 0; $i < 10; $i++) { // count($url_product_array)
//   product_parsing($url_product_array[$i], $i+1);
// }

// search URLs products on page category
function category_url_product($url_category) {
  global $url_product_array;
  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('.x-catalog-gallery .x-gallery-tile__name.ek-link.ek-link_style_multi-line') as $item) {
    $item = pq($item);
    $url_product = $item->attr('href');
    array_push($url_product_array, $url_product);
  }

  $next = $doc_category->find('.x-pager__content .x-pager__item_state_selected')->next()->attr('href');
  // check the next page
  if ($next) {
    $url_category = 'https://prom.ua'.$next;
    // category_url_product($url_category);
  }
}


// parse each product
function product_parsing($url_item, $number_product) {
  $html = file_get_contents($url_item);
  $doc = phpQuery::newDocument($html);
  //  name_product
  $name_product = $doc->find('.x-product-info__content .x-title')->text();
  $number_product = $number_product;
  // url product
  $url = $url_item;

  // price
  $price = $doc->find('.x-product-price .x-product-price__value .x-hidden')->text();
  $price = preg_replace('/UAH/', '', $price); // trim currency symbol
  $price = preg_replace('/[^x\d|*\.]/', '', $price); // trim spaces

  // // old_price
  // $old_price = $doc->find('.x-product-price .x-product-price__discount .x-product-price__discount-value')->attr('data-qaprice');
  // $old_price = preg_replace('/₴/', '', $old_price);
  // $old_price = preg_replace('/[^x\d|*\.]/', '', $old_price);

  // // sku
  // $sku = $doc->find('.x-product-info__identity-item span[data-qaid="product-sku"]')->text();

  // // available
  // $available = $doc->find('.x-product-presence')->text();

  // // desc_full
  // $desc_full = $doc->find('.x-user-content')->html();
  // $desc_full = preg_replace('/\sstyle=".*?"/', '', $desc_full); // trim styles
  // $desc_full = preg_replace('/\sclass=".*?"/', '', $desc_full); // trim classes


  // // category_list
  // $category = $doc->find('.x-breadcrumb .x-breadcrumb__item a');
  // $category_list = [];
  // foreach($category as $item) {
  //   $item = pq($item);
  //   $item = trim($item->text());
  //   array_push($category_list, $item);
  // }
  // array_shift($category_list);
  // array_pop($category_list);

  // $category_breadcrumbs_last = $category_list[array_key_last($category_list)];
  // $category_list = implode('>', $category_list);

  // // image
  // $json_template = $doc->find('.x-product-info__images div[data-bazooka="ProductGallery"]')->attr('data-bazooka-props');
  // $json_reg = '/"image_url_100x100".+?".+?"/';
  // preg_match_all($json_reg, $json_template, $json_array, PREG_SET_ORDER, 0);
  // $json_list_url_img = [];
  // for ($i = 0; $i < count($json_array); $i++) {
  //   $json_array[$i][0] = preg_replace('/"image_url_100x100".+?"/', '', $json_array[$i][0]);
  //   $json_array[$i][0] = preg_replace('/"/', '', $json_array[$i][0]);
  //   $json_array[$i][0] = preg_replace('/_w.*?_h.*?_/', '_', $json_array[$i][0]);
  //   array_push($json_list_url_img, $json_array[$i][0]);
  // }
  // $main_image_url = $json_list_url_img[0];
  // $all_images_url = implode(',', $json_list_url_img);
  // array_shift($json_list_url_img);
  // $additional_images_url = implode(',', $json_list_url_img);

  // // $characteristics
  // $characteristics_data = $doc->find('.x-product-attr');
  // pq($characteristics_data)->find('.x-title')->remove();
  // pq($characteristics_data)->find('.x-product-attr__more-link')->remove();
  // $characteristics = $characteristics_data->html();
  // $characteristics = preg_replace('/\shref=".*?"/', ' href="#"', $characteristics);

  echo '<tr>';
  echo '<td class="number_product">'.$number_product.'</td>';
  echo '<td class="name_product">'.$name_product.'</td>';
  echo '<td class="url">'.$url.'</td>';
  echo '<td class="price">'.$price.'</td>';
  // echo '<td class="old_price">'.$old_price.'</td>';
  // echo '<td class="sku">'.$sku.'</td>';
  // echo '<td class="available">'.$available.'</td>';
  // echo '<td class="desc_full">'.$desc_full.'</td>';
  // echo '<td class="category_list">'.$category_list.'</td>';
  // echo '<td class="category_breadcrumbs_last">'.$category_breadcrumbs_last.'</td>';
  // echo '<td class="_main_image_url">'.$main_image_url.'</td>';
  // echo '<td class="main_image_url">'.$additional_images_url.'</td>';
  // echo '<td class="all_images_url">'.$all_images_url.'</td>';
  // echo '<td class="characteristics">'.$characteristics.'</td>';
  echo '</tr>';

  // // PDO
  // $servername = "fja00.mysql.tools";
  // $database = "fja00_test";
  // $username = "fja00_test";
  // $password = "JNb_!n30f5";
  // $servername = "127.0.0.1";
  // $database = "parse_test";
  // $username = "root";
  // $password = "";


  // $dsn = "mysql:host=$servername;port=3306;dbname=$database;charset=utf8";
  // $dsn_Options = [
  //   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  //   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  // ];

  // $pdo = new PDO($dsn, $username, $password, $dsn_Options);

  // $stmt = $pdo->prepare('INSERT INTO test_prom (number_product, name_product, url, price) 
  //                                       VALUES (:number_product, :name_product, :url, :price)');
  // $stmt->bindParam(':number_product',  $number_product,  PDO::PARAM_STR);
  // $stmt->bindParam(':name_product',  $name_product,  PDO::PARAM_STR);
  // $stmt->bindParam(':url',         $url,         PDO::PARAM_STR);
  // $stmt->bindParam(':price',         $price,         PDO::PARAM_STR);

  // $stmt->execute();
}
// PDO
// $servername = "yuntar.mysql.tools";
// $database = "yuntar_planshetk";
// $username = "yuntar_planshetk";
// $password = "";

// $dsn = "mysql:host=yuntar.mysql.tools;port=3306;dbname=yuntar_planshetk;charset=utf8";
// $options = [
  //   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  //   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  // ];

  // $pdo = new PDO($dsn, $username, $password, $options);

  // $stmt = $pdo->prepare('UPDATE products_planshetka SET desc_full=:desc_full WHERE id=:id');
  // $stmt->bindParam(desc_full, $desc_full, PDO::PARAM_STR);
  // $id = 1;
  // $stmt->bindParam(id, $id, PDO::PARAM_STR);
  // $stmt->execute();

  // $stmt = $pdo->prepare('INSERT INTO products_planshetka(name_product, price, old_price, sku, available, desc_full, images_url, category_list, category_breadcrumbs_last)
  //                                                 VALUES(:name_product, :price, :old_price, :sku, :available, :desc_full, :images_url, :category_list, :category_breadcrumbs_last)');
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

  // $stmt = $pdo->query('SELECT desc_full FROM products_planshetka');
  // $results = $stmt->fetchAll(PDO::FETCH_OBJ);
  // var_dump (get_object_vars($results["0"])); // Возвращает ассоциативный массив нестатических свойств объекта object

