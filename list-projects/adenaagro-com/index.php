<?php

require 'phpQuery-onefile.php';
set_time_limit(0);
require_once "PHPDebug.php";
$debug = new PHPDebug();
echo $debug->debug("Start crawling");
$start = microtime(true);

// 0) create all html doc from local URL
for ($i = 1162; $i <= 1162; $i++) {
	${'url_' . $i} = 'Downloads/Адміністрування(' . $i . ').html';
	${'html_' . $i} = file_get_contents(${'url_' . $i});
	${'doc_' . $i} = phpQuery::newDocument(${'html_' . $i});
}

echo $debug->debug('step 0 | create documents | Time: ' . (microtime(true) - $start) . ' секунд');

// 1) Name RU
// 2) Name UA
for ($i = 1162; $i <= 1162; $i++) {
	${'name_ru_' . $i} = ${'doc_' . $i}->find('input[name="name_ru"]')->attr('value');
	${'name_ua_' . $i} = ${'doc_' . $i}->find('input[name="name_ua"]')->attr('value');
}
echo $debug->debug('step 1 | Time: ' . (microtime(true) - $start) . ' секунд');

// 3) visible
for ($i = 1162; $i <= 1162; $i++) {
	${'visible_check_' . $i} = ${'doc_' . $i}->find('#saveform input[name="visible"]');

	if (${'visible_check_' . $i}->attr('checked')) {
		${'visible_' . $i} = 'visible';
	} else {
		${'visible_' . $i} = 'hidden';
	}
}
echo $debug->debug('step 3 | Visible | Time: ' . (microtime(true) - $start) . ' секунд');

// 4) get categories products
for ($i = 1162; $i <= 1162; $i++) {
	${'menu_' . $i} = ${'doc_' . $i}->find('select[name="__categoryid"] option[selected="selected"]')->attr('value');

	switch (${'menu_' . $i}) {
		case '0':
			${'category_' . $i} = 'Немає';
			${'category_child_' . $i} = '';
			break;
		case '2':
			${'category_' . $i} = 'Техника Б/У';
			${'category_child_' . $i} = '';
			break;
		case '10':
			${'category_' . $i} = 'Техника Б/У';
			${'category_child_' . $i} = 'Обработка почвы';
			break;
		case '11':
			${'category_' . $i} = 'Техника Б/У';
			${'category_child_' . $i} = 'Посадка, посев';
			break;
		case '13':
			${'category_' . $i} = 'Техника Б/У';
			${'category_child_' . $i} = 'Уборка';
			break;
		case '14':
			${'category_' . $i} = 'Техника Б/У';
			${'category_child_' . $i} = 'Складское оборудование';
			break;
		case '15':
			${'category_' . $i} = 'Техника Б/У';
			${'category_child_' . $i} = 'Упаковка';
			break;
		case '12':
			${'category_' . $i} = 'Техника Б/У';
			${'category_child_' . $i} = 'Приемные и горизонтальные конвейеры';
			break;
		case '1':
			${'category_' . $i} = 'Новая техника';
			${'category_child_' . $i} = '';
			break;
		case '4':
			${'category_' . $i} = 'Новая техника';
			${'category_child_' . $i} = 'Обработка почвы';
			break;
		case '5':
			${'category_' . $i} = 'Новая техника';
			${'category_child_' . $i} = 'Посадка, посев';
			break;
		case '7':
			${'category_' . $i} = 'Новая техника';
			${'category_child_' . $i} = 'Уборка';
			break;
		case '8':
			${'category_' . $i} = 'Новая техника';
			${'category_child_' . $i} = 'Складское оборудование';
			break;
		case '9':
			${'category_' . $i} = 'Новая техника';
			${'category_child_' . $i} = 'Упаковка';
			break;
		case '6':
			${'category_' . $i} = 'Новая техника';
			${'category_child_' . $i} = 'Приемные и горизонтальные конвейеры';
			break;
		case '3':
			${'category_' . $i} = 'Запчасти';
			${'category_child_' . $i} = '';
			break;
		case '32':
			${'category_' . $i} = 'Запчасти';
			${'category_child_' . $i} = 'Ролики';
			break;
		case '33':
			${'category_' . $i} = 'Запчасти';
			${'category_child_' . $i} = 'Звездочки';
			break;
		case '34':
			${'category_' . $i} = 'Запчасти';
			${'category_child_' . $i} = 'Транспортеры';
			break;
		case '35':
			${'category_' . $i} = 'Запчасти';
			${'category_child_' . $i} = 'Цепи';
			break;
		case '36':
			${'category_' . $i} = 'Запчасти';
			${'category_child_' . $i} = 'Другое';
			break;
		case '37':
			${'category_' . $i} = 'Запчасти';
			${'category_child_' . $i} = 'Ложечки';
			break;
		case '38':
			${'category_' . $i} = 'Запчасти';
			${'category_child_' . $i} = 'Мешкозашивочные машины';
			break;
		default:
			${'category_' . $i} = '';
			${'category_child_' . $i} = '';
			break;
	}
}
echo $debug->debug('step 4 | Category/Category Child| Time: ' . (microtime(true) - $start) . ' секунд');

