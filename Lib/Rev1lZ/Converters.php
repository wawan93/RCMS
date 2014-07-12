<?php
/**
 * @author Rev1lZ<mrrev1lz@gmail.com>
 * @version 1.0
 *
 * Rev1lZ Converters static class
 */

class Rev1lZ_Converters {
    /**
     * @param int $byte
     * @return string
     *
     * Get file size format by byte
     */
    public static function fileSizeFormat($byte) {
        if ($byte >= 1073741824)
            return round($byte / 1073741824 * 100) / 100 . " GiB";
        elseif ($byte >= 1048576)
            return round($byte / 1048576 * 100) / 100 . " MiB";
        elseif ($byte >= 1024)
            return round($byte / 1024 * 100) / 100 . " KiB";
        else
            return $byte . " b";
    }

    /**
     * @param $string
     * @return mixed
     *
     * Converter to Translit
     */
    public static function toTranslit($string) {
        $that = array (
            "A", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ы", "Ь", "Э", "Ю", "Я",
            "a", "б", "в", "г", "д", "е", "ё", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", "ь", "э", "ю", "я"
        );

        $to = array (
            "A", "B", "V", "G", "D", "E", "Jo", "Zh", "Z", "I", "J", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "H", "C", "Ch", "Sh", "Shh", "##", "Y", "''", "Je", "Ju", "Ja",
            "a", "b", "v", "g", "d", "e", "jo", "zh", "z", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "ch", "sh", "shh", "#", "y", "'", "je", "ju", "ja"
        );

        return str_replace($that, $to, $string);
    }

    /**
     * @param array $array
     * @return string
     *
     * Convert Array to File
     */
    public static function arrayToFile(array $array) {
        return "<?php return ". var_export($array, true) . ";";
    }
}