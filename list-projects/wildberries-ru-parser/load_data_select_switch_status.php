<?php

$list_categories_array = [];
$value_option = [];

if ($file = fopen("./list_category_wildberries.csv", "r")) {
  while(!feof($file)) {
    $line = fgets($file);
    if ($line != "\n" && $line != "") {
      $value_option[] = trim($line);
      $line = explode(',', $line);
      $list_categories_array[] = $line;
    }
  }
  fclose($file);
}

for ($i=0; $i < count($list_categories_array); $i++) { 
  $index = $i+1;  
  echo '<option value="' . $value_option[$i] . '">№' . $index . ' - ' . $list_categories_array[$i][1] . '</option>';
}



?>