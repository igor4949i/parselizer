<?php

use Google\Auth\FetchAuthTokenInterface;

require __DIR__ . '/vendor/autoload.php';
require  __DIR__ . '/phpQuery-onefile.php';
set_time_limit(0);

$url_product_array = ['https://prom.ua/p1173002854-interaktivnaya-igrushka-tantsuyuschij.html', 'https://prom.ua/p1146610169-perchatka-beskonechnosti-zheleznogo.html', 'https://prom.ua/p1246724216-domik-dlya-kukly.html', 'https://prom.ua/p1145868718-figurka-supergeroj-kapitan.html', 'https://prom.ua/p1146404400-figurka-supergeroj-kapitan.html', 'https://prom.ua/p1146414735-figurka-supergeroj-avengers.html', 'https://prom.ua/p1208197069-perchatka-beskonechnosti-perchatkatanosa.html', 'https://prom.ua/p1219869126-ruchnoj-ventilyator-detskij.html', 'https://prom.ua/p1146618999-igrushka-tri-kota.html', 'https://prom.ua/p1267309628-nabor-dlya-detskogo.html', 'https://prom.ua/p1147969204-konstruktor-qihui-tank.html', 'https://prom.ua/p1208462933-ruchka-dlya-risovaniya.html', 'https://prom.ua/p1208463012-plastik-dlya-ruchki.html', 'https://prom.ua/p1146612629-figurka-supergeroj-kapitan.html', 'https://prom.ua/p1146532555-molot-tora-sekira.html', 'https://prom.ua/p1146626414-igrovoj-nabor-slajm.html', 'https://prom.ua/p1146420532-figurka-supergeroj-avengers.html', 'https://prom.ua/p1267284448-mega-boks-bravl.html', 'https://prom.ua/p1170726448-robot-metallicheskij-zheleznyj.html', 'https://prom.ua/p1219867689-ruchnoj-ventilyator-detskij.html', 'https://prom.ua/p1146432792-perchatka-beskonechnosti-perchatkatanosa.html', 'https://prom.ua/p1170802113-nabor-tri-kota.html', 'https://prom.ua/p1147287265-kubik-mf8931abc-podstavke.html', 'https://prom.ua/p1146605174-nabor-supergeroya-perchatka.html', 'https://prom.ua/p1146607670-robot-metallicheskij-geroi.html', 'https://prom.ua/p1146614612-transformer-889-ruka.html', 'https://prom.ua/p1147170834-pauk-chernaya-vdova.html', 'https://prom.ua/p1146617646-igrushka-tri-kota.html', 'https://prom.ua/p1170727149-robot-metallicheskij-kapitan.html', 'https://prom.ua/p1145834700-figurka-supergeroj-zheleznyj.html', 'https://prom.ua/p1146627359-nabor-ukrashenij-dlya.html', 'https://prom.ua/p1146405180-figurka-supergeroj-ronin.html', 'https://prom.ua/p1146445583-figurka-supergeroj-avengers.html', 'https://prom.ua/p1146437111-figurka-supergeroj-avengers.html', 'https://prom.ua/p1146605170-supergeroj-halk-dospehah.html', 'https://prom.ua/p1146605187-pena-dlya-sozdaniya.html', 'https://prom.ua/p1170722761-robot-metallicheskij-chelovek.html', 'https://prom.ua/p1208410971-detskij-fotoapparat-rozovyj.html', 'https://prom.ua/p1208410990-tsifrovoj-detskij-fotoapparat.html', 'https://prom.ua/p1147195476-kubik-eqy516-mnogogrannik.html', 'https://prom.ua/p1146612887-figurka-supergeroj-zheleznyj.html', 'https://prom.ua/p1208478683-interaktivnyj-smart-dinozavr.html', 'https://prom.ua/p1162375476-kopilka-sejf-kodovym.html', 'https://prom.ua/p1147297288-tank-upravlenii-bolshoj.html', 'https://prom.ua/p1208358632-besprovodnoj-mikrofon-karaoke.html', 'https://prom.ua/p1146624688-igrovoj-nabor-slajm.html', 'https://prom.ua/p1147204954-kubik-rubika-magic.html', 'https://prom.ua/p1146614115-nabor-robotov-futbol.html', 'https://prom.ua/p1219811473-samolet-planer-bolshoj.html', 'https://prom.ua/p1170725698-robot-metallicheskij-betmen.html', 'https://prom.ua/p1146606899-kubik-rubik-frukty.html', 'https://prom.ua/p1147210242-kubik-rubika-5x5.html', 'https://prom.ua/p1173008055-nabor-diy-slime.html', 'https://prom.ua/p1208370691-besprovodnoj-mikrofon-karaoke.html', 'https://prom.ua/p1208368938-besprovodnoj-mikrofon-karaoke.html', 'https://prom.ua/p1146612745-figurka-supergeroj-tor.html', 'https://prom.ua/p1146611280-figurka-super-geroj.html', 'https://prom.ua/p1145828671-supergeroj-mstiteli-99106.html', 'https://prom.ua/p1146612149-figurka-supergeroj-kapitan.html', 'https://prom.ua/p1162374902-kopilka-sejf.html', 'https://prom.ua/p1208489720-detskaya-videokamera-smart.html', 'https://prom.ua/p1208490517-detskaya-videokamera-smart.html', 'https://prom.ua/p1147284406-kubik-rubika-vosmiugolnij.html', 'https://prom.ua/p1267297820-nabor-transformerov-tsifry.html', 'https://prom.ua/p1146621972-interaktivnaya-malyshka-poni.html', 'https://prom.ua/p1146433713-figurka-supergeroj-avengers.html', 'https://prom.ua/p1146536018-nabor-supergeroya-spiderman.html', 'https://prom.ua/p1146605166-kopilka-sejf-kodovym.html', 'https://prom.ua/p1146405814-figurka-supergeroj-tor.html', 'https://prom.ua/p1145813476-ukazka-lazernaya-laser.html', 'https://prom.ua/p1146406356-figurka-supergeroj-tor.html', 'https://prom.ua/p1145820408-kopilka-sejf-kodovym.html', 'https://prom.ua/p1146611464-figurka-super-geroj.html', 'https://prom.ua/p1145806319-moschnaya-lazernaya-ukazka.html', 'https://prom.ua/p1146605115-kopilka-sejf-kodovym.html', 'https://prom.ua/p1146470791-konstruktor-figurka-world.html', 'https://prom.ua/p1146472008-konstruktor-figurka-world.html', 'https://prom.ua/p1146472085-konstruktor-figurka-world.html', 'https://prom.ua/p1146455789-konstruktor-figurka-world.html', 'https://prom.ua/p1146471272-konstruktor-figurka-world.html', 'https://prom.ua/p1146472236-konstruktor-figurka-world.html', 'https://prom.ua/p1147183242-konstruktor-bela-11261.html', 'https://prom.ua/p1172988875-kukla-candy-locks.html', 'https://prom.ua/p1172988012-kukla-candy-locks.html', 'https://prom.ua/p1267263835-igrovoj-nabor-figurok.html', 'https://prom.ua/p1178301423-nabor-kosmetiki-chemodan.html', 'https://prom.ua/p1147296658-tankovyj-boj-household.html', 'https://prom.ua/p1145825961-kopilka-robot-sejf.html', 'https://prom.ua/p1208447101-nabor-figurok-mishki.html', 'https://prom.ua/p1146408325-figurka-supergeroj-halk.html', 'https://prom.ua/p1146409476-figurka-supergeroj-betmen.html', 'https://prom.ua/p1147290708-kubik-logika-2202.html', 'https://prom.ua/p1246715544-nabor-play-doh.html', 'https://prom.ua/p1159847115-nabor-blasterov-sb458.html', 'https://prom.ua/p1146528533-schit-kapitan-amerika.html', 'https://prom.ua/p1146627142-nabor-dlya-tvorchestva.html', 'https://prom.ua/p1147156955-robot-povtoryuha-interaktivnyj.html', 'https://prom.ua/p1146613052-boj-robotov-nabor.html', 'https://prom.ua/p1147178264-trejler-metallicheskimi-mashinkami.html', 'https://prom.ua/p1146406908-figurka-supergeroj-halk.html', 'https://prom.ua/p1146611511-figurka-supergeroj-venom.html', 'https://prom.ua/p1147164129-robot-povtoryuha-interaktivnyj.html', 'https://prom.ua/p1178288393-bey-blade-bejblejd.html', 'https://prom.ua/p1146622105-interaktivnaya-malyshka-poni.html', 'https://prom.ua/p1146439413-figurka-supergeroj-avengers.html', 'https://prom.ua/p1146605167-kopilka-sejf-zheleznyj.html', 'https://prom.ua/p1147162413-robot-povtoryuha-interaktivnyj.html', 'https://prom.ua/p1146617918-igrushka-kuhnya-kota.html', 'https://prom.ua/p1147159707-robot-povtoryuha-interaktivnyj.html', 'https://prom.ua/p1145753052-kartina-gvozdi-pinart.html', 'https://prom.ua/p1145332100-shar-zhelanij.html', 'https://prom.ua/p1147197471-kubik-rubika-eqy502.html', 'https://prom.ua/p1146620961-figurka-supergeroj-doktor.html', 'https://prom.ua/p1145815975-denezhnyj-pistolet-money.html'];
$values_google_sheets = [];

