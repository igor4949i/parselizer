<?php

require 'phpQuery-onefile.php';
set_time_limit(0);
require_once "PHPDebug.php";
$debug = new PHPDebug();
echo $debug->debug("Start crawling");
$start = microtime(true);
echo '<link rel="stylesheet" href="./style.css">';

// 0) create all html doc from local URL
for ($i = 1; $i <= 4; $i++) {
	${'url_' . $i} = 'html/product (' . $i . ').html';
	${'html_' . $i} = file_get_contents(${'url_' . $i});
	${'doc_' . $i} = phpQuery::newDocument(${'html_' . $i});
}

// column - SKU
for ($i = 1; $i <= 4; $i++) {
	${'sku_' . $i} = ${'doc_' . $i}->find('.product-intro__title span:last')->html();
}

// column - category
for ($i = 1; $i <= 4; $i++) {
	${'category_' . $i} = ${'doc_' . $i}->find('.breadcrumbs__item a:last')->html();
}

// column - Name
for ($i = 1; $i <= 4; $i++) {
	${'remove_sku_' . $i} = ${'doc_' . $i}->find('.product-intro__title span')->remove();
	${'name_' . $i} = ${'doc_' . $i}->find('.product-intro__title')->text();
	${'name_' . $i} = trim(${'name_' . $i});
}

// column - Price
for ($i = 1; $i <= 4; $i++) {
	${'price_' . $i} = ${'doc_' . $i}->find('.product-price .product-price__inner:first .product-price__main-value')->text();
}

// column - Images Feature
for ($i = 1; $i <= 4; $i++) {
	${'images_feature_' . $i} = [];

	// 1 - main image
	${'result_main_image_' . $i} = ${'doc_' . $i}->find('.product-photo__item-wrapper a.product-photo__item')->attr('href');
	${'result_main_image_' . $i} = preg_replace('/large/', 'origin', ${'result_main_image_' . $i});
	array_push(${'images_feature_' . $i}, ${'result_main_image_' . $i});

	// 2 - additional images
	if (${'images_url_' . $i} = ${'doc_' . $i}->find('.product-photo__thumbs li a')) {
		foreach (${'images_url_' . $i} as $item) {
			$item = pq($item);
			$images_step_url = $item->attr('href');
			array_push(${'images_feature_' . $i}, $images_step_url);
		}
	}

	// 3 - color images
	${'color_array_' . $i} = ${'doc_' . $i}->find('.product-intro__section .variants-color__list label input');
	foreach (${'color_array_' . $i} as $item) {
		$item = pq($item);
		$color_step_url = $item->attr('data-product-variant--photo-link');
		array_push(${'images_feature_' . $i}, $color_step_url);
	}

	${'images_feature_' . $i} = array_unique(${'images_feature_' . $i});
	${'images_feature_' . $i} = implode(',', ${'images_feature_' . $i});
}

// column - VAR Color Name
for ($i = 1; $i <= 4; $i++) {
	${'color_name_array_' . $i} = ${'doc_' . $i}->find('.product-intro__section .variants-color__list label');
	${'color_name_' . $i} = [];
	foreach (${'color_name_array_' . $i} as $item) {
		$item = pq($item);
		$color_step_url = $item->attr('title');
		array_push(${'color_name_' . $i}, $color_step_url);
	}
	${'color_name_' . $i} = implode('|', ${'color_name_' . $i});
	${'color_name_' . $i} = mb_strtolower(${'color_name_' . $i}, 'UTF-8');
}

