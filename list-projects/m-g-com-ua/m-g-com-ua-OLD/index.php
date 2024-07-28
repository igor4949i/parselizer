<?php
set_time_limit(0);

require 'vendor/autoload.php';
require __DIR__ . '/phpQuery-onefile.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// $values_google_sheets = [];

// $url = './html/desc-ru.htm';
$url = './html/desc-ua-test.html';
$html = file_get_contents($url);
$doc = phpQuery::newDocument($html);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

for ($i=1; $i <= 1387; $i++) { 
    $item = $doc->find('#' . $i)->html();

    // $item = preg_replace('/<font style=\"vertical-align: inherit;\">/', '<span>', $item);
    // $item = preg_replace('/<\/font>/', '</span>', $item);
    // $item = preg_replace('/<font style=\"vertical-align: inherit;\">/', '<span>', $item);
    // $item = preg_replace('/<\/font>/', '</span>', $item);
    // $item = preg_replace('/<font style=\"vertical-align: inherit;\">/', '<span>', $item);
    // $item = preg_replace('/<\/font>/', '</span>', $item);

    $item = trim($item);
    $sheet->setCellValue('A' . $i, $item);
}

$writer = new Xlsx($spreadsheet);
$writer->save('./Excel/test.xlsx');


// ////////////////////////////////////////////////////////////////////////////////////////////////
// Читання Excel та запис на HTML


// $file = "Excel/All_desc_ru.xlsx"; // файл для получения данных
// $excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);; // подключить Excel-файл
// $excel->setActiveSheetIndex(0); // получить данные из указанного листа

// $sheet = $excel->getActiveSheet();

// // формирование html-кода с данными
// $html = '<div>';
// foreach ($sheet->getRowIterator() as $row) {
//     $cellIterator = $row->getCellIterator();
//     foreach ($cellIterator as $cell) {
//         // значение текущей ячейки
//         $value = $cell->getCalculatedValue();
//         $html .= $value;
//     }
// }
// $html .= $html . '</div>';
// // вывод данных
// echo $html;

// ////////////////////////////////////////////////////////////////////////////////////////////////






