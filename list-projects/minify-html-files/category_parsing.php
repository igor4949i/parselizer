<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/phpQuery-onefile.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$values_google_sheets = [];
$url_product_array = [];

// Generate URL product
// parsing URL
$url_product_array = [];

$url_category_list = [
	// 'https://electro100.kyiv.ua/index.php?route=product/category&path=20_34&limit=100', // done
	// 'https://electro100.kyiv.ua/index.php?route=product/category&path=20_63&limit=100',
	// 'https://electro100.kyiv.ua/index.php?route=product/category&path=20_64&limit=100',
	// 'https://electro100.kyiv.ua/index.php?route=product/category&path=20_64&limit=100&page=2',
	// 'https://electro100.kyiv.ua/index.php?route=product/category&path=20_67&limit=100',
	// 'https://electro100.kyiv.ua/index.php?route=product/category&path=20_67&limit=100&page=2',
	// 'https://electro100.kyiv.ua/index.php?route=product/category&path=20_33&limit=100',
	// 'https://electro100.kyiv.ua/index.php?route=product/category&path=20_91&limit=100',
	// 'https://electro100.kyiv.ua/index.php?route=product/category&path=20_70&limit=100',
	'https://electro100.kyiv.ua/index.php?route=product/category&path=20_61&limit=100',
	'https://electro100.kyiv.ua/index.php?route=product/category&path=20_65&limit=100',
	'https://electro100.kyiv.ua/index.php?route=product/category&path=20_75&limit=100',
	'https://electro100.kyiv.ua/index.php?route=product/category&path=20_76&limit=100',
	// 'https://electro100.kyiv.ua/index.php!?route=product/category&path=20_77&limit=100', // done
];

// Parsing URL
for ($i = 0; $i < count($url_category_list); $i++) {
	category_parsing($url_category_list[$i]);
}

for ($i = 0; $i < count($url_product_array); $i++) {
	product_parsing($url_product_array[$i], $i + 1);
}

echo_table_URL($values_google_sheets);

echo_Excel_URL($values_google_sheets, count($url_product_array));

function category_parsing($url_category_list)
{
	global $url_product_array;

	$html_category = file_get_contents($url_category_list);
	$doc_category = phpQuery::newDocument($html_category);

	foreach ($doc_category->find('#content .product-layout .name a') as $item) {
		$item = pq($item);
		$url_product = $item->attr('href');
		array_push($url_product_array, $url_product);
	}

}

