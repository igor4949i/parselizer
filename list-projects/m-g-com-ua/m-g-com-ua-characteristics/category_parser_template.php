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
$url_product_array = ["https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-11__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-4__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-10__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-8__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-3__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-9__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-5__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-6/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-28/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-7__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-2/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed-6__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn__trashed/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-7/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-29/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-8/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-30/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-9/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-31/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-12/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-13/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-34/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-14/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-35/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-15/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-16/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-36/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-17/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-37/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-18/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-38/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-19/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-39/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-20/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-40/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-21/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-41/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-22/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-42/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-24/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-43/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-25/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-44/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-26/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-45/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-27/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-46/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-2/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-3/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-4/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-5/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-47/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-48/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-49/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-50/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-53/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-51/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-52/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-54/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-55/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-56/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-57/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-58/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-59/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-60/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d12-d9-5-l82-6-h38-1-z2-14-10212p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d12-d12-7-l82-6-h38-1-z2-14-10412p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d12-d12-7-l82-6-h54-z2-14-10612p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d12-d127-l81-h381-z2-14-20412p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d6-d12-7-l50-8-h12-5-z2-16-10006p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d8-d12-7-l50-8-h12-5-z2-16-10008p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d6-d19-l50-8-h12-5-z2-16-10406p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d8-d19-l50-8-h12-5-z2-16-10408p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d12-d12-7-l60-3-h12-5-z2-16-11012p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d12-d31-8-l60-3-h12-1-z2-16-11812p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d6-d19-l61-9-h19-z2-16-50406p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d8-d16-l85-h45-z21-17-10008p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d8-d18-l70-h18-z21-17-10208p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d8-d20-l70-h18-z21-17-10408p/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-d8-d22-l70-h25-z21-17-10608p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-tverdosp/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-tverdosp-4/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-tverdosp-2/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-tverdosp-5/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-tverdosp-3/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-tverdosp-6/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d6d95-h9r48l46z2-18-10606p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d8d95-h9r48l46z2-18-10608p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d6d12-h9r6l46z2-18-10806p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d8d12-h9r6l46z2-18-10808p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d6d158-h11r8l508z2-18-11006p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d8d158-h11r8l508z2-18-11008p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d6d19-h11r95l508z2-18-11206p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d8d19-h11r95l508z2-18-11208p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-tverdosp-7/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d12d127-h317r635l715z2-18-11612p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d12d19-h317r95l73z2-18-12212p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-d12d254-h317r127l73z2-18-12612p/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-3/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-2/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-4/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-5/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-6/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-7/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-9/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d19-h127-r48-l542-z2-30-10006p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d19-h127-r48-l542-z2-30-10008p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d19-h127-r48-l62-z2-30-10012p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d222-h127-r635-l542-z2-30-10206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d222-h127-r635-l542-z2-30-10208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d254-h127-r8-l542-z2-30-10306p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d254-h127-r8-l542-z2-30-10308p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d318-h127-r95-l552-z2-30-10406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d318-h127-r95-l552-z2-30-10408p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d381-h164-r127-l583-z2-30-10606p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d381-h164-r127-l583-z2-30-10608p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d222-h127-r635-l612-z2-30-11012p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d254-h127-r8-l607-z2-30-11112p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d318-h127-r95-l612-z2-30-11212p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d381-h164-r127-l649-z2-30-11412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d318-h151-r8-l566-z2-30-20206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d318-h151-r8-l566-z2-30-20208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d318-h151-r8-l635-z2-30-22212p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d286-h127-r48-l542-z2-30-30406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d286-h127-r48-l542-z2-30-30408p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d286-h127-r48-l635-z2-30-32412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d158-h127-r16-l549-z2-34-10006p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d158-h127-r16-l549-z2-34-10008p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d167-h127-r2-l549-z2-34-10106p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d167-h127-r2-l549-z2-34-10108p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d19-h127-r32-l552-z2-34-10406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d19-h127-r32-l552-z2-34-10408p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d222-h127-r48-l549-z2-34-10806p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d222-h127-r48-l549-z2-34-10808p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d254-h127-r635-l552-z2-34-11006p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d254-h127-r635-l552-z2-34-11008p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d286-h127-r8-l552-z2-34-11206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d286-h127-r8-l552-z2-34-11208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d318-h175-r95-l58-z2-34-11406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d318-h175-r95-l58-z2-34-11408p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d381-h1905-r127-l607-z2-34-11606p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d381-h1905-r127-l607-z2-34-11608p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d254-h127-r635-l612-z2-34-12012p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d318-h18-r95-l657-z2-34-12412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d381-h1905-r127-l673-z2-34-12612p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d8-d445-h222-r158-l639-z2-34-12708p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d445-h222-r158-l707-z2-34-12712p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d508-h254-r19-l739-z2-34-12812p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d571-h318-r222-l799-z2-34-13012p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d635-h318-r254-l798-z2-34-13212p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d6985-h3492-r286-l833-z2-34-13412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d12-d762-h381-r318-l866-z2-34-13612p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d254-h127-r635-l552-z2-36-11006p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-kalevochnaya-d6-d318-h18-r95-l657-z2-36-11406p/",
"https://vok.com.ua/product/freza-freud-pazovaya-fasonnaya-d8-d3175-h143-r953-l463-z239-20808p/",
"https://vok.com.ua/product/freza-freud-pazovaya-fasonnaya-d8-d247-h127-r6-l447-z239-20908p/",
"https://vok.com.ua/product/freza-freud-pazovaya-fasonnaya-d12-d635-h333-r254-l713-z239-23812p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d6-d95-h254-l715-c95-s254-z2-42-10006p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d8-d95-h254-l715-c90-s254-z2-42-10008p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d6-d95-h131-l588-c95-s127-z2-42-10206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d8-d95-h131-l588-c95-s127-z2-42-10208p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d6-d127-h254-l715-c127-s254-z2-42-10406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d8-d127-h254-l715-c127-s254-z2-42-10408p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d12-d127-h254-l825-c127-s254-z2-42-11012p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d12-d127-h381-l936-c127-s381-z2-42-11412p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d8-d127-h40-l84-c127-s40-z2-42-11508p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d12-d127-h508-l1068-c127-s508-z2-42-11612p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d6-d13-h20-l60-c13-z2-50-10206p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d8-d16-h20-l60-c16-z2-50-10308p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d6-d15-h20-l60-c15-z2-50-10406p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d6-d19-h254-l675-c19-z2-50-10606p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-pryamaya-d8-d22-h20-l60-c22-z2-50-10808p/",
"https://vok.com.ua/product/freza-freud-pazovaya-t-obraznaya-d12-d2858-h206-l635-z2-52-52212p/",
"https://vok.com.ua/product/freza-freud-pazovaya-t-obraznaya-d12-d30-h18-l61-z2-52-52612p/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d6-d19-h124-r32-l447/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d8-d19-h124-r32-l447/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d6-d222-h195-r48-l51/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d8-d222-h195-r48-l51/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d6-d254-h229-r635-l5/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d8-d254-h229-r635-l5/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d12-d19-h127-r32-l54/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d12-d222-h19-r48-l60/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d12-d254-h23-r635-l65/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d12-d37-h29-r95-l715/",
"https://vok.com.ua/product/freza-freud-kromochnaya-polusterzhnevaya-d12-d459-h354-r127-l/",
"https://vok.com.ua/product/freza-freud-pazovaya-galtelnaya-s-podshipni-8/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-10/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-32/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-11/",
"https://vok.com.ua/product/freza-freud-pazovaya-pryamaya-s-tverdosplavn-33/"];