// 5) get vendors products
for ($i = 1162; $i <= 1162; $i++) {
	${'vendor_level_array_' . $i} = ${'doc_' . $i}->find('#factoryid option[selected="selected"]');
}
for ($i = 1162; $i <= 1162; $i++) {
	// create array vendor
	${'vendor_' . $i} = [];
	foreach (${'vendor_level_array_' . $i} as $item) {
		$item = pq($item);
		$vendor_step = $item->text();
		array_push(${'vendor_' . $i}, $vendor_step);
	}
}
echo $debug->debug('step 5 | Vendor | Time: ' . (microtime(true) - $start) . ' секунд');

// 6) get SKU
for ($i = 1162; $i <= 1162; $i++) {
	${'sku_' . $i} = ${'doc_' . $i}->find('.price input[name="id"]')->attr('value');
}
echo $debug->debug('step 6 | SKU | Time: ' . (microtime(true) - $start) . ' секунд');

// 7) get price
for ($i = 1162; $i <= 1162; $i++) {
	${'price_' . $i} = ${'doc_' . $i}->find('.price input[name="price"]')->attr('value');
}
echo $debug->debug('step 7 | Price | Time: ' . (microtime(true) - $start) . ' секунд');

// 8) in stock
for ($i = 1162; $i <= 1162; $i++) {
	${'instock_check_' . $i} = ${'doc_' . $i}->find('.price input[name="in_stock"]');

	if (${'instock_check_' . $i}->attr('checked')) {
		${'instock_' . $i} = 'yes';
	} else {
		${'instock_' . $i} = 'no';
	}
}
echo $debug->debug('step 8 | Stock | Time: ' . (microtime(true) - $start) . ' секунд');

// 9) sold
for ($i = 1162; $i <= 1162; $i++) {
	${'sold_check_' . $i} = ${'doc_' . $i}->find('.price input[name="sold"]');

	if (${'sold_check_' . $i}->attr('checked')) {
		${'sold_' . $i} = 'yes';
	} else {
		${'sold_' . $i} = 'no';
	}
}
echo $debug->debug('step 9 | Sold | Time: ' . (microtime(true) - $start) . ' секунд');

// 10) is new
for ($i = 1162; $i <= 1162; $i++) {
	${'is_new_check_' . $i} = ${'doc_' . $i}->find('.price input[name="is_new"]');
	if (${'is_new_check_' . $i}->attr('checked')) {
		${'is_new_' . $i} = 'yes';
	} else {
		${'is_new_' . $i} = 'no';
	}
}
echo $debug->debug('step 9 | Sold | Time: ' . (microtime(true) - $start) . ' секунд');

// 11) link
for ($i = 1162; $i <= 1162; $i++) {
	${'link_' . $i} = ${'doc_' . $i}->find('#elink')->attr('value');
}
echo $debug->debug('step 11 | Link | Time: ' . (microtime(true) - $start) . ' секунд');

