<?php
set_time_limit(0);
require './PHPDebug.php';
require './phpQuery-onefile.php';

$file_categories = 'categories.txt';
$item = 0;
$categories_arr = [];

$read = fopen($file_categories, "r") or die("can't open the file_categories");
while (!feof($read)) {
	$categories_arr[$item] = trim(fgets($read));
	++$item;
}
fclose($read);

$categories_arr_string = implode('', $categories_arr);

echo $categories_arr_string;
