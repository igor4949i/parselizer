<?php
set_time_limit(0);
require './PHPDebug.php';
require './phpQuery-onefile.php';

// $language = 'ua';
$language = 'ru';

// get data from MAIN.JS -- $.post
$url_product = $_POST['parser_data'];
$description_json = $_POST['parser_data_desc'];

product_parsing($url_product, $language);

// parse each product
function product_parsing($url_item, $language)
{
	$url = $url_item;

	preg_match('/\/p\d.*\//', $url, $matches_num_prod);
	$goodsId = $matches_num_prod[0];
	$goodsId = preg_replace('/(\/p)|(\/)/', '', $goodsId);

	// echo $goodsId;
	$data = [
		'front-type' => 'xl',
		'goodsId' => $goodsId,
		// 'goodsId' => '261212126',
	];

	$url_request = 'https://xl-catalog-api.rozetka.com.ua/v4/goods/getDetails?'
		. 'front-type=' . $data['front-type']
		. '&product_ids=' . $data['goodsId']
		. '&lang=' . $language;
	$ch = curl_init($url_request);
	$result = curl_exec($ch);
	curl_close($ch);
}