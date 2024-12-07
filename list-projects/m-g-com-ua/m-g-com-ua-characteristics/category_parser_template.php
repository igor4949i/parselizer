<?php
set_time_limit(0);
require ('./PHPDebug.php');
require ('./phpQuery-onefile.php');

// PHPDebug.php
// $debug = new PHPDebug();
// echo $debug->debug("Start");
// $start = microtime(true);



// get data from MAIN.JS -- $.post
$url_category = $_POST['parser_data'];

// create list URLs products
$url_product_array = ["https://vok.com.ua/product/freza-freud-dlya-srashhivaniya-d12-d38-h32-l70-z2-99-03112p/",
"https://vok.com.ua/product/freza-freud-dlya-uglovogo-srashhivaniya-d12-d55-h23-l61-45-z2-99-0/",
"https://vok.com.ua/product/freza-freud-dlya-uglovogo-srashhivaniya-d12-d70-h295-l675-45-z2/",
"https://vok.com.ua/product/freza-freud-dlya-srashhivaniya-d12-d35-h415-l78-z2-99-03712p/",
"https://vok.com.ua/product/freza-freud-dlya-srashhivaniya-s-verkhnim-podshi/",
"https://vok.com.ua/product/freza-freud-dlya-srashhivaniya-s-verkhnim-podshi-2/",
"https://vok.com.ua/product/freza-freud-dlya-uglovogo-srashhivaniya-d8-d373-h222-l542-225-z/",
"https://vok.com.ua/product/freza-freud-mnogoprofilnaya-d12-d547-h49-l102-s127-z2-99-pk112p/",
"https://vok.com.ua/product/freza-freud-mnogoprofilnaya-d12-d318-h20-l58-s-z2-99-pk212p/",
"https://vok.com.ua/product/freza-freud-mnogoprofilnaya-d12-d318-h238-l72-s127-z2-99-pk312p/",
"https://vok.com.ua/product/freza-freud-mnogoprofilnaya-d12-d444-h286-l771-s127-z2-99-00812p/",
"https://vok.com.ua/product/freza-freud-pazovaya-v-obraznaya-s-podshipniko-2/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d8-d1815-h127-l549-c127-15-z2-40-10008p/",
"https://vok.com.ua/product/freza-freud-pazovaya-v-obraznaya-s-podshipniko-3/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d8-d218-h25-l673-c127-113-z2-40-09408p/",
"https://vok.com.ua/product/freza-freud-pazovaya-v-obraznaya-s-podshipniko-4/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d8-d236-h127-l549-c127-25-z2-40-10208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-s-tverdosp-4/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-s-tverdosp/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-s-tverdosp-5/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-s-tverdosp-2/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-s-tverdosp-6/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-s-tverdosp-3/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d6-d254-h254-l737-c127-15-z2-40-09806p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d8-d254-h254-l737-c127-15-z2-40-09808p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d6-d31-h95-l52-c127-45-z2-40-10506p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d8-d31-h95-l52-c127-45-z2-40-10508p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d6-d301-h225-l647-c127-225-z2-40-10106p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d8-d301-h225-l647-c127-225-z2-40-10108p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d6-d33-h115-l54-c127-45-z2-40-10406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d8-d33-h115-l54-c127-45-z2-40-10408p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d6-d33-h19-l616-c127-30-z2-40-20206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d8-d33-h19-l616-c127-30-z2-40-20208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d6-d44-h185-l61-c127-45-z2-40-10606p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d8-d44-h185-l61-c127-45-z2-40-10608p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d12-d218-h25-l733-c127-113-z2-40-09412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d12-d254-h254-l737-c127-15-z2-40-09812p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d12-d301-h225-l707-c127-225-z2-40-10112p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d12-d44-h185-l67-c127-45-z2-40-11412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-konusnaya-d12-d621-h255-l74-c127-45-z2-40-11812p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-10/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-14/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-17/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-15/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-18/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-16/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-19/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d318-h127-r48-l542c95-z2-38-20206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d318-h127-r48-l542c95-z2-38-20208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d381-h157-r64-l574c95-z2-38-20406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d381-h157-r64-l574c95-z2-38-20408p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d381-h157-r64-l634c95-z2-38-21412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d318-h127-r48-l617c95-z2-38-21212p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d27-h133-r4-l547c95-z2-38-10006p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d27-h133-r4-l547c95-z2-38-10008p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d27-h127-r4-l603c95-z2-38-10412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d35-h185-r64-l605c95-z2-38-10206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d35-h185-r64-l605c95-z2-38-10208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d349-h185-r64-l66c95-z2-38-10612p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d286-h127-r14-l547c127-r24-38-60206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d286-h127-r14-l547c127-r24-38-60208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d286-h127-r14-l612c127-r24-38-61212p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d349-h183-r164-l603c127-r248-38-60406/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d349-h183-r164-l603c127-r248-38-60408/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d349-h175-r164-l657c127-r248-38-6141/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d294-h135-r4-l555-c95-z2-38-30606p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d294-h135-r4-l555-c95-z2-38-30608p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d294-h135-r4-l555-c95-z2-38-31212p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d389-h183-r64-l603-c95-z2-38-30406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d389-h183-r64-l603-c95-z2-38-30408p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d389-h183-r64-l603-c95-z2-38-31412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d327-h145-r3-l567-c127-z2-38-80006p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d327-h145-r3-l567-c127-z2-38-80008p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d367-h165-r4-l587-c127-z2-38-80206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d367-h165-r4-l587-c127-z2-38-80208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d407-h18-r5-l602-c127-z2-38-80406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d407-h18-r5-l602-c127-z2-38-80408p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d318-h15-r32-l568-c95-z2-38-45206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d318-h15-r32-l568-c95-z2-38-45208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d6-d222-h143-r32-l57/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d8-d222-h143-r32-l57/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d12-d222-h143-r32-l6/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d6-d254-h175-r48-l60/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d8-d254-h175-r48-l60/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d12-d254-h175-r48-l6/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d6-d302-h222-r71-l65/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d8-d302-h222-r71-l65/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d12-d302-h222-r71-l6/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d6-d222-h274-l699-c1/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d8-d222-h274-l699-c1/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d12-d222-h274-l794-c/",
"https://vok.com.ua/product/freza-freud-mnogoprofilnaya-d12-d238-h35-l835-s127-z2-99-01212p/",
"https://vok.com.ua/product/freza-freud-mnogoprofilnaya-d12-d238-h35-l835-s127-z2-99-01312p/",
"https://vok.com.ua/product/freza-freud-dlya-ruchek-integrirovanykh-v-meb/",
"https://vok.com.ua/product/freza-freud-dlya-peril-d12-d635-h19-l662-c127-15-z2-99-02712p/",
"https://vok.com.ua/product/freza-freud-dlya-peril-d12-d35-h385-l866-c127-25-z2-99-07212p/"];

