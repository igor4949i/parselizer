<?php
// define( 'WP_USE_THEMES', true );
// /** Loads the WordPress Environment and Template */
// require( dirname( __FILE__ ) . '/wp-blog-header.php' );
// require 'phpQuery-onefile.php';
// require( dirname( __FILE__ ) . '/phpQuery-onefile.php' );
set_time_limit(0);
require('./PHPDebug.php');
require('./phpQuery-onefile.php');
// require( dirname( __FILE__ ) . '/PHPDebug.php' );
// $debug = new PHPDebug();
// echo $debug->debug("Start");
// $start = microtime(true);

$url_category = $_POST['planshetka'];
$url_product_array = [];
category_url_product($url_category);

// count($url_product_array)
for ($i=0; $i < count($url_product_array); $i++) { 
    product_parsing($url_product_array[$i], $i+1);
  }

function category_url_product($url_category) {
    global $url_product_array;
    $html_category = file_get_contents($url_category);
    $doc_category = phpQuery::newDocument($html_category);
  
    foreach ($doc_category->find('.products-category .name a') as $item) {
      $item = pq($item);
      $url_product_additional = $item->attr('href');
      $url_product = 'http://agrokharkov.com.ua/'.$url_product_additional;
      array_push($url_product_array, $url_product);
    }
}

function product_parsing($url, $number_product) {
    $html = file_get_contents($url);
    $doc = phpQuery::newDocument($html);
  
    // sql-column-1 name_product
    $name_product = $doc->find('.product-name h1')->text();
  
    // sql-column-2 price
    $price = $doc->find('.price div div')->text();
    // $price = preg_replace('/грн\./', '', $price); // trim currency symbol
    // $price = preg_replace('/[^x\d|*\.]/', '', $price); // trim spaces
  
    // // sql-column-3 old_price
    // $old_price = $doc->find('p[class="b-product-cost__old-price"]')->text();
    // $old_price = preg_replace('/(грн\.)|([^x\d|*\.])/', '', $old_price);
    // // $old_price = preg_replace('/[^x\d|*\.]/', '', $old_price);
  
    // // sql-column-4 sku
    // $sku = $doc->find('span[data-qaid="product_code"]')->text();
  
    // // sql-column-5 available
    // $available = $doc->find('li[data-qaid="presence_data"]')->text();
  
    // sql-column-6 desc_full
    $desc_full = $doc->find('#tab-description')->html();
    // $desc_full = preg_replace('/\sstyle=".*?"/', '', $desc_full); // trim styles
    // $desc_full = preg_replace('/\sclass=".*?"/', '', $desc_full); // trim classes
    // $desc_full = pq($desc_full); // object phpQuery
    // $desc_full->find('a[data-qaid="buy-button"]')->remove(); // remove Buy button
    
    $desc_short = $doc->find('.list-unstyled')->html();
    $desc_short = preg_replace('/\shref=".*?"/', '/href="/"/', $desc_short); // trim styles

    // sql-column-7 images_url_array
    $images_url = $doc->find('.image-block .thumbnails-image .thumbnail')->attr('href');
    $images_url = 'http://agrokharkov.com.ua/'.$images_url;
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
  
    // // sql-column-8 category_list
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
    // // echo $category_breadcrumbs_last = preg_replace('/[^x\d|*\.]/', '', $category_breadcrumbs_last);
  
    echo '<tr>';
      echo '<td class="number_product">'.$number_product.'</td>';
      echo '<td class="name_product">'.$name_product.'</td>';
      echo '<td class="url">'.$url.'</td>';
      echo '<td class="price">'.$price.'</td>';
      //   echo '<td class="old_price">'.$old_price.'</td>';
      //   echo '<td class="sku">'.$sku.'</td>';
      //   echo '<td class="available">'.$available.'</td>';
      echo '<td class="desc_full">'.$desc_full.'</td>';
      echo '<td class="desc_short">'.$desc_short.'</td>';
      echo '<td class="images_url">'.$images_url.'</td>';
    //   echo '<td class="category_list">'.$category_list.'</td>';
    //   echo '<td class="category_breadcrumbs_last">'.$category_breadcrumbs_last.'</td>';
    echo '</tr>';
  }
  