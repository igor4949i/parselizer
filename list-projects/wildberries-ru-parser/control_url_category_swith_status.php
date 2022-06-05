<?php

require('./PHPDebug.php');
require('./phpQuery-onefile.php');

$string = $_POST['parser_data'];

var_dump($string);

$add_arr = explode(',', $string);
if ($add_arr[2] === 'off') {
  $add_arr[2] = 'on';
} elseif ($add_arr[2] === 'on') {
  $add_arr[2] = 'off';
}

$new_string_status = implode(',', $add_arr);

$file = './list_category_wildberries.csv';

switchStatus($file, $string, $new_string_status);

function switchStatus($file, $string, $new_string_status)
{
  $i = 0;
  $array = [];
	
	$read = fopen($file, "r") or die("can't open the file");
	while(!feof($read)) {
    $array[$i] = fgets($read);
		++$i;
	}
  fclose($read);
  
  array_pop($array);

  for ($i=0; $i < count($array); $i++) {
    $array[$i] = trim($array[$i]);
    if ($array[$i] === $string) {
      $array[$i] = $new_string_status;
    }
    $array[$i] = $array[$i] . "\n";
    // echo $array[$i];
  }

	$write = fopen($file, "w");
	foreach($array as $a) {
    fwrite($write,$a);
	}
  fclose($write);
}

?>