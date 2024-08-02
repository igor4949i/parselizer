<?php
set_time_limit(0);
require ('./PHPDebug.php');
require ('./phpQuery-onefile.php');

// PHPDebug.php
// $debug = new PHPDebug();
// echo $debug->debug("Start");
// $start = microtime(true);



// get data from MAIN.JS -- $.post
$url_category = $_POST['parser_data'];

// create list URLs products
$url_product_array = ["https://generator.ua/ru/benzinovye-generatory/6817-generator-fogo-fv-11001-tre.html",
"https://generator.ua/ru/svarochnye-generatory/10899-generator-benzinovyj-svarochnyj-agt-wagt-220-dc-rasb.html",
"https://generator.ua/ru/benzinovye-generatory/6779-generator-fogo-fv-15000-tre.html",
"https://generator.ua/ru/benzinovye-generatory/6780-generator-benzinovyj-fogo-fv-20000-tre.html",
"https://generator.ua/ru/dizelnye-generatory/10969-generator-dizelnyj-enersol-stbs-90bnp.html",
"https://generator.ua/ru/dizelnye-generatory/10981-generator-dizelnyj-enersol-strs-90rnp.html",
"https://generator.ua/ru/dizelnye-generatory/10967-generator-dizelnyj-enersol-stbs-50bnp.html",
"https://generator.ua/ru/dizelnye-generatory/10968-generator-dizelnyj-enersol-stbs-72bnp.html",
"https://generator.ua/ru/svarochnye-generatory/5822-generator-svarochnyj-agt-wagt-300-dc-hsbe.html",
"https://generator.ua/ru/benzinovye-generatory/6382-generator-benzinovyj-matari-mx14000ea-ats.html",
"https://generator.ua/ru/invertornye-generatory/6578-generator-benzinovyj-invertornyj-fogo-f2001is.html",
"https://generator.ua/ru/benzinovye-generatory/3935-generator-agt-16503-hsbe-r16.html",
"https://generator.ua/ru/benzinovye-generatory/7051-generator-benzinovyj-enersol-epg-8500ue.html",
"https://generator.ua/ru/benzinovye-generatory/7487-generator-benzinovyj-enersol-epg-7500tea.html",
"https://generator.ua/ru/benzinovye-generatory/7046-generator-benzinovyj-enersol-epg-7500te.html",
"https://generator.ua/ru/benzinovye-generatory/7466-generator-benzinovyj-agt-mlg65002.html",
"https://generator.ua/ru/benzinovye-generatory/7584-generator-benzinovyj-agt-mlg9300e2.html",
"https://generator.ua/ru/benzinovye-generatory/7465-generator-benzinovyj-agt-mlg35002.html",
"https://generator.ua/ru/benzinovye-generatory/7467-generator-benzinovyj-agt-mlg6500e2.html",
"https://generator.ua/ru/benzinovye-generatory/7488-generator-benzinovyj-enersol-epg-8500uea.html",
"https://generator.ua/ru/benzinovye-generatory/7582-generator-benzinovyj-agt-3601-hsb-ttl.html",
"https://generator.ua/ru/benzinovye-generatory/7549-generator-benzinovyj-vulkan-sc8000te.html",
"https://generator.ua/ru/dizelnye-generatory/7716-generator-dizelnyj-enersol-scms-45dm.html",
"https://generator.ua/ru/benzinovye-generatory/7583-generator-benzinovyj-agt-mlg3500e2.html",
"https://generator.ua/ru/dizelnye-generatory/7720-generator-dizelnyj-enersol-scbs-65dm.html",
"https://generator.ua/ru/benzinovye-generatory/7744-generator-benzinovyj-matari-mh10000ea-ats.html",
"https://generator.ua/ru/benzinovye-generatory/7049-generator-benzinovyj-enersol-epg-5500s.html",
"https://generator.ua/ru/benzinovye-generatory/7742-generator-benzinovyj-matari-mh9000ea.html",
"https://generator.ua/ru/benzinovye-generatory/7743-generator-benzinovyj-matari-mh9000ea-ats.html",
"https://generator.ua/ru/benzinovye-generatory/7745-generator-benzinovyj-matari-mh10000ea.html",
"https://generator.ua/ru/benzinovye-generatory/5689-generator-matari-mx13000ea-ats.html",
"https://generator.ua/ru/dizelnye-generatory/7703-generator-dizelnyj-enersol-scbs-90dm.html",
"https://generator.ua/ru/dizelnye-generatory/7715-generator-dizelnyj-enersol-scms-35dm.html",
"https://generator.ua/ru/dizelnye-generatory/7718-generator-dizelnyj-enersol-scks-35dm.html",
"https://generator.ua/ru/dizelnye-generatory/7704-generator-dizelnyj-enersol-stss-700snp.html",
"https://generator.ua/ru/dizelnye-generatory/7717-generator-dizelnyj-enersol-scps-25dm.html",
"https://generator.ua/ru/dizelnye-generatory/7480-generator-dizelnyj-enersol-scrs-110dm.html",
"https://generator.ua/ru/dizelnye-generatory/7479-generator-dizelnyj-enersol-scrs-85dm.html",
"https://generator.ua/ru/dizelnye-generatory/7714-generator-dizelnyj-enersol-scms-25dm.html",
"https://generator.ua/ru/invertornye-generatory/7167-generator-benzinovyj-invertornyj-weekender-gt4000ioe.html",
"https://generator.ua/ru/benzinovye-generatory/7771-generator-benzinovyj-aldo-ap-7000ge.html",
"https://generator.ua/ru/benzinovye-generatory/6466-generator-fogo-fv-10001-tre.html",
"https://generator.ua/ru/benzinovye-generatory/2115-generator-nik-pg-6300.html",
"https://generator.ua/ru/benzinovye-generatory/501-generator-nik-pg-5500.html",
"https://generator.ua/ru/benzinovye-generatory/6632-generator-honda-eg5500cl.html",
"https://generator.ua/ru/benzinovye-generatory/7446-generator-benzinovyj-genergy-ezcaray-s.html",
"https://generator.ua/ru/dizelnye-generatory/7781-generator-dizelnyj-profi-tec-dgs15-power-max.html",
"https://generator.ua/ru/benzinovye-generatory/7448-generator-benzinovyj-genergy-limited-5000.html",
"https://generator.ua/ru/benzinovye-generatory/51-generator-honda-eg-5500-cxs.html",
"https://generator.ua/ru/dizelnye-generatory/11097-generator-dizelnyj-enersol-scbs-40dm.html",
"https://generator.ua/ru/invertornye-generatory/11035-generator-benzinovyj-invertornyj-genergy-feroe.html",
"https://generator.ua/ru/benzinovye-generatory/11062-generator-benzinovyj-genergy-isasa.html",
"https://generator.ua/ru/gazovye-generatory/7254-generator-gazovyj-generac-7189-380v.html",
"https://generator.ua/ru/invertornye-generatory/11036-generator-benzinovyj-invertornyj-genergy-ibiza.html",
"https://generator.ua/ru/invertornye-generatory/11030-generator-invertornyj-benzinovyj-genergy-limited-1000i.html",
"https://generator.ua/ru/generatory-ot-vom/4187-generator-traktornyj-vom-agrovolt-27.html",
"https://generator.ua/ru/dizelnye-generatory/7776-generator-dizelnyj-profi-tec-pe-5700de.html",
"https://generator.ua/ru/dizelnye-generatory/7789-generator-dizelnyj-profi-tec-rdsg62-3-power-max.html",
"https://generator.ua/ru/dizelnye-generatory/5946-generator-vulkan-scd6000.html",
"https://generator.ua/ru/dizelnye-generatory/7772-generator-dizelnyj-aldo-ap-5500de.html",
"https://generator.ua/ru/dizelnye-generatory/10570-generator-dizelnyj-profi-tec-pe-11000ssde.html",
"https://generator.ua/ru/dizelnye-generatory/7451-generator-dizelnyj-genergy-limited-3000d.html",
"https://generator.ua/ru/gazovye-generatory/4292-generator-gazovyj-generac-7145.html",
"https://generator.ua/ru/dizelnye-generatory/10879-generator-dizelnyj-profi-tec-pe-12000ssde.html",
"https://generator.ua/ru/dizelnye-generatory/7755-generator-dizelnyj-profi-tec-dgs12-power-max.html",
"https://generator.ua/ru/dizelnye-generatory/8875-generator-dizelnyj-profi-tec-wdsg375-3-power-max.html",
"https://generator.ua/ru/invertornye-generatory/1252-generator-benzinovyj-invertornyj-honda-eu-30-is1.html",
"https://generator.ua/ru/dizelnye-generatory/11095-generator-dizelnyj-enersol-strs-65rnp.html",
"https://generator.ua/ru/gazovye-generatory/4291-generator-gazovyj-generac-7232-220v.html",
"https://generator.ua/ru/invertornye-generatory/44-generator-benzinovyj-invertornyj-honda-eu-10-it1.html",
"https://generator.ua/ru/generatory-ot-vom/4186-generator-traktornyj-vom-agrovolt-22.html",
"https://generator.ua/ru/benzinovye-generatory/7766-generator-benzinovyj-aldo-ap-8000ge.html",
"https://generator.ua/ru/svarochnye-generatory/7779-generator-dizelnyj-svarochnyj-aldo-ap-6500wdg.html",
"https://generator.ua/ru/dizelnye-generatory/7792-generator-dizelnyj-profi-tec-wdsg125-3-power-max.html",
"https://generator.ua/ru/dizelnye-generatory/7790-generator-dizelnyj-profi-tec-wdsg62-3-power-max.html",
"https://generator.ua/ru/gazovye-generatory/4293-generator-gazovyj-generac-7146.html",
"https://generator.ua/ru/invertornye-generatory/3461-generator-benzinovyj-invertornyj-honda-eu-70-is.html",
"https://generator.ua/ru/dizelnye-generatory/11071-generator-dizelnyj-genergy-gds90t.html",
"https://generator.ua/ru/invertornye-generatory/11283-generator-benzinovyj-invertornyj-matari-m4600io.html",
"https://generator.ua/ru/benzinovye-generatory/7410-generator-benzinovyj-invertornyj-fogo-f3001is.html",
"https://generator.ua/ru/svarochnye-generatory/5951-generator-svarochnyj-vulkan-sc200m-2.html",
"https://generator.ua/ru/benzinovye-generatory/1416-generator-konnersohnen-3000.html",
"https://generator.ua/ru/benzinovye-generatory/1421-generator-konnersohnen-7000e-ats.html",
"https://generator.ua/ru/benzinovye-generatory/1423-generator-benzinovyj-konnersohnen-10000e-ats.html",
"https://generator.ua/ru/benzinovye-generatory/1417-generator-konnersohnen-3000e.html",
"https://generator.ua/ru/benzinovye-generatory/2530-generator-konnersohnen-7000.html",
"https://generator.ua/ru/benzinovye-generatory/3534-generator-benzinovyj-konnersohnen-10000e-13.html",
"https://generator.ua/ru/benzinovye-generatory/1419-generator-konnersohnen-7000e.html",
"https://generator.ua/ru/benzinovye-generatory/3034-generator-benzinovyj-konnersohnen-10000e-3-ats.html",
"https://generator.ua/ru/benzinovye-generatory/3031-generator-konnersohnen-7000e-3.html",
"https://generator.ua/ru/benzinovye-generatory/7064-generator-benzinovyj-konnersohnen-ksb-6500c.html",
"https://generator.ua/ru/benzinovye-generatory/7413-generator-benzinovyj-invertornyj-konnersohnen-ks-2100i-s.html",
"https://generator.ua/ru/benzinovye-generatory/6540-generator-benzinovyj-konnersohnen-ks-12-1e-13-atsr.html",
"https://generator.ua/ru/benzinovye-generatory/7595-generator-postoyannogo-napryazheniya-konnersohnen-ks-48v-dc.html",
"https://generator.ua/ru/dizelnye-generatory/7566-generator-dizelnyj-konnersohnen-ks-18-1xm.html",
"https://generator.ua/ru/dizelnye-generatory/10902-generator-dizelnyj-konnersohnen-ks-18-1de-g.html",
"https://generator.ua/ru/dizelnye-generatory/7372-generator-dizelnyj-konnersohnen-ks-9300de-13-atsr-super-s-euro-v.html",
"https://generator.ua/ru/generatory-gaz-benzin/6399-generator-dvukhtoplivnyj-konnersohnen-ks-10000e-g.html",
"https://generator.ua/ru/generatory-gaz-benzin/7243-dvukhtoplivnyj-generator-gaz-benzin-konnersohnen-ks-4000ieg-s.html",
"https://generator.ua/ru/generatory-gaz-benzin/7416-generator-invertornyj-gazobenzinovyj-konnersohnen-ks-3100ig-s.html",
"https://generator.ua/ru/benzinovye-generatory/6541-generator-konnersohnen-ks-12-1e-atsr.html",
"https://generator.ua/ru/dizelnye-generatory/3425-generator-konnersohnen-9100hde-13-atsr.html",
"https://generator.ua/ru/generatory-gaz-benzin/7242-dvukhtoplivnyj-generator-gaz-benzin-konnersohnen-ks-2000ig-s.html",
"https://generator.ua/ru/gazovye-generatory/4311-generator-generac-sg-064.html",
"https://generator.ua/ru/invertornye-generatory/6551-generator-invertornyj-konnersohnen-ks-4000ie-s.html",
"https://generator.ua/ru/gazovye-generatory/4309-generator-generac-sg-056.html",
"https://generator.ua/ru/dizelnye-generatory/7571-generator-dizelnyj-konnersohnen-ks-33-3de-g.html",
"https://generator.ua/ru/invertornye-generatory/7421-generator-benzinovyj-invertornyj-konnersohnen-ksb-30i-s.html",
"https://generator.ua/ru/invertornye-generatory/7420-generator-benzinovyj-invertornyj-konnersohnen-ksb-12i-s.html",
"https://generator.ua/ru/invertornye-generatory/7418-generator-invertornyj-gazobenzinovyj-konnersohnen-ks-5500ieg-s.html",
"https://generator.ua/ru/generatory-gaz-benzin/1418-generator-konnersohnen-3000g.html",
"https://generator.ua/ru/generatory/3886-generator-fogo-fh-6001.html",
"https://generator.ua/ru/gazovye-generatory/4314-generator-gazovyj-generac-sg-104.html",
"https://generator.ua/ru/dizelnye-generatory/10901-generator-dizelnyj-konnersohnen-ks-18-1ye.html",
"https://generator.ua/ru/invertornye-generatory/7198-generator-benzinovyj-invertornyj-konnersohner-ksb-21i-s.html",
"https://generator.ua/ru/generatory-gaz-benzin/1420-generator-dvukhtoplivnyj-konnersohnen-7000e-g.html",
"https://generator.ua/ru/generatory-gaz-benzin/7414-generator-invertornyj-gazobenzinovyj-konnersohnen-ks-2100ig-s.html",
"https://generator.ua/ru/invertornye-generatory/3095-generator-benzinovyj-invertornyj-konnersohnen-2000i-s.html",
"https://generator.ua/ru/invertornye-generatory/7723-generator-invertornyj-konnersohnen-ks-4000ie-s-ats.html",
"https://generator.ua/ru/benzinovye-generatory/11334-generator-benzinovyj-agt-7501-rasb.html",
"https://generator.ua/ru/benzinovye-generatory/11333-generator-benzinovyj-agt-7201-rasb.html",
"https://generator.ua/ru/benzinovye-generatory/11335-generator-benzinovyj-agt-7501-rasbe.html",
"https://generator.ua/ru/benzinovye-generatory/7411-generator-benzinovyj-invertornyj-fogo-f4001is.html",
"https://generator.ua/ru/benzinovye-generatory/6839-generator-fogo-fv-13000-tre.html",
"https://generator.ua/ru/benzinovye-generatory/7550-generator-benzinovyj-agt-7601-hsbe.html",
"https://generator.ua/ru/benzinovye-generatory/2331-generator-fogo-fv-17001rte.html",
"https://generator.ua/ru/benzinovye-generatory/11332-generator-benzinovyj-agt-3501-rasb-se.html",
"https://generator.ua/ru/benzinovye-generatory/11330-generator-benzinovyj-agt-8603-hsbe-ttl.html",
"https://generator.ua/ru/benzinovye-generatory/11331-generator-benzinovyj-agt-16503-rasbe-r45.html",
"https://generator.ua/ru/benzinovye-generatory/11337-generator-benzinovyj-agt-9203-rasb.html",
"https://generator.ua/ru/benzinovye-generatory/11336-generator-benzinovyj-agt-8203-rasb.html",
"https://generator.ua/ru/benzinovye-generatory/11340-generator-benzinovyj-enersol-epg-5500seh.html",
"https://generator.ua/ru/dizelnye-generatory/11323-generator-dizelnyj-agt-10001dsea.html",
"https://generator.ua/ru/dizelnye-generatory/11325-generator-dizelnyj-agt-18dsea.html",
"https://generator.ua/ru/dizelnye-generatory/11324-generator-dizelnyj-agt-12003dsea.html",
"https://generator.ua/ru/invertornye-generatory/11039-generator-benzinovyj-invertornyj-genergy-rodas.html",
"https://generator.ua/ru/invertornye-generatory/11038-generator-benzinovyj-invertornyj-genergy-limited-2000i.html",
"https://generator.ua/ru/invertornye-generatory/11338-generator-benzinovyj-invertornyj-agt-3500ier.html",
"https://generator.ua/ru/benzinovye-generatory/7473-generator-benzinovyj-gtm-dk7500-l-3.html",
"https://generator.ua/ru/benzinovye-generatory/11322-generator-benzinovyj-fogo-fm-8000-re.html",
"https://generator.ua/ru/dizelnye-generatory/7568-generator-dizelnyj-genergy-gds10t.html",
"https://generator.ua/ru/benzinovye-generatory/5709-generator-konnersohnen-basic-ks-2200-a.html",
"https://generator.ua/ru/benzinovye-generatory/7521-generator-benzinovyj-vulkan-sc18000-iii.html",
"https://generator.ua/ru/benzinovye-generatory/5711-generator-konnersohnen-basic-ks-2800-a.html",
"https://generator.ua/ru/benzinovye-generatory/11339-generator-benzinovyj-enersol-epg-3200seh.html",
"https://generator.ua/ru/generatory-ot-vom/4184-generator-traktornyj-vom-agrovolt-38.html",
"https://generator.ua/ru/invertornye-generatory/6348-generator-honda-eu-22-it.html",
"https://generator.ua/ru/benzinovye-generatory/7471-generator-benzinovyj-gtm-dk5500-l.html",
"https://generator.ua/ru/benzinovye-generatory/7468-generator-benzinovyj-agt-mlg90002.html",
"https://generator.ua/ru/gibridnye-invertory-azzurro/11265-invertor-gibridnyj-azzurro-3ph-hyd10000-zss.html",
"https://generator.ua/ru/gibridnye-invertory-azzurro/11261-invertor-gibridnyj-azzurro-1ph-hyd6000-zss-hp.html",
"https://generator.ua/ru/setevye-invertory-fronius/11302-invertor-setevoj-fronius-symo-50-3-m-light.html",
"https://generator.ua/ru/setevye-invertory-fronius/11294-invertor-setevoj-fronius-symo-30-3-s-light.html",
"https://generator.ua/ru/komplektuyusshie-solnechnykh-elektrostancij/11022-solnechnaya-panel-enersol-esp-200w.html",
"https://generator.ua/ru/komplektuyusshie-solnechnykh-elektrostancij/11021-solnechnaya-panel-enersol-esp-100w.html",
"https://generator.ua/ru/komplektuyusshie-solnechnykh-elektrostancij/7543-paneli-solnechnye-ecoflow-400w-solar-panel.html",
"https://generator.ua/ru/komplektuyusshie-solnechnykh-elektrostancij/7528-paneli-solnechnye-jackery-solarsaga-200w.html",
"https://generator.ua/ru/komplektuyusshie-solnechnykh-elektrostancij/7542-paneli-solnechnye-ecoflow-220w-solar-panel.html",
"https://generator.ua/ru/komplektuyusshie-solnechnykh-elektrostancij/7540-paneli-solnechnye-ecoflow-110w-solar-panel.html",
"https://generator.ua/ru/komplektuyusshie-solnechnykh-elektrostancij/7541-paneli-solnechnye-ecoflow-160w-solar-panel.html",
"https://generator.ua/ru/komplektuyusshie-solnechnykh-elektrostancij/7527-paneli-solnechnye-jackery-solarsaga-100w.html",
"https://generator.ua/ru/solnechnye-generatory/11017-zaryadnaya-stanciya-weekender-hbp1600tp.html",
"https://generator.ua/ru/portativnye-zaryadnye-stancii/7656-elektrostanciya-portativnaya-konnersohnen-ks-300ps.html"];

