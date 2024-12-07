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

$category_list = [];
$category_breadcrumbs_last = '';

category_url_product($url_category);

// run parse each product
for ($i = 0; $i < count($url_product_array); $i++) { // count($url_product_array)
  product_parsing($url_product_array[$i], $i, $category_list, $category_breadcrumbs_last);
}

// search URLs products on page category
function category_url_product($url_category) {
  global $url_product_array;
  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('.b-goods-list-item .colors .b-color-dot a') as $item) {
    $item = pq($item);
    $url_product = $item->attr('href');
    $url_product = 'http://joma.com.ua' . $url_product;
    array_push($url_product_array, $url_product);
  }

  $category = $doc_category->find('.breadcrumb a:last');
  global $category_list;
  // $category = pq($category);
  $category = trim($category->text());
  $category_list = $category;
  
  // array_pop($category_list);
  // global $category_breadcrumbs_last;
  global $category_breadcrumbs_last;
  $category_breadcrumbs_last = $doc_category->find('.breadcrumb')->html();
  $$category_breadcrumbs_last = pq($category_breadcrumbs_last);
  $category_breadcrumbs_last = preg_replace('/<a href=".*<\/a>/', '', $category_breadcrumbs_last);
  $category_breadcrumbs_last = preg_replace('#(?<!\\\\)(\\$|\\\\)#', '', $category_breadcrumbs_last); // delete backslash
  $category_breadcrumbs_last = trim($category_breadcrumbs_last);
  $category_list = $category_list . ' > ' . $category_breadcrumbs_last;
}


// parse each product
function product_parsing($url_item, $number_product, $category_list, $category_breadcrumbs_last) {
  $html = file_get_contents($url_item);
  $doc = phpQuery::newDocument($html);
  //  name_product
  $name_product = $doc->find('.page-title')->text();
  $name_product = preg_replace('/\s\s/', '', $name_product);
  $name_product = trim($name_product);
  $number_product = $number_product;
  // url product
  $url = $url_item;
  // sku
  $sku = $doc->find('.b-article')->text();
  $sku = preg_replace('/Артикул: /', '', $sku);
  // desc_full
  $desc_full = $doc->find('.content')->html();
  $desc_full = preg_replace('/\sstyle=".*?"/', '', $desc_full); // trim styles
  $desc_full = preg_replace('/\sclass=".*?"/', '', $desc_full); // trim classes

  $category_list = $category_list;

  $category_breadcrumbs_last = $category_breadcrumbs_last;

  // // OLD CODE image
  // $image_url_search = $doc->find('.fancybox-thumb');
  // $all_images_url_arr = [];
  // foreach($image_url_search as $item) {
  //   $item = pq($item);
  //   $item = $item->attr('href');
  //   array_push($all_images_url_arr, $item);
  // }
  // $main_image_url = $all_images_url_arr[0];
  // $all_images_url = implode(',', $all_images_url_arr);
  // array_shift($all_images_url_arr);
  // $additional_images_url = implode(',', $all_images_url_arr);

  $main_image_url = $doc->find('#goods-preview a')->attr('href');
  $main_image_url = preg_replace('/\?.*/', '', $main_image_url);

  // color
  $color = $doc->find('.b-good-color .label')->text();
  $color = strval($color);
  $color = str_replace("\n", "", $color);
  $color = trim($color);
  $color = str_replace("  ", "", $color);

  // color dot
  $color_dot_left = $doc->find('.left-color')->attr('style');
  $color_dot_left = preg_replace('/(background-color:)|(;)/', '', $color_dot_left);
  $color_dot_left = trim($color_dot_left);

  $color_dot_right_search = $doc->find('.right-color')->attr('style');
  $color_dot_right_search = preg_replace('/(background-color:)|(;)/', '', $color_dot_right_search);
  $color_dot_right_search = trim($color_dot_right_search);

  if ($color_dot_left != $color_dot_right_search) {
    $color_dot_right = $color_dot_right_search;
  }

  $size_foot = '';
  //
  // $characteristics
  $characteristics_data_label_all = $doc->find('.b-option .label');
  $characteristics_data_label = [];
  foreach($characteristics_data_label_all as $item) {
    $item = pq($item);
    $item = $item->html();
    array_push($characteristics_data_label, $item);
  }

  $characteristics_data_value_all = $doc->find('.b-option .value');
  $characteristics_data_value = [];
  foreach($characteristics_data_value_all as $item) {
    $item = pq($item);
    $item = $item->html();
    array_push($characteristics_data_value, $item);
  }

  for ($i=0; $i < count($characteristics_data_label); $i++) { 
    if ($characteristics_data_label[$i] === 'Размеры:') {
      $size_foot = $characteristics_data_value[$i];
      $size_foot = explode(' ', $size_foot);
    }
  }

  //
  // // size_table
  // $size_table = $doc->find('.b-sizes-table table');
  // // size
  // $size = preg_replace('/<td class="label">.*<\/td>/', ' ', $size_table);
  // // size
  // $size_table_3 = $doc->find('.b-sizes-table table td:not(".label")')->text();
  // $size_table_3 = strval($size_table_3);
  // $size_table_3 = str_replace("\n", " ", $size_table_3);
  // $size_table_3 = trim($size_table_3);
  // $size_table_3 = str_replace("  ", " ", $size_table_3);
  // $size_table_3 = str_replace(" ", "|", $size_table_3);

  // $size_arr = explode('|', $size_table_3);
  // for ($i=0; $i < count($size_foot); $i++) { // вивести по масиву значень Size
    echo '<tr>';
    echo '<td class="number_product">'.$number_product.'</td>';
    echo '<td class="name_product">'.$name_product.'</td>';
    echo '<td class="price">0</td>';
    // echo '<td class="url">'.$url.'</td>';
    echo '<td class="sku">'.$sku.'</td>';
    // echo '<td class="desc_full">'.$desc_full.'</td>';
    // echo '<td class="category_breadcrumbs_last">'.$category_breadcrumbs_last.'</td>';
    echo '<td class="_main_image_url">'.$main_image_url.'</td>';
    // echo '<td class="main_image_url">'.$additional_images_url.'</td>';
    // echo '<td class="characteristics">'.$characteristics.'</td>';
    echo '<td class="color">'.$color.'</td>';
    // echo '<td class="size">'.$size_arr[$i].'</td>';
    // echo '<td class="size">'.$size_foot[$i].'</td>';
    // echo '<td class="all_images_url">'.$all_images_url.'</td>';
    echo '<td class="category_list">Аксесуари > М\'ячі</td>';
    echo '<td class="color_dot_left">'.$color_dot_left.'</td>';
    echo '<td class="color_dot_right">'.$color_dot_right.'</td>';
    // echo '<td class="size_table">'.$size_table.'</td>';
    // echo '<td class="size_table_2">'.$size_table_2.'</td>';
    // echo '<td class="size_table_3">'.$size_table_3.'</td>';
    echo '</tr>';
  // }
}