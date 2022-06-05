<?php
set_time_limit(0);
require __DIR__ . '/vendor/autoload.php';
require './PHPDebug.php';
require './phpQuery-onefile.php';

// get data from MAIN.JS -- $.post
$url_category = $_POST['parser_data'];

// create list URLs products
$url_photo = [];

$fd_more = fopen('download_photo_url.txt', 'r') or die('не удалось открыть файл');
while (!feof($fd_more)) {
	$str = htmlentities(fgets($fd_more));
	$url_photo[] = trim($str);
}
fclose($fd_more);

$name_photo = [];

$fd_more = fopen('download_photo_finish_name.txt', 'r') or die('не удалось открыть файл');
while (!feof($fd_more)) {
	$str = htmlentities(fgets($fd_more));
	$name_photo[] = trim($str);
}
fclose($fd_more);

for ($i = 0; $i < count($url_photo); $i++) {
	file_put_contents('./photo_products/' . $name_photo[$i], file_get_contents($url_photo[$i]));
}