// 12) year
for ($i = 1162; $i <= 1162; $i++) {
	${'year_' . $i} = ${'doc_' . $i}->find('select[name="year"] option[selected="selected"]')->text();
}
echo $debug->debug('step 12 | Year | Time: ' . (microtime(true) - $start) . ' секунд');

// 13) keyword_ru
for ($i = 1162; $i <= 1162; $i++) {
	${'keyword_ru_' . $i} = ${'doc_' . $i}->find('input[name="keyword_ru"]')->attr('value');
}
echo $debug->debug('step 13 | keywords ru | Time: ' . (microtime(true) - $start) . ' секунд');

// 14) keyword_ua
for ($i = 1162; $i <= 1162; $i++) {
	${'keyword_ua_' . $i} = ${'doc_' . $i}->find('input[name="keyword_ua"]')->attr('value');
}
echo $debug->debug('step 14 | keyword ua | Time: ' . (microtime(true) - $start) . ' секунд');

// 15) images_url_1 --- html list tag A - URL images
for ($i = 1162; $i <= 1162; $i++) {
	${'images_url_' . $i} = ${'doc_' . $i}->find('.product-images a[rel="images"]');
}
echo $debug->debug('step 15 | image | Time: ' . (microtime(true) - $start) . ' секунд');

// get array attributes href
for ($i = 1162; $i <= 1162; $i++) {
	// create array images href
	${'result_url_array_' . $i} = [];
	foreach (${'images_url_' . $i} as $item) {
		$item = pq($item);
		$images_step_url = $item->attr('href');
		array_push(${'result_url_array_' . $i}, $images_step_url);
	}

	// 16) main_image_href_1 --- get main image href
	${'main_image_href_' . $i} = ${'result_url_array_' . $i}[0];

	// $additional_image_href_1 --- data STRING - get string additional images href
	if (count(${'result_url_array_' . $i}) == 2) {
		${'additional_image_href_' . $i} = ${'result_url_array_' . $i}[1];
	}

	// if count url>2 => add urls to array
	if (count(${'result_url_array_' . $i}) > 2) {
		${'additional_image_href_' . $i} = '' . ${'result_url_array_' . $i}[1];
		for ($x = 2; $x < count(${'result_url_array_' . $i}); $x++) {
			${'additional_image_href_' . $i} .= ',' . ${'result_url_array_' . $i}[$x];
		}
	}
}
echo $debug->debug('step 16 | add image | Time: ' . (microtime(true) - $start) . ' секунд');

// 17 video
for ($i = 1162; $i <= 1162; $i++) {
	${'video_' . $i} = ${'doc_' . $i}->find('#video')->attr('value');
}
echo $debug->debug('step 17 | video | Time: ' . (microtime(true) - $start) . ' секунд');

// 18) shorttext_ru
for ($i = 1162; $i <= 1162; $i++) {
	${'shorttext_ru_' . $i} = ${'doc_' . $i}->find('#shorttext_ru')->html();
}
// 19) text_ru
for ($i = 1162; $i <= 1162; $i++) {
	${'text_ru_' . $i} = ${'doc_' . $i}->find('#text_ru')->html();
}
// 20) shorttext_ua
for ($i = 1162; $i <= 1162; $i++) {
	${'shorttext_ua_' . $i} = ${'doc_' . $i}->find('#shorttext_ua')->html();
}
// 21) text_ua
for ($i = 1162; $i <= 1162; $i++) {
	${'text_ua_' . $i} = ${'doc_' . $i}->find('#text_ua')->html();
}
echo $debug->debug('step 21 | description | Time: ' . (microtime(true) - $start) . ' секунд');

// 21) characteristics
for ($i = 1162; $i <= 1162; $i++) {
	${'characteristics_' . $i} = ${'doc_' . $i}->find('#props1');

}

// 22) manager
for ($i = 1162; $i <= 1162; $i++) {
	${'manager_' . $i} = ${'doc_' . $i}->find('#par_it_man');
}

