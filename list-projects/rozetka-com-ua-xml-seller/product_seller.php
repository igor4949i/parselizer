<?php
set_time_limit(0);
require './PHPDebug.php';
require './phpQuery-onefile.php';

// $language = 'ua';
$language = 'ru';

// get data from MAIN.JS -- $.post
$url_products_seller = $_POST['parser_data'];

////
//// Створення ссилок на категорії
$result_url_cat = parse_sidebar_categories($url_products_seller);

function parse_sidebar_categories($url_products_seller)
{
	$url_cat = $url_products_seller;
	$ch2 = curl_init($url_cat);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
// curl_setopt($ch2, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt'); // создает куки
	curl_setopt($ch2, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt'); //  использует куки
	$result = curl_exec($ch2);
	curl_close($ch2);

	$doc = phpQuery::newDocument($result);
	$list_c = $doc->find('.sidebar .categories-filter__list.categories-filter__main > .categories-filter__item > a');

	$urls_cat = [];
	foreach ($list_c as $item_name) {
		$item_name = pq($item_name);
		$item_name = $item_name->attr('href');
		if ($item_name != '') {
			$urls_cat[] = 'https://rozetka.com.ua' . $item_name;
		}
	}

	return $urls_cat;
}
////
////

////
//// Собрать все ссилки на товари
$url_products_list = [];
for ($i = 0; $i < 1; $i++) { // count($result_url_cat)
	parse_url_products_list($result_url_cat[$i]);
}

function parse_url_products_list($url_cat)
{
	global $url_products_list;
	$url_category = $url_cat;

	$ch2 = curl_init($url_category);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
// curl_setopt($ch2, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt'); // создает куки
	curl_setopt($ch2, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt'); //  использует куки
	$result_cat = curl_exec($ch2);
	curl_close($ch2);

	$result = phpQuery::newDocument($result_cat);

	$page_count_label = $result->find('.catalog-selection .catalog-selection__label')->html();
	$page_match = preg_match('/\d+/', $page_count_label, $matches, PREG_OFFSET_CAPTURE);
	$product_count_cat = $matches[0][0];

	$pages_count = ceil($product_count_cat / 36);

	$id_cat_match = preg_match('/id=\d+/', $url_category, $matches_id, PREG_OFFSET_CAPTURE);
	$id_cat = preg_replace('/id=/', '', $matches_id[0][0]);

	$list_pages_url_on_category = [];

	$url_category_first = $url_category;
	$list_pages_url_on_category[] = $url_category_first;

	if ($pages_count > 1) {
		for ($i = 2; $i <= $pages_count; $i++) {
			$temp_url = 'https://rozetka.com.ua/seller/ssshop/goods/?page=' . $i . '&section_id=' . $id_cat;
			$list_pages_url_on_category[] = $temp_url;
		}
	}

	for ($i = 0; $i < count($list_pages_url_on_category); $i++) { // count($list_pages_url_on_category)

		$ch2 = curl_init($list_pages_url_on_category[$i]);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
		// curl_setopt($ch2, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt'); // создает куки
		curl_setopt($ch2, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt'); //  использует куки
		$result_categ = curl_exec($ch2);
		curl_close($ch2);

		$doc_category = phpQuery::newDocument($result_categ);
		// var_dump($doc_category);
		foreach ($doc_category->find('li a.goods-tile__heading') as $item) {
			$item = pq($item);
			$url_product = $item->attr('href');
			$url_products_list[] = $url_product;
		}
	}
}


for ($i=0; $i < count($url_products_list); $i++) { 
	echo $url_products_list[$i] . '</br>';
}
//
//





////
//// Парсинг каждого товара
for ($i = 0; $i < count($url_products_list); $i++) { // count($url_products_list)
	// parsing_product($url_products_list[$i]);
	// echo $url_products_list[$i] . '</br>';
}

$xml = '';

function parsing_product($url_product)
{
	global $xml;

	$characteristics = get_characteristics($url_product);
	$doc = phpQuery::newDocument($characteristics);
	$characteristics_full__group = $doc->find('.characteristics-full__group')->html();
	$characteristics_full__group = pq($characteristics_full__group);
	$characteristics_name = [];
	$characteristics_value = [];
	$characteristics_block_name = $characteristics_full__group->find('.characteristics-full__label');
	$characteristics_block_value = $characteristics_full__group->find('.characteristics-full__value');

	foreach ($characteristics_block_name as $item_name) {
		$item_name = pq($item_name);
		$item_name = trim($item_name->text());
		$characteristics_name[] = $item_name;
	}
	foreach ($characteristics_block_value as $item_value) {
		$item_value = pq($item_value);

		if (trim($item_value->find('li a')->html())) {
			$item_value = $item_value->find('li')->html();

			preg_match_all("/<a.*?<\/a>/", $item_value, $matches, PREG_OFFSET_CAPTURE);
			if (count($matches[0]) > 1) {
				$final_matches_arr = [];
				for ($i = 0; $i < count($matches[0]); $i++) {
					$matches[0][$i][0] = preg_replace('/<\/a>/', '', $matches[0][$i][0]);
					$matches[0][$i][0] = preg_replace('/\<a.+?\>/', '', $matches[0][$i][0]);

					$final_matches_arr[] = $matches[0][$i][0];
				}
				$final_str = implode('|', $final_matches_arr);
				$characteristics_value[] = $final_str;
			} else {
				$matches[0][0][0] = preg_replace('/<\/a>/', '', $matches[0][0][0]);
				$matches[0][0][0] = preg_replace('/\<a.+?\>/', '', $matches[0][0][0]);
				$final_str = '' . $matches[0][0][0];
				$characteristics_value[] = $final_str;
			}
		} else {
			$item_value = trim($item_value->find('li span')->text());
			$characteristics_value[] = $item_value;
		}
	}

	$parser_data_details = product_details($url_product, $language);
	$product_details = json_decode($parser_data_details, true)['data'][0];

	$offer_id = $product_details['id'];
	// echo $offer_id . '</br>';
	// echo 'test' . '</br>';
}

