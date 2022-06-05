<?php

$list_categories_array = [];

if ($file = fopen("./list_category_wildberries.csv", "r")) {
  while(!feof($file)) {
    $line = fgets($file);
    if ($line != "\n" && $line != "") {
      $line = explode(',', $line);
      array_push($list_categories_array, $line);
    }
  }
  fclose($file);
}

for ($i=0; $i < count($list_categories_array); $i++) { 
  $index = $i+1;  
  echo '<tr class="item_'. $index .'" data-value="'. $index .'">';
    echo '<td class="number">'. $index .'</td>';
    echo '<td class="url_wildberries">' . $list_categories_array[$i][0] . '</td>';
    echo '<td class="name_google_sheets">' . $list_categories_array[$i][1] . '</td>';
    echo '<td class="status">';
      if (trim($list_categories_array[$i][2]) === "on") {
        echo '<span class="badge badge-pill badge-success">Включен</span>';
      }
      if (trim($list_categories_array[$i][2]) === "off") {
        echo '<span class="badge badge-pill badge-danger">Выключен</span>';
      }

    echo '</td>';
    // echo '<td class="delete_url noExl"><button type="submit" class="btn btn-warning btn-delete-item" value="'. $index .'" id="delete_item_' . $index. '">Удалить</button></td>';
  echo '</tr>'; 
}



?>