$list_category_page_url = [
  "https://vok.com.ua/search?search=42-10006P",
  "https://vok.com.ua/search?search=42-10008P",
  "https://vok.com.ua/search?search=42-10206P",
  "https://vok.com.ua/search?search=42-10208P",
  "https://vok.com.ua/search?search=42-10406P",
  "https://vok.com.ua/search?search=42-10408P",
  "https://vok.com.ua/search?search=42-11012P",
  "https://vok.com.ua/search?search=42-11412P",
  "https://vok.com.ua/search?search=42-11508P",
  "https://vok.com.ua/search?search=42-11612P",
  "https://vok.com.ua/search?search=50-10206P",
  "https://vok.com.ua/search?search=50-10308P",
  "https://vok.com.ua/search?search=50-10406P",
  "https://vok.com.ua/search?search=50-10606P",
  "https://vok.com.ua/search?search=50-10808P"
];

// array_push($list_category_page_url, $url_category);

// category_url_product($url_category);

// run parse each product
for ($i = 0; $i < 1; $i++) { // count($url_product_array)
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
  $name_product = $doc->find('.us-main-shop-title')->text();
  $number_product = $number_product;
  // url product
  $url = $url_item;

  // art
  $art = $doc->find('.us-product-info-code')->text();

  // // category_list
  // $category = $doc->find('.x-breadcrumb .x-breadcrumb__item a');
  // $category_list = [];
  // foreach($category as $item) {
  //   $item = pq($item);
  //   $item = trim($item->text());
  //   array_push($category_list, $item);
  // }
  // array_shift($category_list);
  // array_pop($category_list);

  // $category_breadcrumbs_last = $category_list[array_key_last($category_list)];
  // $category_list = implode('>', $category_list);

  // $characteristics
  $characteristics_data =  $doc->find('.us-product-attributes-cont');
  $characteristics_data_title = $doc->find('.us-product-attributes-cont .us-product-attr-item span:even');
  $characteristics_data_value = $doc->find('.us-product-attributes-cont .us-product-attr-item span:odd');

  $characteristics_data_title_final = [];
  $characteristics_data_value_final = [];

  foreach($characteristics_data_title as $item) {
    $item = pq($item);
    $item = trim($item->text());
    array_push($characteristics_data_title_final, $item);
  }
  
  foreach($characteristics_data_value as $item) {
    $item = pq($item);
    $item = trim($item->text());
    array_push($characteristics_data_value_final, $item);
  }

  $characteristics_data_title_final = implode('---', $characteristics_data_title_final);
  $characteristics_data_value_final = implode('---', $characteristics_data_value_final);

  // pq($characteristics_data)->find('.x-title')->remove();
  // pq($characteristics_data)->find('.x-product-attr__more-link')->remove();
  // $characteristics = $characteristics_data->html();
  // $characteristics = preg_replace('/\shref=".*?"/', ' href="#"', $characteristics);


  echo '<tr>';
  echo '<td class="number_product">' . $number_product . '</td>';
  echo '<td class="name_product">' . $name_product . '</td>';
  echo '<td class="url">' . $url . '</td>';
  echo '<td class="art">' . $art . '</td>';
  echo '<td class="us-product-attributes-cont">' . $characteristics_data . '</td>';
  echo '<td class="us-product-attributes-cont">' . $characteristics_data_title_final . '</td>';
  echo '<td class="us-product-attributes-cont">' . $characteristics_data_value_final . '</td>';
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

