<?php
set_time_limit(0);
require './PHPDebug.php';
require './phpQuery-onefile.php';

if ($file = fopen("list.txt", "r")) {
	while (!feof($file)) {
		$line = fgets($file);
		$all_images[] = trim($line);
	}
	fclose($file);
}

for ($i = 0; $i < count($all_images); $i++) {
	saveImg($all_images[$i]);
	// echo $all_images[$i] . '</br>';
}

function saveImg($url)
{
	$img_name = preg_replace('/http.*\//', '', $url);

	if (!is_dir('Excel/imgs_cat/')) {
		mkdir('Excel/imgs_cat/', 0777, true);
	}

	// Image path
	$img_name_path = 'Excel/imgs_cat/' . $img_name;

	// Save image
	$ch = curl_init($url);
	$fp = fopen($img_name_path, 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
}
