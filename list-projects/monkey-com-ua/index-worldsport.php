<?php

require 'phpQuery-onefile.php';
// set_time_limit(0);
require_once("PHPDebug.php");
$debug = new PHPDebug();
echo $debug->debug("Start crawling");
$start = microtime(true);

// $list_url_array = [];

// if ($file = fopen("list.txt", "r")) {
//   while(!feof($file)) {
//     $line = fgets($file);
//     array_push($list_url_array, $line);
//   }
//   fclose($file);
// }


// 0) create all html doc from local URL
for($i = 175; $i <= 190; $i++){
  ${'url_'.$i} = 'html/product ('.$i.').html';
  // global $list_url_array;
  // ${'url_'.$i} = $list_url_array[$i-4];
  ${'html_'.$i} = file_get_contents(${'url_'.$i});
  ${'doc_'.$i} = phpQuery::newDocument(${'html_'.$i});
}


// column - SKU
for($i = 175; $i <= 190; $i++){
  ${'sku_'.$i} = ${'doc_'.$i}->find('main > section > div > div:nth-child(1) > div.col-sm-7.col-sm-offset-1 > h4 strong')->text();
}

// column - Category
for($i = 175; $i <= 190; $i++){
  ${'category_list_'.$i} = ${'doc_'.$i}->find('.breadcrumb li span');
  ${'category_list_array_'.$i} = [];
  foreach(${'category_list_'.$i} as $item) {
    $item = pq($item);
    $category_step = $item->text();
    array_push(${'category_list_array_'.$i}, $category_step);
  }
  ${'category_'.$i} = ${'category_list_array_'.$i}[count(${'category_list_array_'.$i})-2];

  // column - Name
  ${'name_'.$i} = ${'category_list_array_'.$i}[count(${'category_list_array_'.$i})-1];
}


// column - Price
for($i = 175; $i <= 190; $i++){
  if (${'doc_'.$i}->find('.price-border .price')) {
    ${'price_'.$i} = ${'doc_'.$i}->find('.price-border .price')->text();
  } else {
    ${'price_'.$i} = ${'doc_'.$i}->find('.price-border .price-new')->text();
  }  
}

// column - Price Old
for($i = 175; $i <= 190; $i++){
  ${'price_old_'.$i} = ${'doc_'.$i}->find('.price-border .price-old')->html();
}

// column - Desc
for($i = 175; $i <= 190; $i++){
  ${'desc_'.$i} = ${'doc_'.$i}->find('.tab-content #w0-tab0')->html();
}

for($i = 175; $i <= 190; $i++){
  ${'main_image_url_'.$i} = ${'doc_'.$i}->find('.fotorama img');
}


// column - Main Image Url
// column - Images
for($i = 175; $i <= 190; $i++){
  ${'main_image_url_array_'.$i} = ${'doc_'.$i}->find('.fotorama img');

  ${'result_url_array_'.$i} = [];
  foreach(${'main_image_url_array_'.$i} as $item) {
    $item = pq($item);
    $images_step_url = $item->attr('src');
    $images_step_url = 'https://www.worldsport.ua'.$images_step_url;
    array_push(${'result_url_array_'.$i}, $images_step_url);
  }
  ${'main_image_url_'.$i} = ${'result_url_array_'.$i}[0];
  unset(${'result_url_array_'.$i}[0]);
  ${'result_url_array_'.$i} = implode(',', ${'result_url_array_'.$i});  
}


echo '<table>';
echo '<tr class="title">';
  echo '<th class="number">'.'№'.'</th>';
  echo '<th class="sku">'.'SKU'.'</th>';
  echo '<th class="category">'.'category'.'</th>';
  echo '<th class="name">'.'Name'.'</th>';
  echo '<th class="price">'.'Price'.'</th>';
  echo '<th class="price_old">'.'Price Old'.'</th>';
  echo '<th class="desc">'.'Desc'.'</th>';
  echo '<th class="main_images_url">'.'Main Image URL'.'</th>';
  echo '<th class="images_url">'.'Images URL'.'</th>';
echo '</tr>';

for($i = 175; $i <= 190; $i++){
  echo '<tr class="url-'.$i.'">';
    echo '<td class="number">'.$i.'</td>';
    echo '<td class="category">'.${'category_'.$i}.'</td>';
    echo '<td class="sku">'.${'sku_'.$i}.'</td>';
    echo '<td class="name">'.${'name_'.$i}.'</td>';
    echo '<td class="price">'.${'price_'.$i}.'</td>';
    echo '<td class="price_old">'.${'price_old_'.$i}.'</td>';
    echo '<td class="desc">'.${'desc_'.$i}.'</td>';
    echo '<td class="main_images_url">'.${'main_image_url_'.$i}.'</td>';
    echo '<td class="images_url">'.${'result_url_array_'.$i}.'</td>';
  echo '</tr>';
}

echo '</table>';

// time working php
echo $debug->debug('Time crawling all products: ' . (microtime(true) - $start) . ' секунд');