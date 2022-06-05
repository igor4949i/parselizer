<?php

require('./PHPDebug.php');
require('./phpQuery-onefile.php');

$string = $_POST['parser_data'];
$file = './list_category_wildberries.csv';

deleteLineInFile($file, $string);

function deleteLineInFile($file, $string)
{
  $i = 0;
  $array = [];
	
	$read = fopen($file, "r") or die("can't open the file");
	while(!feof($read)) {
		$array[$i] = fgets($read);	
		++$i;
	}
	fclose($read);
	
	$write = fopen($file, "w") or die("can't open the file");
	foreach($array as $a) {
    $add_str = trim($a);
		if(!strstr($add_str, $string)) fwrite($write, $a);
	}
	fclose($write);
}

?>