// parse each product
function product_parsing($url, $number_product)
{
	global $values_google_sheets;

	$html = file_get_contents($url);
	$doc = phpQuery::newDocument($html);

	$product_data = [];

	$number_product_title = $doc->find('head title')->text();
	$product_data[] = $number_product_title;

	$product_title = $doc->find('h2.product-title')->text();
	$product_data[] = $product_title;

	$product_url = $url;
	$product_data[] = $product_url;

	$product_id_from_url = $url;
	preg_match('/_id\=\d+/', $product_id_from_url, $product_id);
	$product_id = preg_replace('/\_id=/', '', $product_id[0]);
	// $product_id = 'https://electro100.kyiv.ua/admin/index.php?route=catalog/product/edit&user_token=tSUdyg0o7aTDwLbhjBYJPXtQbuhteDrC&product_id=' . $product_id;
	// echo $product_id;
	// $product_id = preg_replace('/&limit.*/', '', $product_id_from_url);
	$product_data[] = $product_id;

	$product_cat = $doc->find('.breadcrumb li a:eq(2)')->text();
	$product_data[] = $product_cat;

	$description = $doc->find('#tab-description')->html();
	// $description = pq($description);
	// $description->find('font')->contentsUnwrap();
	// // $description->find('img')->empty();
	// $description = preg_replace('/\ssrc=\".*?\"/', ' ', $description);
	// $description = preg_replace('/data-savepage-currentsrc/', 'src', $description);

	// Eleks start
	$description = preg_replace('/в г.\<\/span\> агазини/', '</span>в магазине', $description);
	$description = preg_replace('/в г\. агазини/', 'в магазине', $description);
	$description = preg_replace('/Клемна колодка/', 'Клеммная колодка', $description);
	$description = preg_replace('/самовидновлюваннмй защита/', 'самовозобновляемая защита', $description);
	$description = preg_replace('/мленьку английскую/', 'маленькую английскую', $description);
	$description = preg_replace('/микропцесор/', 'микропроцессор', $description);
	$description = preg_replace('/недолиом/', 'недостатком', $description);
	$description = preg_replace('/стабилизтора/', 'стабилизатора', $description);
	$description = preg_replace('/стабилизатрив/', 'стабилизаторов', $description);
	$description = preg_replace('/ магазин/', ' Магазин', $description);
	$description = preg_replace('/www\.electro100\.kyiv\.ua/', ' www.electro100.kyiv.ua', $description);
	$description = preg_replace('/Елекс/', 'Элекс', $description);
	$description = preg_replace('/Интегрировано варисторный защита/', 'Интегрировано варисторную защита', $description);
	$description = preg_replace('/Релейний/', 'Релейный', $description);
	$description = preg_replace('/гибридных виривнювачив предлагает/', 'гибридных выпрямителей предлагает', $description);
	$description = preg_replace('/Елекс Гібрид/', 'Элекс Гибрид', $description);
	$description = preg_replace('/вмикаетья/', 'включается', $description);
	$description = preg_replace('/микрхвильовкы/', 'микроволновые печи', $description);
	$description = preg_replace('/Випускаетьсявидповидно/', 'Выпускается в соответствии', $description);
	$description = preg_replace('/забезпеченняспоживачив/', 'обеспечение потребителей', $description);
	$description = preg_replace('/клемнепидключення расположенное/', 'клеммное подключение размещено', $description);
	$description = preg_replace('/контрольованипараметры/', 'контролируемые параметры', $description);
	$description = preg_replace('/индикаторавидображае/', 'индикатор отражает', $description);
	$description = preg_replace('/знаходиться/', 'находится', $description);
	$description = preg_replace('/автотрансформаторнекерування/', 'автотрансформатор управления', $description);
	$description = preg_replace('/силовийтороидальний/', 'силовой тороидальный', $description);
	$description = preg_replace('/стабилизациивихиднои/', 'стабилизации выходного', $description);
	$description = preg_replace('/интелектуальнеуправлиння/', 'интелектуального управления', $description);
	$description = preg_replace('/алюминиевогоохолоджувача/', 'алюминиевого охладителя', $description);
	$description = preg_replace('/голчастогого типа/', 'голчастого типа', $description);
	$description = preg_replace('/налаштуванняпорога/', 'настройки порога', $description);
	$description = preg_replace('/навключення/', 'на включение', $description);
	$description = preg_replace('/импульсногоджерела/', 'импульсного источника', $description);
	$description = preg_replace('/вхидноинапругы/', 'входного напряжение', $description);
	$description = preg_replace('/Використаннявисокопродуктивного/', 'Использование высокопроизводительного', $description);
	$description = preg_replace('/дизель-генераторнимиустановкамы/', 'дизель-генераторными установками', $description);
	$description = preg_replace('/створюенезначний/', 'создает незначительный', $description);
	$description = preg_replace('/вхиднийнапрузи/', 'входного напряжение', $description);
	$description = preg_replace('/сумарнапотужнисть/', 'суммарная мощность', $description);
	$description = preg_replace('/потужноси/', 'мощности', $description);
	$description = preg_replace('/байпасаабо/', 'байпаса або', $description);
	$description = preg_replace('/вихиднихваристорив/', 'выходных варисторов', $description);
	$description = preg_replace('/видключеннянавантаження/', 'отключения нагрузки', $description);
	$description = preg_replace('/зниженоиабо/', 'пониженного или', $description);
	$description = preg_replace('/характеристикоюелектромагнитного защиты/', 'характеристики электромагнитной защиты', $description);
	$description = preg_replace('/квартирина/', 'квартиры, на', $description);
	$description = preg_replace('/иЭлекс/', 'и Элекс', $description);
	$description = preg_replace('/из самых мощных/', 'из самых мощных', $description);
	$description = preg_replace('/частинипередбачени/', 'части предусмотрены', $description);
	$description = preg_replace('/вартовикористовуваты/', 'следует использовать', $description);
	$description = preg_replace('/бувправильно/', 'был правильно', $description);
	$description = preg_replace('/вибирайтеправильний/', 'выбирайте правильный', $description);
	$description = preg_replace('/мистпрацюе/', 'городов работает', $description);
	$description = preg_replace('/темпераратура/', 'температура', $description);
	$description = preg_replace('/нуляградиусив/', 'ноль градусов', $description);
	$description = preg_replace('/автотрансформаторнекерування/', 'автотрансформаторное управления', $description);
	$description = preg_replace('/силовийтороидальний/', 'силовой тороидальный', $description);
	$description = preg_replace('/матимеще/', 'будет еще иметь', $description);
	$description = preg_replace('/потужноси/', 'мощности', $description);
	$description = preg_replace('/такожознайомтесь/', 'также ознакомьтесь', $description);
	$description = preg_replace('/квартири.Якщо/', 'квартиры. Если', $description);
	$description = preg_replace('/чудовопрацюватиме/', 'прекрасно работать', $description);
	$description = preg_replace('/щостабилизатор/', 'что стабилизатор', $description);
	$description = preg_replace('/вКиеви/', 'в Киеве', $description);
	$description = preg_replace('/Детальнуинформацию/', 'Подробную информацию', $description);
	$description = preg_replace('/моделиЭлекс/', 'модели Элекс', $description);
	$description = preg_replace('/напургы/', 'напряжения', $description);
	$description = preg_replace('/ ЭЛЕКС/', ' Элекс', $description);
	$description = preg_replace('/т \<span\>рифазний стабилизатор/', '<span> трифазный стабилизатор', $description);
	$description = preg_replace('/при помощи/', ' за помощью', $description);
	$description = preg_replace('/electro100.kyiv.ua\<\/a\>/', ' electro100.kyiv.ua</a>', $description);
	$description = preg_replace('/° C/', '°C', $description);
	$description = preg_replace('/т рифазний/', 'трифазный', $description);
	$description = preg_replace('/АВТОТРАНСФОРМАТОРНОЕ/', 'Автотрансформаторное', $description);
	$description = preg_replace('/\. Магазин/', '. Магазин&nbsp;', $description);
	$description = preg_replace('/цеоднофазний/', 'это однофазный', $description);
	$description = preg_replace('/установление задержки/', 'установлены задержки', $description);
	$description = preg_replace('/резюме/', 'Резюме', $description);
	$description = preg_replace('/по телефону/', 'по телефону', $description);
	$description = preg_replace('/интелектуального управления/', 'интеллектуального управления', $description);
	$description = preg_replace('/Амперсмиливо/', 'Ампер смело', $description);
	$description = preg_replace('/\\в Магазини/', 'в магазине&nbsp;', $description);
	$description = preg_replace('/знаходиметься/', 'будет находиться', $description);
	$description = preg_replace('/\<\/a\>/', '&nbsp;</a>', $description);
	$description = preg_replace('/Гібрид/', 'Гибрид', $description);
	$description = preg_replace('/более мощных/', 'более мощных&nbsp;', $description);
	$description = preg_replace('/сериюElex/', 'серию Элекс', $description);
	$description = preg_replace('/в Магазине/', 'в магазине', $description);
	$description = preg_replace('/в г\. \<\/span\>\<span\>агазини/', 'в магазине', $description);
	$description = preg_replace('/при входного напряжение/', 'при входном напряжении', $description);
	$description = preg_replace('/для Вашего линии/', 'для Вашей линии', $description);
	$description = preg_replace('/то стабилизатор стабилизатор/', 'то стабилизатор', $description);
	$description = preg_replace('/але сам, при этом/', '&nbsp;але сам, при этом', $description);
	$description = preg_replace('/тостабилизатор/', 'то стабилизатор', $description);
	$description = preg_replace('/при входном напрузи/', 'при входном напряжении', $description);
	$description = preg_replace('/нуля градусив/', 'нуля градусов', $description);
	$description = preg_replace('/однофазногостабилизатора/', 'однофазного стабилизатора', $description);
	$description = preg_replace('/для обеспечение/', 'для обеспечения', $description);
	$description = preg_replace('/потелефону/', 'по телефону', $description);
	$description = preg_replace('/знать о однофазный стабилизатор/', 'знать о однофазном стабилизаторе', $description);
	$description = preg_replace('/\. оставляя включены/', '. Оставляя включены', $description);
	$description = preg_replace('/периодического внешнего очистки/', 'периодической внешней очистки', $description);
	$description = preg_replace('/обязательства \"обязательств/', 'обязательств', $description);
	$description = preg_replace('/правильный Магазин\!/', 'правильный магазин!', $description);
	//  ЕЛЕКС done

	//  Укртехнологія
	$description = preg_replace('/вх.напругы/', 'вх.напряжения', $description);
	$description = preg_replace('/внутрутришних/', 'внутренних', $description);
	$description = preg_replace('/идикатор/', 'индикатор', $description);
	$description = preg_replace('/мультиуровневых защита по току/', 'мультиуровневою защитою по току', $description);
	$description = preg_replace('/вимкунты/', 'выключить', $description);
	$description = preg_replace('/\<span\>тиристо \<\/span\>\<span\>рН\<span\> ого/', 'тиристорного', $description);
	$description = preg_replace('/\<span\>тиристо \<\/span\>\<span\>рный/', 'тиристорный', $description);
	$description = preg_replace('/перемкинить/', 'переключите', $description);
	$description = preg_replace('/зменшеться/', 'уменьшится', $description);
	$description = preg_replace('/вимкеться/', 'выключится', $description);
	$description = preg_replace('/вибе входной/', 'выбьет входной', $description);
	$description = preg_replace('/ввимкненняння/', 'выключения', $description);
	$description = preg_replace('/навартаження/', 'нагрузка', $description);
	$description = preg_replace('/допустимую навантажння/', 'допустимую нагрузку', $description);
	$description = preg_replace('/тиристо рного/', 'тиристорного', $description);
	$description = preg_replace('/апаратуруы/', 'аппаратуры', $description);
	$description = preg_replace('/для будники/', 'для будинку', $description);
	//  Укртехнологія DONE

	// Volter
	$description = preg_replace('/Коеф.кориснои действия/', 'Коэф. полезного действия', $description);
	$description = preg_replace('/Напольний/', 'Напольный', $description);
	$description = preg_replace('/вхидноги напряжения/', 'входного напряжения', $description);
	$description = preg_replace('/детельно/', 'подробно', $description);
	$description = preg_replace('/догостоящего/', 'долгостоящего', $description);
	$description = preg_replace('/запоасом/', 'запасом', $description);
	$description = preg_replace('/клемна колодка/', 'клеммная колодка', $description);
	$description = preg_replace('/стабилизоры/', 'стабилизаторы', $description);
	$description = preg_replace('/сушильн на/', 'сушильна', $description);
	$description = preg_replace('/Номинальная вых. напряжение/', 'Номинальное вых. напряжение', $description);
	$description = preg_replace('/Выходная напругаx/', 'Выходное напряжение', $description);
	// Volter DONE

	// Диан, лоренз, лвт

	$description = preg_replace('/Машная/', 'машин', $description);
	$description = preg_replace('/напряжениев/', 'напряжение в', $description);
	$description = preg_replace('/потибно/', 'нужно', $description);
	$description = preg_replace('/приборовс/', 'приборов с', $description);
	$description = preg_replace('/присторив/', 'устройств', $description);
	$description = preg_replace('/присторю/', 'устройства', $description);
	$description = preg_replace('/стабилизатр/', 'стабилизатор', $description);
	$description = preg_replace('/увикнуты/', 'включить', $description);
	$description = preg_replace('/фнформативному/', 'информативному', $description);
// Диан, лоренз, лвт DONE

//
	$description = preg_replace('/ввикнення/', 'включение', $description);
	$description = preg_replace('/конкрета/', 'конкретное', $description);
	$description = preg_replace('/меншеч/', 'меньше чем', $description);
	$description = preg_replace('/ривян/', 'уровня', $description);
	$description = preg_replace('/стабизацию/', 'стабилизация', $description);
	$description = preg_replace('/полном навантажженни/', 'полной нагрузке', $description);
	$description = preg_replace('/характеристникамы/', 'характеристиками', $description);
	$description = preg_replace('/° С/', '°С', $description);
	$description = preg_replace('/конкрета напряжение/', 'конкретном напряжение', $description);
	$description = preg_replace('/полного навантажження/', 'полной нагрузки', $description);
	$description = preg_replace('/знаходятся/', 'находятся', $description);
	$description = preg_replace('/одного ступення/', 'одной ступени', $description);
	$description = preg_replace('/мощное трфазне нагрузки/', 'мощная трехфазная нагрузка', $description);
	$description = preg_replace('/Пристрий/', 'Устройство', $description);
	$description = preg_replace('/Однофазний/', 'Однофазный', $description);
	$description = preg_replace('/автотрансформаторм/', 'автотрансформатором', $description);
	$description = preg_replace('/дорогущие/', 'дорогой', $description);
	$description = preg_replace('/забалансовани/', 'сбалансировано', $description);
	$description = preg_replace('/захситу бытовой/', 'защите бытовой', $description);
	$description = preg_replace('/минимальоно/', 'минимальноє', $description);
	$description = preg_replace('/к \<span\>омпании/', 'компании', $description);
	$description = preg_replace('/ткож/', 'также', $description);
	$description = preg_replace('/електомережи/', 'электросети', $description);
	$description = preg_replace('/тиступинчасту/', 'тиступенчатую', $description);
	$description = preg_replace('/видностно/', 'относительно', $description);
	$description = preg_replace('/диспелей/', 'дисплей', $description);
	$description = preg_replace('/перегораннi/', 'перегорании', $description);
	$description = preg_replace('/поВольток/', 'по Вольток', $description);
	$description = preg_replace('/стабилиазтора/', 'стабилизатора', $description);
	$description = preg_replace('/стабилизаи/', 'стабилизации', $description);
	$description = preg_replace('/украинького/', 'украинского', $description);
	$description = preg_replace('/инверторного/', 'инверторного', $description);
	$description = preg_replace('/трасформаторного/', 'трансформаторного', $description);

	$description = trim($description);
	$product_data[] = $description;

	$values_google_sheets[] = $product_data;
}