// column - VAR Size
for ($i = 1; $i <= 4; $i++) {
	${'size_' . $i} = [];
	${'size_array_' . $i} = ${'doc_' . $i}->find('.product-intro__row.product-intro__row--lg select option');
	foreach (${'size_array_' . $i} as $item) {
		$item = pq($item);
		$size_step = $item->text();
		$size_step = trim($size_step);
		$size_step = preg_replace('/\(Нет в наличии\)/', '', $size_step);
		$size_step = strtr($size_step, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
		$size_step = trim($size_step, chr(0xC2) . chr(0xA0)); // trim &nbsp
		$size_step = trim($size_step);
		if (strpos($size_step, "разные") !== 0) { //substring is in start position
			array_push(${'size_' . $i}, $size_step);

		}
	}

	${'size_' . $i} = array_unique(${'size_' . $i});
	${'size_' . $i} = implode('|', ${'size_' . $i});
}

// column - Images
for ($i = 1; $i <= 4; $i++) {
	${'result_main_image_' . $i} = ${'doc_' . $i}->find('.product-photo__item-wrapper a.product-photo__item')->attr('href');
	${'result_main_image_' . $i} = preg_replace('/large/', 'origin', ${'result_main_image_' . $i});

	${'result_url_array_' . $i} = [];

	if (${'images_url_' . $i} = ${'doc_' . $i}->find('.product-photo__thumbs li a')) {
		foreach (${'images_url_' . $i} as $item) {
			$item = pq($item);
			$images_step_url = $item->attr('href');
			array_push(${'result_url_array_' . $i}, $images_step_url);
		}
		${'result_url_array_' . $i} = implode(',', ${'result_url_array_' . $i});
	}
}

// column - Color
for ($i = 1; $i <= 4; $i++) {
	${'color_array_' . $i} = ${'doc_' . $i}->find('.product-intro__section .variants-color__list label input');
	${'color_' . $i} = [];
	foreach (${'color_array_' . $i} as $item) {
		$item = pq($item);
		$color_step_url = $item->attr('data-product-variant--photo-link');
		array_push(${'color_' . $i}, $color_step_url);
	}
	${'color_' . $i} = implode(',', ${'color_' . $i});
}

echo '<table id="table2excel">';
echo '<tr class="title">';
echo '<th class="number">' . '№' . '</th>';
echo '<th class="sku">' . 'SKU' . '</th>';
echo '<th class="category">' . 'category' . '</th>';
echo '<th class="name">' . 'Name' . '</th>';
echo '<th class="price">' . 'Price' . '</th>';
echo '<th class="images_url">' . 'Images Feature' . '</th>';
echo '<th class="images_url">' . 'Main Image' . '</th>';
echo '<th class="images_url">' . 'Additional Images' . '</th>';
echo '<th class="color">' . 'Color_URL' . '</th>';
echo '<th class="color_name">' . 'VAR_Color_Name' . '</th>';
echo '<th class="color_name">' . 'VAR_Size' . '</th>';
echo '</tr>';

for ($i = 1; $i <= 4; $i++) {
	echo '<tr class="url-' . $i . '">';
	echo '<td class="number">' . $i . '</td>';
	echo '<td class="sku">' . ${'sku_' . $i} . '</td>';
	echo '<td class="category">' . ${'category_' . $i} . '</td>';
	echo '<td class="name">' . ${'name_' . $i} . '</td>';
	echo '<td class="price">' . ${'price_' . $i} . '</td>';
	echo '<td class="main_images_url">' . ${'images_feature_' . $i} . '</td>';
	echo '<td class="main_images_url">' . ${'result_main_image_' . $i} . '</td>';
	echo '<td class="images_url">' . ${'result_url_array_' . $i} . '</td>';
	echo '<td class="color_url">' . ${'color_' . $i} . '</td>';
	echo '<td class="color_name">' . ${'color_name_' . $i} . '</td>';
	echo '<td class="size_name">' . ${'size_' . $i} . '</td>';
	echo '</tr>';
}

echo '</table>';

echo '<button id="data_excel">Скачати CSV</button>';

echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>';
echo '<script src="jquery.table2excel.js"></script>';
echo '<script src="main.js"></script>';
// time working php
echo $debug->debug('Time crawling all products: ' . (microtime(true) - $start) . ' секунд');