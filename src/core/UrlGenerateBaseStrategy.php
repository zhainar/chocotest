<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 15.08.2018
 * Time: 13:32
 */

namespace core;


use core\base\UrlGenerateStrategyInterface;

class UrlGenerateBaseStrategy implements UrlGenerateStrategyInterface
{
    public function generate($id, $name)
    {
        $map = [
            "а" => "a",
            "ый" => "iy",
            "ые" => "ie",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ё" => "yo",
            "ж" => "zh",
            "з" => "z",
            "и" => "i",
            "й" => "y",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "kh",
            "ц" => "ts",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "shch",
            "ь" => "",
            "ы" => "y",
            "ъ" => "",
            "э" => "e",
            "ю" => "yu",
            "я" => "ya",
            "йо" => "yo",
            "ї" => "yi",
            "і" => "i",
            "є" => "ye",
            "ґ" => "g"
        ];

        $link = mb_strtolower($name);
        $link = str_replace(array_keys($map), $map, $link);
        $link = preg_replace('/[^a-z\-]/', '-', $link);
        $link = preg_replace('/\-{2,}/', '-', $link);
        $link = preg_replace('/^\-+/', '', $link);
        $link = preg_replace('/\-+$/', '', $link);

        return sprintf('/%s-%s', $id, $link);
    }

}