echo '<table>';
echo '<tr class="title">';
echo '<th class="name_ru">' . '№' . '</th>';
echo '<th class="name_ru">' . 'Name RU' . '</th>';
echo '<th class="name_ua">' . 'Name UA' . '</th>';
echo '<th class="visible">' . 'Visible' . '</th>';
echo '<th class="category">' . 'Category' . '</th>';
echo '<th class="category-child">' . 'Category Child' . '</th>';
echo '<th class="vendor-level-1">' . 'Vendor - level 0' . '</th>';
echo '<th class="vendor-level-2">' . 'Vendor - level 1' . '</th>';
echo '<th class="vendor-level-3">' . 'Vendor - level 2' . '</th>';
echo '<th class="sku">' . 'SKU' . '</th>';
echo '<th class="price">' . 'Price' . '</th>';
echo '<th class="in-stock">' . 'In stock' . '</th>';
echo '<th class="sold">' . 'Sold' . '</th>';
echo '<th class="is-new">' . 'Is new' . '</th>';
echo '<th class="link">' . 'URL' . '</th>';
echo '<th class="year">' . 'Year' . '</th>';
echo '<th class="keyword_ru">' . 'keyword_ru' . '</th>';
echo '<th class="keyword_ua">' . 'keyword_ua' . '</th>';
echo '<th class="main-image">' . 'main_image_href' . '</th>';
echo '<th class="additional-images">' . 'additional_image_href' . '</th>';
echo '<th class="video">' . 'Video' . '</th>';
echo '<th class="shorttext_ru">' . 'shorttext_ru' . '</th>';
echo '<th class="text_ru">' . 'text_ru' . '</th>';
echo '<th class="shorttext_ua">' . 'shorttext_ua' . '</th>';
echo '<th class="text_ua">' . 'text_ua' . '</th>';
echo '</tr>';

for ($i = 1162; $i <= 1162; $i++) {
	echo '<tr class="url-' . $i . '">';
	echo '<td class="name-ru">' . $i . '</td>';
	echo '<td class="name-ru">' . ${'name_ru_' . $i} . '</td>';
	echo '<td class="name-ua">' . ${'name_ua_' . $i} . '</td>';
	echo '<td class="visible">' . ${'visible_' . $i} . '</td>';
	echo '<td class="category">' . ${'category_' . $i} . '</td>';
	echo '<td class="category-child">' . ${'category_child_' . $i} . '</td>';
	echo '<td class="vendor-level-1">' . ${'vendor_' . $i}[0] . '</td>';
	echo '<td class="vendor-level-2">' . ${'vendor_' . $i}[1] . '</td>';
	echo '<td class="vendor-level-3">' . ${'vendor_' . $i}[2] . '</td>';
	echo '<td class="sku">' . ${'sku_' . $i} . '</td>';
	echo '<td class="price">' . ${'price_' . $i} . '</td>';
	echo '<td class="in-stock">' . ${'instock_' . $i} . '</td>';
	echo '<td class="sold">' . ${'sold_' . $i} . '</td>';
	echo '<td class="is-new">' . ${'is_new_' . $i} . '</td>';
	echo '<td class="link">' . ${'link_' . $i} . '</td>';
	echo '<td class="year">' . ${'year_' . $i} . '</td>';
	echo '<td class="keyword_ru">' . ${'keyword_ru_' . $i} . '</td>';
	echo '<td class="keyword_ua">' . ${'keyword_ua_' . $i} . '</td>';
	echo '<td class="main-image">' . ${'main_image_href_' . $i} . '</td>';
	echo '<td class="additional-images">' . ${'additional_image_href_' . $i} . '</td>';
	echo '<td class="video">' . ${'video_' . $i} . '</td>';
	echo '<td class="shorttext_ru">' . ${'shorttext_ru_' . $i} . '</td>';
	echo '<td class="text_ru">' . ${'text_ru_' . $i} . '</td>';
	echo '<td class="shorttext_ua">' . ${'shorttext_ua_' . $i} . '</td>';
	echo '<td class="text_ua">' . ${'text_ua_' . $i} . '</td>';
	echo '</tr>';
}

echo '</table>';

// time working php
echo $debug->debug('Time crawling all products: ' . (microtime(true) - $start) . ' секунд');