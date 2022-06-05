<?php
set_time_limit(0);
require __DIR__ . '/vendor/autoload.php';
require './PHPDebug.php';
require './phpQuery-onefile.php';

// get data from MAIN.JS -- $.post
$url_category = $_POST['parser_data'];

// create list URLs products
$arr_characteristics = [];

$fd = fopen('characteristics_column_from_row.txt', 'r') or die('не удалось открыть файл');
while (!feof($fd)) {
	$str = htmlentities(fgets($fd));
	$arr_characteristics[] = $str;
}
fclose($fd);

$final_arr_char = [];

for ($i = 0; $i < count($arr_characteristics); $i++) {
	$final_arr_char[] = explode("	", $arr_characteristics[$i]);
}

$name_char_list = array_shift($final_arr_char);
array_shift($name_char_list);
array_shift($name_char_list);

for ($i = 0; $i < count($final_arr_char); $i++) { // count($final_arr_char)
	column_data_char($name_char_list, $final_arr_char[$i]);
}

// parse each product
function column_data_char($name_char_list, $arr_char)
{
	$product_id = array_shift($arr_char);
	$attribute_group = array_shift($arr_char);
	//

	for ($i = 0; $i < count($name_char_list); $i++) {
		if (!empty($arr_char[$i])) {
			echo '<tr>';
			echo '<td class="product_id">' . $product_id . '</td>';
			echo '<td class="attribute_group">' . $attribute_group . '</td>';
			echo '<td class="name_char_list">' . $name_char_list[$i] . '</td>';
			echo '<td class="attribure_value">' . $arr_char[$i] . '</td>';
			echo '</tr>';
		}
	}
}
