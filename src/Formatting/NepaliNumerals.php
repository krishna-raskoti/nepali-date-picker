<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Formatting;

final class NepaliNumerals
{
    private const MAP = [
        '0' => '०',
        '1' => '१',
        '2' => '२',
        '3' => '३',
        '4' => '४',
        '5' => '५',
        '6' => '६',
        '7' => '७',
        '8' => '८',
        '9' => '९',
    ];

    public static function toDevanagari(string $value): string
    {
        return strtr($value, self::MAP);
    }

    public static function toEnglish(string $value): string
    {
        return strtr($value, array_flip(self::MAP));
    }
}
