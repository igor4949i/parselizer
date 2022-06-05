<?php
set_time_limit(0);
require __DIR__ . '/vendor/autoload.php';
require './PHPDebug.php';
require './phpQuery-onefile.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// get data from MAIN.JS -- $.post
$url_admin = $_POST['parser_data'];

$excel_data = [];

// create list URLs products
$data_url = ["https://mdth-ua.com/wp-admin/term.php?taxonomy=product_cat&tag_ID=19&post_type=product&wp_http_referer=%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dproduct_cat%26post_type%3Dproduct%26s%26lang%3Dru"];

for ($i = 0; $i < count($data_url); $i++) { // count($data_url)
	parseAdminCat($data_url[$i], $i);
}

function parseAdminCat($url_admin_cat, $num)
{
	global $excel_data;
	$htmlAdminWordpress = getAllProducts($url_admin_cat);
	$doc = phpQuery::newDocument($htmlAdminWordpress);

	$num_cat = $num;
	$name_cat_UA = $doc->find('#tr_lang_uk')->attr('value');
	$edit_cat_UA = $doc->find('.pll-edit-column .pll_icon_edit')->attr('href');
	$img_cat = $doc->find('#product_cat_thumbnail img')->attr('src');

	// $temp_data[] = $num_cat;
	$temp_data[] = $name_cat_UA;
	$temp_data[] = $edit_cat_UA;
	$temp_data[] = $img_cat;

	$excel_data[] = $temp_data;
}


function getAllProducts($url_product)
{

	$ch2 = curl_init($url_product);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
	// curl_setopt($ch2, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt'); // создает куки
	curl_setopt($ch2, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt'); //  использует куки
	$result_cart = curl_exec($ch2);
	curl_close($ch2);
	return $result_cart;
}


// for ($j = 1; $j <= count($excel_data); $j++) {
// 	echo_Excel($contetUAarr, $j);
// }

echo_Excel($excel_data);

function echo_Excel($excel_data)
{
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	for ($i = 0; $i < count($excel_data); $i++) { // count($excel_data)
		$line = $i + 2;
		$sheet->setCellValue('A' . $line, $excel_data[$i][0]);
		$sheet->setCellValue('B' . $line, $excel_data[$i][1]);
		$sheet->setCellValue('C' . $line, $excel_data[$i][2]);
	}

	$writer = new Xlsx($spreadsheet);
	$writer->save('./Excel/Categories_Url_Images.xlsx');
}