$list_category_page_url = [];

// array_push($list_category_page_url, $url_category);

// category_url_product($url_category);

// run parse each product
for ($i = 0; $i < count($url_product_array); $i++) { // count($url_product_array)
  product_parsing($url_product_array[$i], $i + 1);
}


// for ($i = 0; $i < count($list_category_page_url); $i++) { // count($url_product_array)
//   category_url_product($list_category_page_url[$i], $i);
// }

// search URLs products on page category

function category_url_product($url_product_array, $i)
{
  $html = file_get_contents($url_product_array);
  $doc = phpQuery::newDocument($html);

  $number_product = $i + 1;
  $name = $doc->find('.us-category-products .us-module-item .us-module-title a')->text();
  $url = $doc->find('.us-category-products .us-module-item .us-module-title a')->attr('href');
  $html2 = file_get_contents($url);
  $doc2 = phpQuery::newDocument($html2);
  $art = $doc2->find('.us-product-info-code')->text();

  echo '<tr>';
  echo '<td class="number_product">' . $number_product . '</td>';
  echo '<td class="name">' . $name . '</td>';
  echo '<td class="url">' . $url . '</td>';
  echo '<td class="art">' . $art . '</td>';
  echo '</tr>';
}

function product_parsing($url_item, $number_product)
{
  $html = file_get_contents($url_item);
  $doc = phpQuery::newDocument($html);
  //  name_product
  $name_product = $doc->find('.product_header h1')->text();
  $number_product = $number_product;
  // url product
  $url = $url_item;

  // art
  $art = $doc->find('.product_header__reference .product_header__reference_value')->text();
  $price = $doc->find('#our_price_display')->attr('content');

  // $characteristics_data_title_final = implode('---', $characteristics_data_title_final);
  // $characteristics_data_value_final = implode('---', $characteristics_data_value_final);


  echo '<tr>';
  echo '<td class="number_product">' . $number_product . '</td>';
  echo '<td class="name_product">' . $name_product . '</td>';
  echo '<td class="url">' . $url . '</td>';
  echo '<td class="art">' . $art . '</td>';
  echo '<td class="price">' . $price . '</td>';

  // echo '<td class="us-product-attributes-cont">' . $characteristics_data_value_final . '</td>';
  // echo '<td class="sku">'.$sku.'</td>';
  // echo '<td class="available">'.$available.'</td>';
  // echo '<td class="desc_full">'.$desc_full.'</td>';
  // echo '<td class="category_list">'.$category_list.'</td>';
  // echo '<td class="category_breadcrumbs_last">'.$category_breadcrumbs_last.'</td>';
  // echo '<td class="_main_image_url">'.$main_image_url.'</td>';
  // echo '<td class="main_image_url">'.$additional_images_url.'</td>';
  // echo '<td class="all_images_url">'.$all_images_url.'</td>';
  // echo '<td class="characteristics">'.$characteristics.'</td>';
  echo '</tr>';
}