$list_category_page_url = [
  "https://vok.com.ua/search?search=99-03112P",
"https://vok.com.ua/search?search=99-03512P",
"https://vok.com.ua/search?search=99-03412P",
"https://vok.com.ua/search?search=99-03712P",
"https://vok.com.ua/search?search=99-03912P",
"https://vok.com.ua/search?search=99-04212P",
"https://vok.com.ua/search?search=99-04308P",
"https://vok.com.ua/search?search=99-PK112P",
"https://vok.com.ua/search?search=99-PK212P",
"https://vok.com.ua/search?search=99-PK312P",
"https://vok.com.ua/search?search=99-00812P",
"https://vok.com.ua/search?search=40-10006P",
"https://vok.com.ua/search?search=40-10008P",
"https://vok.com.ua/search?search=40-09406P",
"https://vok.com.ua/search?search=40-09408P",
"https://vok.com.ua/search?search=40-10206P",
"https://vok.com.ua/search?search=40-10208P",
"https://vok.com.ua/search?search=40-90006P",
"https://vok.com.ua/search?search=40-90008P",
"https://vok.com.ua/search?search=40-90206P",
"https://vok.com.ua/search?search=40-90208P",
"https://vok.com.ua/search?search=40-90406P",
"https://vok.com.ua/search?search=40-90408P",
"https://vok.com.ua/search?search=40-09806P",
"https://vok.com.ua/search?search=40-09808P",
"https://vok.com.ua/search?search=40-10506P",
"https://vok.com.ua/search?search=40-10508P",
"https://vok.com.ua/search?search=40-10106P",
"https://vok.com.ua/search?search=40-10108P",
"https://vok.com.ua/search?search=40-10406P",
"https://vok.com.ua/search?search=40-10408P",
"https://vok.com.ua/search?search=40-20206P",
"https://vok.com.ua/search?search=40-20208P",
"https://vok.com.ua/search?search=40-10606P",
"https://vok.com.ua/search?search=40-10608P",
"https://vok.com.ua/search?search=40-09412P",
"https://vok.com.ua/search?search=40-09812P",
"https://vok.com.ua/search?search=40-10112P",
"https://vok.com.ua/search?search=40-11412P",
"https://vok.com.ua/search?search=40-11812P",
"https://vok.com.ua/search?search=23-10008P",
"https://vok.com.ua/search?search=23-20008P",
"https://vok.com.ua/search?search=23-20012P",
"https://vok.com.ua/search?search=23-20208P",
"https://vok.com.ua/search?search=23-20212P",
"https://vok.com.ua/search?search=23-20408P",
"https://vok.com.ua/search?search=23-20412P",
"https://vok.com.ua/search?search=38-20206P",
"https://vok.com.ua/search?search=38-20208P",
"https://vok.com.ua/search?search=38-20406P",
"https://vok.com.ua/search?search=38-20408P",
"https://vok.com.ua/search?search=38-21412P",
"https://vok.com.ua/search?search=38-21212P",
"https://vok.com.ua/search?search=38-10006P",
"https://vok.com.ua/search?search=38-10008P",
"https://vok.com.ua/search?search=38-10412P",
"https://vok.com.ua/search?search=38-10206P",
"https://vok.com.ua/search?search=38-10208P",
"https://vok.com.ua/search?search=38-10612P",
"https://vok.com.ua/search?search=38-60206P",
"https://vok.com.ua/search?search=38-60208P",
"https://vok.com.ua/search?search=38-61212P",
"https://vok.com.ua/search?search=38-60406P",
"https://vok.com.ua/search?search=38-60408P",
"https://vok.com.ua/search?search=38-61412P",
"https://vok.com.ua/search?search=38-30606P",
"https://vok.com.ua/search?search=38-30608P",
"https://vok.com.ua/search?search=38-31212P",
"https://vok.com.ua/search?search=38-30406P",
"https://vok.com.ua/search?search=38-30408P",
"https://vok.com.ua/search?search=38-31412P",
"https://vok.com.ua/search?search=38-80006P",
"https://vok.com.ua/search?search=38-80008P",
"https://vok.com.ua/search?search=38-80206P",
"https://vok.com.ua/search?search=38-80208P",
"https://vok.com.ua/search?search=38-80406P",
"https://vok.com.ua/search?search=38-80408P",
"https://vok.com.ua/search?search=38-45206P",
"https://vok.com.ua/search?search=38-45208P",
"https://vok.com.ua/search?search=80-10206P",
"https://vok.com.ua/search?search=80-10208P",
"https://vok.com.ua/search?search=80-12212P",
"https://vok.com.ua/search?search=80-10406P",
"https://vok.com.ua/search?search=80-10408P",
"https://vok.com.ua/search?search=80-12412P",
"https://vok.com.ua/search?search=80-10806P",
"https://vok.com.ua/search?search=80-10808P",
"https://vok.com.ua/search?search=80-12812P",
"https://vok.com.ua/search?search=80-55206P",
"https://vok.com.ua/search?search=80-55208P",
"https://vok.com.ua/search?search=80-57212P",
"https://vok.com.ua/search?search=84-10606P",
"https://vok.com.ua/search?search=84-10608P",
"https://vok.com.ua/search?search=84-12612P",
"https://vok.com.ua/search?search=99-01212P",
"https://vok.com.ua/search?search=99-01312P",
"https://vok.com.ua/search?search=99-00712P",
"https://vok.com.ua/search?search=99-02712P",
"https://vok.com.ua/search?search=99-07212P",
"https://vok.com.ua/search?search=TG74MD%20CA3",
"https://vok.com.ua/search?search=TG74MD%20CB3",
"https://vok.com.ua/search?search=TG74MD%20CC3",
"https://vok.com.ua/search?search=TG74MD%20CD3",
"https://vok.com.ua/search?search=TG74MD%20CE3",
"https://vok.com.ua/search?search=TG74MD%20CF3",
"https://vok.com.ua/search?search=TG76MD%20CD3",
"https://vok.com.ua/search?search=TG76MD%20CE3",
"https://vok.com.ua/search?search=TG62MD%20AD3",
"https://vok.com.ua/search?search=TG62MD%20BD3",
"https://vok.com.ua/search?search=TG63MD%20CD3"
  
];