function echo_table_URL($values_google_sheets)
{
	usort($values_google_sheets, function ($a, $b) {
		return ($a[0] - $b[0]);
	});

	for ($i = 0; $i < count($values_google_sheets); $i++) {
		echo '<tr>';
		// echo '<td class="number_title">' . $values_google_sheets[$i][0] . '</td>';
		echo '<td class="meta_title">' . $values_google_sheets[$i][1] . '</td>';
		// echo '<td class="url">' . $values_google_sheets[$i][2] . '</td>';
		echo '<td class="id">' . $values_google_sheets[$i][3] . '</td>';
		// echo '<td class="cat">' . $values_google_sheets[$i][4] . '</td>';
		echo '<td class="desc">' . $values_google_sheets[$i][5] . '</td>';
		echo '</tr>';
	}
}

function echo_Excel_URL($values_google_sheets, $count_product)
{
	usort($values_google_sheets, function ($a, $b) {
		return ($a[0] - $b[0]);
	});

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	for ($i = 0; $i < $count_product; $i++) {
		$sheet->setCellValue('A' . ($i + 1), $values_google_sheets[$i][0]);
		$sheet->setCellValue('B' . ($i + 1), $values_google_sheets[$i][1]);
		$sheet->setCellValue('C' . ($i + 1), $values_google_sheets[$i][2]);
		$sheet->setCellValue('D' . ($i + 1), $values_google_sheets[$i][3]);
		$sheet->setCellValue('E' . ($i + 1), $values_google_sheets[$i][4]);
		$sheet->setCellValue('F' . ($i + 1), $values_google_sheets[$i][5]);
	}

	$writer = new Xlsx($spreadsheet);
	$writer->save('Dian-lvt-lorenz_Additional_Excel.xlsx');

}

// // END Generate URL product
