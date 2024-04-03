<?php
set_time_limit(0);
require('PHPDebug.php');
require('./phpQuery-onefile.php');

$url_category = $_POST['planshetka'];

$url_hotels_array = [];

category_url_product($url_category);

// count($url_hotels_array)
for ($i=1450; $i < count($url_hotels_array); $i++) { 
  product_parsing($url_hotels_array[$i], $i+1);
}

// search url product on page category
function category_url_product($url_category) {
  global $url_hotels_array;
  $html_category = file_get_contents($url_category);
  $doc_category = phpQuery::newDocument($html_category);

  foreach ($doc_category->find('.cart-company-lg__title.ui-title-inner a') as $item) {
    $item = pq($item);
    $url_product = $item->attr('href');
    array_push($url_hotels_array, 'https://www.ua-region.com.ua'.$url_product);
  }

  $next = $doc_category->find('.pagination .active + li a')->attr('href');
  // check the next page
  if ($next) {
    $url_category = $next;
    category_url_product($url_category);
  }
}


function product_parsing($url, $number_product) {
  $html = file_get_contents($url);
  $doc = phpQuery::newDocument($html);

  $name_hotel = $doc->find('h1.ui-title')->text();

  // $left_side_bar = $doc->find('.d-none.d-lg-block.company-sidebar.p-3.p-md-4.mb-3.border .company-sidebar__item');
  $center_side_bar = $doc->find('.company-main-info .company-sidebar__item');

  $company_sidebar__item = [];
  // foreach ($left_side_bar as $item) {
  //   $item = pq($item);
  //   pq($item)->find('.company-sidebar__label')->remove();
  //   $item = $item->text();
  //   array_push($company_sidebar__item, $item);

  //   echo $item . '</br>';
  // }

  foreach ($center_side_bar as $item) {
    $item = pq($item);
    // pq($item)->find('.company-sidebar__label')->remove();
    $company_sidebar__label = $item->find('.company-sidebar__label')->text();
    $company_sidebar__data = $item->find('.company-sidebar__data')->text();
    $item = $company_sidebar__label . '===' . $company_sidebar__data;
    array_push($company_sidebar__item, $item);

    // echo $item . '</br>';
  }

  $company_sidebar__item_1 = implode('---', $company_sidebar__item);

  // for ($i=0; $i < count($company_sidebar__item[$i]); $i++) { 
  //   echo $company_sidebar__item[$i] . '</ br>';
  // }

  

  


  echo '<tr>';
    echo '<td class="number_product">'. $number_product . '</td>';
    echo '<td class="url">'. $url . '</td>';
    echo '<td class="name_hotel">'. $name_hotel . '</td>';

    echo '<td class="company-sidebar__item_1">'. $company_sidebar__item_1 . '</td>';
    
  echo '</tr>';
}