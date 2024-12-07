<?php
set_time_limit(0);
require __DIR__ . '/vendor/autoload.php';
require './PHPDebug.php';
require './phpQuery-onefile.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$list_num = 19;
$start_ProductId = 3600;
$end_ProductId = 3840;




for ($i = $list_num; $i <= $list_num; $i++) {
	$contetUAarr = translatedPageParse($i, $start_ProductId, $end_ProductId);
}

function translatedPageParse($number, $start_ProductId, $end_ProductId)
{
	$page = './html-translated/final/' . $number . '.html';

	$html = file_get_contents($page);
	$page_parsed = phpQuery::newDocument($html);

	$contetUAarr = [];

	for ($i = $start_ProductId; $i <= $end_ProductId; $i++) {
		$content = $page_parsed->find('.product-id-' . $i)->html();
		$content = preg_replace('/<font style=\"vertical-align: inherit;\">/', '<span>', $content);
		$content = preg_replace('/<\/font>/', '</span>', $content);
		$content = preg_replace('/<\/p><br>/', '</p>', $content);
		$content = preg_replace('/<br><br>/', '<br>', $content);
		$content = preg_replace('/<br><br>/', '<br>', $content);
		$content = trim($content);
		$content = preg_replace('/\n/', '', $content);
		$content = preg_replace('/(<br>)+/', '<br>', $content);

		$content = preg_replace('/\ssrc=\".*?\"/', ' ', $content);
		$content = preg_replace('/data-savepage-src/', 'src', $content);

		$contetUAarr[] = $content;
	}

	return $contetUAarr;
}


for ($j = $list_num; $j <= $list_num; $j++) {
	echo_Excel($contetUAarr, $j);
}

function echo_Excel($contetUAarr, $num)
{
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	for ($i = 0; $i <= 300; $i++) {
		$line = $i + 2;
		$sheet->setCellValue('A' . $line, $contetUAarr[$i]);
	}

	$writer = new Xlsx($spreadsheet);
	$writer->save('./Excel/Result_Excel_products' . $num . '.xlsx');
}
