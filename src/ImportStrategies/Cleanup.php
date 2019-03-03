<?php

declare(strict_types=1);

namespace Hbp\Import\ImportStrategies;

class Cleanup
{
    public static function deleteMultipleSpaces($string)
    {
        return preg_replace("#[ \t\n\r\0\x0B]+#", " ", $string);
    }

    public static function replaceWeirdCharacters($string)
    {
        return str_replace(['”', '“'], ['"', '"'], $string);
    }
}