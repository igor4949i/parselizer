<?php
set_time_limit(0);
require './PHPDebug.php';
require './phpQuery-onefile.php';

$count_num_product = $_POST['count_num_product'];

$file_products = 'url_list.txt';
$item = 0;
$product_arr = [];

$read = fopen($file_products, "r") or die("can't open the file_products");
while (!feof($read)) {
	$product_arr[$item] = trim(fgets($read));
	++$item;
}
fclose($read);


// $url_products = 'https: //bt.rozetka.com.ua/ua/263838001/p263838001/';

echo $product_arr[$count_num_product - 1];