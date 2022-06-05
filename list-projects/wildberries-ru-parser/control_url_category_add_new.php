<?php

require('./PHPDebug.php');
require('./phpQuery-onefile.php');

// set_time_limit(0);

// get data from MAIN.JS -- $.post
$data = $_POST['parser_data'];

$new_item = $data[1] . ',' . $data[2] . ',' . $data[3] . "\n";

$filename = './list_category_wildberries.csv';

// Вначале давайте убедимся, что файл существует и доступен для записи.
if (is_writable($filename)) {

    // В нашем примере мы открываем $filename в режиме "дописать в конец".
    // Таким образом, смещение установлено в конец файла и
    // наш $new_item допишется в конец при использовании fwrite().
    
    if (!$handle = fopen($filename, 'a')) {
         echo "Не могу открыть файл ($filename)";
         exit;
    }

    // Записываем $new_item в наш открытый файл.
    if (fwrite($handle, $new_item) === FALSE) {
        echo "Не могу произвести запись в файл ($filename)";
        exit;
    }
        
    fclose($handle);

} else {
    echo "Файл $filename недоступен для записи";
}

$index = $data[0] + 1;
echo '<tr class="item" data-value="'. $index .'">';
echo '<td class="number">'. $index .'</td>';
echo '<td class="url_wildberries">' . $data[1] . '</td>';
echo '<td class="name_google_sheets">' . $data[2] . '</td>';
echo '<td class="status"><span class="badge badge-pill badge-success">Включен</span></td>';
// echo '<td class="delete_url noExl"><button type="submit" class="btn-delete-item" value="'. $index .'" id="delete_item_' . $index. '">Удалить</button></td>';
echo '</tr>'; 


?>