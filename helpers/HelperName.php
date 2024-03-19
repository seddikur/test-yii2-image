<?php

namespace app\helpers;

/**
 * Class Helper
 */
class HelperName
{
    /**
     * Вычисление окончания слова по предметному кол-ву
     * например - 1 яблоко, 2 яблока, 5 яблок
     * @param $count int - число, по которому определяем окончание
     * @param $word array - [яблоко, яблока, яблок] или [корова, коровы, коров]
     * нужно заранее прикинуть окончание по алгоритму: [[1], [2, 3, 4], [5, 6 , 7, 8, 9]]
     * @return mixed
     */
    public static function countWordCorrect($count, $word)
    {
        $count = (int) $count;
        $cases = [2, 0, 1, 1, 1, 2];
        return $word[($count % 100 > 4 && $count % 100 < 20) ? 2 : $cases[min($count % 10, 5)]];
    }

    /**
     * @deprecated
     * @param $str
     * @return mixed|string
     */
    public static function transliteration($str)
    {
        // ГОСТ 7.79B
        $transliteration = [
            'А' => 'A', 'а' => 'a',
            'Б' => 'B', 'б' => 'b',
            'В' => 'V', 'в' => 'v',
            'Г' => 'G', 'г' => 'g',
            'Д' => 'D', 'д' => 'd',
            'Е' => 'E', 'е' => 'e',
            'Ё' => 'Yo', 'ё' => 'yo',
            'Ж' => 'Zh', 'ж' => 'zh',
            'З' => 'Z', 'з' => 'z',
            'И' => 'I', 'и' => 'i',
            'Й' => 'J', 'й' => 'j',
            'К' => 'K', 'к' => 'k',
            'Л' => 'L', 'л' => 'l',
            'М' => 'M', 'м' => 'm',
            'Н' => 'N', 'н' => 'n',
            'О' => 'O', 'о' => 'o',
            'П' => 'P', 'п' => 'p',
            'Р' => 'R', 'р' => 'r',
            'С' => 'S', 'с' => 's',
            'Т' => 'T', 'т' => 't',
            'У' => 'U', 'у' => 'u',
            'Ф' => 'F', 'ф' => 'f',
            'Х' => 'H', 'х' => 'h',
            'Ц' => 'Cz', 'ц' => 'cz',
            'Ч' => 'Ch', 'ч' => 'ch',
            'Ш' => 'Sh', 'ш' => 'sh',
            'Щ' => 'Shh', 'щ' => 'shh',
            'Ъ' => 'ʺ', 'ъ' => 'ʺ',
            'Ы' => 'Y`', 'ы' => 'y`',
            'Ь' => '', 'ь' => '',
            'Э' => 'E`', 'э' => 'e`',
            'Ю' => 'Yu', 'ю' => 'yu',
            'Я' => 'Ya', 'я' => 'ya',
            '№' => '#', 'Ӏ' => '‡',
            '’' => '`', 'ˮ' => '¨',
        ];

        $str = strtr($str, $transliteration);
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[^0-9a-z\-]/', '', $str);
        $str = preg_replace('|([-]+)|s', '-', $str);
        $str = trim($str, '-');

        return $str;
    }
}
