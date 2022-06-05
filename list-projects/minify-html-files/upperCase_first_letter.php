<?php

if (!function_exists('mb_ucfirst') && extension_loaded('mbstring')) {
/**
 * mb_ucfirst - преобразует первый символ в верхний регистр
 * @param string $str - строка
 * @param string $encoding - кодировка, по-умолчанию UTF-8
 * @return string
 */
	function mb_ucfirst($str, $encoding = 'UTF-8')
	{
		$str = mb_ereg_replace('^[\ ]+', '', $str);
		$str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
		mb_substr($str, 1, mb_strlen($str), $encoding);
		return $str;
	}
}

$str = 'test буквы';
$str2 = 'тест буквы';


echo mb_ucfirst($str);
echo mb_ucfirst($str2);