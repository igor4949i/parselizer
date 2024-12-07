<?php
set_time_limit(0);
require('./PHPDebug.php');
require('./phpQuery-onefile.php');

$url_category = $_POST['planshetka'];

$url_product_array = [];

category_url_product($url_category);

// count($url_product_array)
for ($i=0; $i < count($url_product_array); $i++) { 
  product_parsing($url_product_array[$i], $i+1);
}

// search url product on page category
function category_url_product($url_category) {
  global $url_product_array;
  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('.cs-goods-title') as $item) {
    $item = pq($item);
    $url_product = $item->attr('href');
    array_push($url_product_array, $url_product);
  }

  $next = $doc_category->find('.b-pager .b-pager__link_type_current')->next()->attr('href');
  // check the next page
  if ($next) {
    $url_category = 'https://planshetka.com.ua'.$next;
    category_url_product($url_category);
  }
}

function product_parsing($url, $number_product) {
  $html = file_get_contents($url);
  $doc = phpQuery::newDocument($html);

  // // sql-column-1 name_product
  // $name_product = $doc->find('span[data-qaid="product_name"]')->text();

  // // sql-column-2 price
  // $price = $doc->find('p[class="b-product-cost__price"]')->text();
  // $price = preg_replace('/грн\./', '', $price); // trim currency symbol
  // $price = preg_replace('/[^x\d|*\.]/', '', $price); // trim spaces

  // // sql-column-3 old_price
  // $old_price = $doc->find('p[class="b-product-cost__old-price"]')->text();
  // $old_price = preg_replace('/(грн\.)|([^x\d|*\.])/', '', $old_price);
  // $old_price = preg_replace('/[^x\d|*\.]/', '', $old_price);

  // // sql-column-4 sku
  // $sku = $doc->find('span[data-qaid="product_code"]')->text();

  // // sql-column-5 available
  // $available = $doc->find('li[data-qaid="presence_data"]')->text();

  // sql-column-6 desc_full
  $desc_full = $doc->find('div[class="b-user-content"]')->html();
  $desc_full = preg_replace('/\sstyle=".*?"/', '', $desc_full); // trim styles
  $desc_full = preg_replace('/\sclass=".*?"/', '', $desc_full); // trim classes
  $desc_full = pq($desc_full); // object phpQuery
  $desc_full->find('a[data-qaid="buy-button"]')->remove(); // remove Buy button

  // // sql-column-7 images_url_array
  // $images = $doc->find('img[class="cs-pictures__img"]');
  // $images_url = [];
  // foreach($images as $item) {
  //   $item = pq($item);
  //   $item = preg_replace('/_w.*?_h.*?_/', '_', $item->attr('src')); // trim width/height sizes -- get full size image
  //   array_push($images_url, $item);
  // }

  // if ($images_additional_block = $doc->find('.cs-pictures__list .cs-pictures-list__img')) {
  //   foreach($images_additional_block as $item) {
  //     $item = pq($item);
  //     $item = preg_replace('/_w.*?_h.*?_/', '_', $item->attr('src'));
  //     array_push($images_url, $item);
  //   }
  // }
  // $images_url = implode(',',$images_url);

  // // // sql-column-8 category_list
  // $category = $doc->find('.b-breadcrumb li a');
  // $category_list = [];
  // foreach($category as $item) {
  //   $item = pq($item);
  //   $item = trim($item->text());
  //   array_push($category_list, $item);
  // }
  // $category_list = implode('|', $category_list);

  // // sql-column-9 category_breadcrumbs_last
  // $category_breadcrumbs_last = $doc->find('.b-breadcrumb li a:last')->text();
  // $category_breadcrumbs_last = preg_replace('/[^x\d|*\.]/', '', $category_breadcrumbs_last);

  echo '<tr>';
    // echo '<td class="number_product">'.$number_product.'</td>';
    // echo '<td class="name_product">'.$name_product.'</td>';
    // echo '<td class="url">'.$url.'</td>';
    // echo '<td class="price">'.$price.'</td>';
    // echo '<td class="old_price">'.$old_price.'</td>';
    // echo '<td class="sku">'.$sku.'</td>';
    // echo '<td class="available">'.$available.'</td>';
    echo '<td class="desc_full">'.$desc_full.'</td>';
    // echo '<td class="images_url">'.$images_url.'</td>';
    // echo '<td class="category_list">'.$category_list.'</td>';
    // echo '<td class="category_breadcrumbs_last">'.$category_breadcrumbs_last.'</td>';
  echo '</tr>';
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


  // time working php
  // echo $debug->debug('Finish time: ' . (microtime(true) - $start) . ' секунд');