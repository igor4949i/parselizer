<?php
set_time_limit(0);
require './PHPDebug.php';
require './phpQuery-onefile.php';

$hosting_url = 'https://parselizer.devlviv.fun/rozetka-com-ua-xml/';

// get data from MAIN.JS -- $.post
$url_product = $_POST['parser_data'];
$description_json = $_POST['parser_data_desc'];
$parser_data_details = $_POST['parser_data_details'];

$desc = json_decode($description_json, true)['data']['text'];
$product_details = json_decode($parser_data_details, true)['data'][0];

product_parsing($url_product, $desc, $product_details, $hosting_url);

// parse each product
function product_parsing($url_product, $desc, $product_details, $hosting_url)
{
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

	$offer_id = $product_details['id'];
	$price = $product_details['price'];
	$price_old = $product_details['old_price'];
	$category_id = $product_details['category_id'];
	$name = $product_details['title'];
	$vendor = $product_details['brand'];
	$description = $desc;
	$all_images = $product_details['images']['all_images'];

	// $all_images_name_arr = [];

	// for ($i = 0; $i < count($all_images); $i++) {
	// 	// $pattern = '/http.*\//';
	// 	// $img_name[$i] = preg_replace($pattern, '', $all_images[$i]);
	// 	$all_images_name_arr[] = $img_name[$i];
	// }

	// for ($i = 0; $i < count($all_images); $i++) {
	// 	saveImg($all_images[$i], $offer_id);
	// }

	// Save - Category into FILE Categories
	$category_name = $doc->find('ul.breadcrumbs li:not(.breadcrumbs__item--last):last')->text();
	$category_xml = '<category id="' . $category_id . '">' . $category_name . '</category>';

	$file_categories = 'categories.txt';
	$categories_arr = [];

	$read = fopen($file_categories, "r") or die("can't open the file_categories");
	while (!feof($read)) {
		$line_category = trim(fgets($read));

		if ($line_category !== "\n" and $line_category !== "") {
			$categories_arr[] = $line_category;
		}
	}
	fclose($read);

	$categories_arr[] = $category_xml;
	$category_xml_result = array_unique($categories_arr);

	file_put_contents('categories.txt', '');

	$fd = fopen("categories.txt", 'w') or die("не удалось создать файл");
	for ($i = 0; $i < count($category_xml_result); $i++) {
		fwrite($fd, $category_xml_result[$i] . "\n");
	}
	fclose($fd);

	//images
	$pictures_xml = '';
	for ($i = 0; $i < count($all_images); $i++) {
		$pictures_xml = $pictures_xml . '<picture>' . $all_images[$i] . '</picture>';
	}
	$characteristics_xml = '';
	for ($i = 0; $i < count($characteristics_name); $i++) {
		$characteristics_xml = $characteristics_xml . '<param name="' . $characteristics_name[$i] . '">' . $characteristics_value[$i] . '</param>';
	}

	$xml_final = '<offer id="' . $offer_id . '" available="true">'
		. '<price>' . $price . '</price>'
		. '<price_old>' . $price_old . '</price_old>'
		. '<currencyId>UAH</currencyId>'
		. '<category_id>' . $category_id . '</category_id>'
		. $pictures_xml
		. '<name>' . $name . '</name>'
		. '<vendor>' . $vendor . '</vendor>'
		. '<description><![CDATA[' . $description . ']]></description>'
		. $characteristics_xml
		. '</offer>';

	file_put_contents('xml_products_seller.xml', $xml_final . "\n", FILE_APPEND);
	// echo $xml_final;

}

function saveImg($url, $offer_id)
{
	$img_name = preg_replace('/http.*\//', '', $url);

	if (!is_dir('images_products/' . $offer_id)) {
		mkdir('images_products/' . $offer_id, 0777, true);
	}

	// Image path
	$img_name_path = 'images_products/' . $offer_id . '/' . $img_name;

	// Save image
	$ch = curl_init($url);
	$fp = fopen($img_name_path, 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
}

function get_characteristics($url_product)
{
	$url_product_characteristics = $url_product . 'characteristics/';

	$ch2 = curl_init($url_product_characteristics);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
	// curl_setopt($ch2, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt'); // создает куки
	curl_setopt($ch2, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt'); //  использует куки
	$result_cart = curl_exec($ch2);
	curl_close($ch2);
	return $result_cart;
}
