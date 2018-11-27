<?php
/** @var modX $modx */
switch ($modx->event->name) {
	case 'pdoToolsOnFenomInit':
		/** @var Fenom $fenom
		Мы получаем переменную $fenom при его первой инициализации, так что можем добавить
		модификатор "website" для вывода имени домена из произвольной ссылки.
		 */
		$fenom->addModifier('website', function ($input) {
			if (!$url = parse_url($input)) {
				return $input;
			}
			$output = str_replace('www.', '', $url['host']);

			return strtolower($output);
		});

		$fenom->addModifier('clearphone', function ($input) {
			$tmp =  preg_replace(array("/[^0123456789]/isx","/^8/isx"),array("","7"), strip_tags($input));
			if (mb_strlen($tmp)==10) {
				$tmp = "+7".$tmp;
			} elseif (mb_strlen($tmp)>10) {
				$tmp = "+".$tmp;
			}
			return $tmp;
		});

		break;
    default:
        break;
}