for ($i = 0; $i < count($url_product_array); $i++) { //count($url_product_array)
  product_parsing($url_product_array[$i]);
}


function product_parsing($url_product_item)
{
  global $values_google_sheets;

  $product_data_arr = [];

  $html = file_get_contents($url_product_item);
  $doc = phpQuery::newDocument($html);

  $code_product = $doc->find('span[data-qaid="product-sku"]')->text();
  $code_product = preg_replace('/Код: /', '', $code_product);
  $product_data_arr[] = $code_product;

  $product_name = $doc->find('h1[data-qaid="product_name"]')->text();
  $product_data_arr[] = $product_name;

  // echo $product_name . '</br>';

  $desc = $doc->find('div[data-qaid="descriptions"]')->html();
  $desc = trim($desc);
  $product_data_arr[] = $desc;

  $price = $doc->find('span[data-qaid="product_price"]')->text();
  $price = preg_replace('/грн./', '', $price);
  $price = preg_replace('/[^x\d|*\.]/', '', $price);
  $product_data_arr[] = $price;

  $currency = 'UAH';
  $product_data_arr[] = $currency;

  $measure_unit = 'шт.';
  $product_data_arr[] = $measure_unit;

  // images
  $images = $doc->find('div[data-qaid="image_block"] img:not(.ek-image.ek-image_valign_center)');
  $images_url = [];
  foreach ($images as $item) {
    $item = pq($item);
    $item = preg_replace('/_w.*?_h.*?_/', '_', $item->attr('src')); // trim width/height sizes -- get full size image
    array_push($images_url, $item);
  }
  $images_url = implode(', ', $images_url);
  $product_data_arr[] = $images_url;

  $num_group = '89348430';
  $product_data_arr[] = $num_group;

  $name_group = 'Игровые фигурки, роботы трансформеры в Украине';
  $product_data_arr[] = $name_group;

  $link_group = 'https://prom.ua/Detskie-igrovye-figurki';
  $product_data_arr[] = $link_group;


  $manufacturer_name = [];
  $manufacturer_value = [];
  $characteristics_block_name = $doc->find('ul.ek-list.ek-list_indent_xs .ek-text.ek-text_color_black-600.ek-text_wrap_break');
  $characteristics_block_value = $doc->find('ul.ek-list.ek-list_indent_xs .ek-text.ek-text_wrap_break');
  foreach ($characteristics_block_name as $item_name) {
    $item_name = pq($item_name);
    $item_name = $item_name->text();
    $manufacturer_name[] = $item_name;
    // echo $item_name . '</br>';
  }
  foreach ($characteristics_block_value as $item) {
    $item = pq($item);
    $item = $item->text();
    $manufacturer_value[] = $item;
  }

  // for ($i = 0; $i < count($manufacturer_name); $i++) {
  //   $manufacturer_name[$i] = strtr($manufacturer_name[$i], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
  //   $manufacturer_name[$i] = trim($manufacturer_name[$i], chr(0xC2) . chr(0xA0)); // trim &nbsp
  //   $manufacturer_name[$i] = trim($manufacturer_name[$i]);
  // }


  $values_google_sheets[] = $product_data_arr;
}


// $values_google_sheets = [["1", "2", "3", "4"]];
// GOOGLE SHEETS
$googleAccountKeyFilePath = __DIR__ . '/credentials.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
$service = new Google_Service_Sheets($client);
// ID таблицы
$spreadsheetId = '1eSFcG5XhLtV0d58EefpDqYEuL5yNQG7U5ftNMtY0-IU';
$response = $service->spreadsheets->get($spreadsheetId);
$range = 'test!A2'; // Letter name Sheet
$response = $service->spreadsheets_values->clear($spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest([]));

$body = new Google_Service_Sheets_ValueRange(['values' => $values_google_sheets]);

// valueInputOption - Determines how input data should be interpreted.
// https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// RAW | USER_ENTERED
$options = array('valueInputOption' => 'USER_ENTERED');
$service->spreadsheets_values->update($spreadsheetId, $range, $body, $options);
