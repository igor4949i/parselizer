<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/phpQuery-onefile.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$values_google_sheets = [];

$url_product_array = [];

for ($i = 232; $i < 233; $i++) {
	$url_product_array[] = 'original_html/' . ($i + 1) . '.html';
}

// parse product
for ($i = 0; $i < count($url_product_array); $i++) {
	product_parsing($url_product_array[$i], $i + 1);
}
echo_table($values_google_sheets, count($url_product_array));
// END parse product

// test Excel
echo_Excel($values_google_sheets, count($url_product_array));
// END test Excel



// parse each product
function product_parsing($url, $number_product)
{
	global $values_google_sheets;

	$html = file_get_contents($url);
	$doc = phpQuery::newDocument($html);

	$product_data = [];
	$product_data[] = $number_product;

	$product_title = $doc->find('h2.product-title')->text();
	$product_data[] = $product_title;

	$description = $doc->find('#tab-description')->html();
	$description = pq($description);
	$description->find('font')->contentsUnwrap();
	// $description->find('img')->empty();
	$description = preg_replace('/\ssrc=\".*?\"/', ' ', $description);
	$description = preg_replace('/data-savepage-currentsrc/', 'src', $description);

	$product_data[] = $description;

	$values_google_sheets[] = $product_data;
}

function echo_table($values_google_sheets, $count_product)
{
	for ($i = 0; $i < $count_product; $i++) {
		echo '<tr>';
		echo '<td class="number">' . $values_google_sheets[$i][0] . '</td>';
		echo '<td class="title">' . $values_google_sheets[$i][1] . '</td>';
		echo '<td class="desc">' . $values_google_sheets[$i][2] . '</td>';
		echo '</tr>';
	}
}

function echo_Excel($values_google_sheets, $count_product)
{
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	for ($i = 0; $i < $count_product; $i++) {
		$sheet->setCellValue('A' . $values_google_sheets[$i][0], $values_google_sheets[$i][0]);
		$sheet->setCellValue('B' . $values_google_sheets[$i][0], $values_google_sheets[$i][1]);
		$sheet->setCellValue('C' . $values_google_sheets[$i][0], $values_google_sheets[$i][2]);
	}

	$writer = new Xlsx($spreadsheet);
	$writer->save('Result_Excel_products.xlsx');

}