// array_push($list_category_page_url, $url_category);

// category_url_product($url_category);

// run parse each product
for ($i = 0; $i < count($url_product_array); $i++) { // count($url_product_array)
  product_parsing($url_product_array[$i], $i + 1);
}


// for ($i = 0; $i < count($list_category_page_url); $i++) { // count($url_product_array)
//   category_url_product($list_category_page_url[$i], $i);
// }

// search URLs products on page category

function category_url_product($url_product_array, $i)
{
  $html = file_get_contents($url_product_array);
  $doc = phpQuery::newDocument($html);

  $number_product = $i + 1;
  $name = $doc->find('.us-category-products .us-module-item .us-module-title a')->text();
  $url = $doc->find('.us-category-products .us-module-item .us-module-title a')->attr('href');
  $html2 = file_get_contents($url);
  $doc2 = phpQuery::newDocument($html2);
  $art = $doc2->find('.us-product-info-code')->text();

  echo '<tr>';
  echo '<td class="number_product">' . $number_product . '</td>';
  echo '<td class="name">' . $name . '</td>';
  echo '<td class="url">' . $url . '</td>';
  echo '<td class="art">' . $art . '</td>';
  echo '</tr>';
}

function product_parsing($url_item, $number_product)
{
  $html = file_get_contents($url_item);
  $doc = phpQuery::newDocument($html);
  //  name_product
  $name_product = $doc->find('.us-main-shop-title')->text();
  $number_product = $number_product;
  // url product
  $url = $url_item;

  // art
  $art = $doc->find('.us-product-info-code')->text();

  // $characteristics
  $characteristics_data = $doc->find('.us-product-attributes-cont');
  $characteristics_data_title = $doc->find('.us-product-attributes-cont .us-product-attr-item span:even');
  $characteristics_data_value = $doc->find('.us-product-attributes-cont .us-product-attr-item span:odd');

  $characteristics_data_title_final = [];
  $characteristics_data_value_final = [];


  foreach ($characteristics_data_title as $item) {
    $item = pq($item);
    $item = trim($item->text());
    array_push($characteristics_data_title_final, $item);
  }

  foreach ($characteristics_data_value as $item) {
    $item = pq($item);
    $item = trim($item->text());
    array_push($characteristics_data_value_final, $item);
  }

  $characteristics_data_D = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Наружный диаметр (D)") {
      $characteristics_data_D = $characteristics_data_value_final[$i];
    }
  }

  $characteristics_data_L = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Длина (L)") {
      $characteristics_data_L = $characteristics_data_value_final[$i];
    }
  }

  $characteristics_data_h = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Длина реза (h)") {
      $characteristics_data_h = $characteristics_data_value_final[$i];
    }
  }

  $characteristics_data_Z = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Количество зубьев (Z)") {
      $characteristics_data_Z = $characteristics_data_value_final[$i];
    }
  }

  
  $characteristics_data_Ob_Min = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Макс. об/мин") {
      $characteristics_data_Ob_Min = $characteristics_data_value_final[$i];
    }
  }

  
  $characteristics_data_Destination = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Назначение") {
      $characteristics_data_Destination = $characteristics_data_value_final[$i];
    }
  }
  
  $characteristics_data_Degree = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Градусы (O)") {
      $characteristics_data_Degree = $characteristics_data_value_final[$i];
    }
  }
  
  $characteristics_data_R = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Радиус (R)") {
      $characteristics_data_R = $characteristics_data_value_final[$i];
    }
  }

    
  $characteristics_data_d = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Диаметр хвостовика (d)") {
      $characteristics_data_d = $characteristics_data_value_final[$i];
    }
  }
    
  $characteristics_data_C = '';
  for ($i = 0; $i < count($characteristics_data_title_final); $i++) {
    if ($characteristics_data_title_final[$i] === "Диаметр подшипника (C)") {
      $characteristics_data_C = $characteristics_data_value_final[$i];
    }
  }


  $characteristics_data_title_final = implode('---', $characteristics_data_title_final);
  $characteristics_data_value_final = implode('---', $characteristics_data_value_final);


  echo '<tr>';
  echo '<td class="number_product">' . $number_product . '</td>';
  echo '<td class="name_product">' . $name_product . '</td>';
  echo '<td class="url">' . $url . '</td>';
  echo '<td class="art">' . $art . '</td>';

  echo '<td class="us-product-attributes-cont">' . $characteristics_data_D . '</td>';
  echo '<td class="characteristics_data-1">' . $characteristics_data_L . '</td>';
  echo '<td class="characteristics_data-1">' . $characteristics_data_h . '</td>';
  echo '<td class="characteristics_data-1">' . $characteristics_data_Z . '</td>';
  echo '<td class="characteristics_data-1">' . $characteristics_data_Ob_Min . '</td>';
  echo '<td class="characteristics_data-1">' . $characteristics_data_Destination . '</td>';
  echo '<td class="characteristics_data-1">' . $characteristics_data_Degree . '</td>';
  echo '<td class="characteristics_data-1">' . $characteristics_data_R . '</td>';
  echo '<td class="characteristics_data-1">' . $characteristics_data_d . '</td>';
  echo '<td class="characteristics_data-1">' . $characteristics_data_C . '</td>';

  echo '<td class="us-product-attributes-cont">' . $characteristics_data_title_final . '</td>';
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
}

