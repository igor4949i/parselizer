<?php

require 'phpQuery-onefile.php';
set_time_limit(0);
require_once("PHPDebug.php");
$debug = new PHPDebug();
echo $debug->debug("Start crawling");
$start = microtime(true);

// 0) create all html doc from local URL
for ($i = 1; $i <= 1162; $i++) {
  ${'url_' . $i} = 'Downloads/Адміністрування(' . $i . ').html';
  ${'html_' . $i} = file_get_contents(${'url_' . $i});
  ${'doc_' . $i} = phpQuery::newDocument(${'html_' . $i});
}

echo $debug->debug('step 0 | create documents | Time: ' . (microtime(true) - $start) . ' секунд');

// 1) Name RU
// 2) Name UA
for ($i = 1; $i <= 1162; $i++) {
  ${'name_ru_' . $i} = ${'doc_' . $i}->find('input[name="name_ru"]')->attr('value');
  ${'name_ua_' . $i} = ${'doc_' . $i}->find('input[name="name_ua"]')->attr('value');
}
echo $debug->debug('step 1 | Time: ' . (microtime(true) - $start) . ' секунд');


// 3) visible
for ($i = 1; $i <= 1162; $i++) {
  ${'visible_check_' . $i} = ${'doc_' . $i}->find('#saveform input[name="visible"]');

  if (${'visible_check_' . $i}->attr('checked')) {
    ${'visible_' . $i} = 'visible';
  } else {
    ${'visible_' . $i} = 'hidden';
  }
}
echo $debug->debug('step 3 | Visible | Time: ' . (microtime(true) - $start) . ' секунд');


// 15) images_url_1
for ($i = 1; $i <= 1162; $i++) {
  ${'images_url_' . $i} = ${'doc_' . $i}->find('.product-images a[rel="images"]');
}
echo $debug->debug('step 15 | image | Time: ' . (microtime(true) - $start) . ' секунд');

for ($i = 1; $i <= 1162; $i++) {
  // create array images href
  ${'result_url_array_' . $i} = [];
  // ${'result_images_'.$i} = '';
  foreach (${'images_url_' . $i} as $item) {
    $item = pq($item);
    $images_step_url = $item->attr('href');
    // ${'result_images_'.$i} .= ','.$images_step_url;
    array_push(${'result_url_array_' . $i}, $images_step_url);
  }

  if (count(${'result_url_array_' . $i}) == 1) {
    ${'additional_image_href_' . $i} = ${'result_url_array_' . $i}[0];
  }

  // if count url>2 => add urls to array
  if (count(${'result_url_array_' . $i}) > 1) {
    ${'additional_image_href_' . $i} = '' . ${'result_url_array_' . $i}[0];
    for ($x = 1; $x < count(${'result_url_array_' . $i}); $x++) {
      ${'additional_image_href_' . $i} .= ',' . ${'result_url_array_' . $i}[$x];
    }
  }
}
echo $debug->debug('step 16 | add image | Time: ' . (microtime(true) - $start) . ' секунд');


echo '<table>';
echo '<tr class="title">';
echo '<th class="number">' . '№' . '</th>';
echo '<th class="name_ru">' . 'Name RU' . '</th>';
echo '<th class="name_ua">' . 'Name UA' . '</th>';
echo '<th class="visible">' . 'Visible' . '</th>';
echo '<th class="images">' . 'main_image_href' . '</th>';
echo '</tr>';

for ($i = 1; $i <= 1162; $i++) {
  echo '<tr class="url-' . $i . '">';
  echo '<td class="number">' . $i . '</td>';
  echo '<td class="name-ru">' . ${'name_ru_' . $i} . '</td>';
  echo '<td class="name-ua">' . ${'name_ua_' . $i} . '</td>';
  echo '<td class="visible">' . ${'visible_' . $i} . '</td>';
  echo '<td class="images">' . ${'additional_image_href_' . $i} . '</td>';
  echo '</tr>';
}

echo '</table>';
