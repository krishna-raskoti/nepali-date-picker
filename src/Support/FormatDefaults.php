<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Support;

final class FormatDefaults
{
    public static function locale(): string
    {
        if (function_exists('config')) {
            $locale = config('nepali-date-picker.default_locale', 'en');

            return is_string($locale) && $locale !== '' ? $locale : 'en';
        }

        // @codeCoverageIgnoreStart
        return 'en';
        // @codeCoverageIgnoreEnd
    }

    public static function format(): string
    {
        if (function_exists('config')) {
            $format = config('nepali-date-picker.default_format', 'Y-m-d');

            return is_string($format) && $format !== '' ? $format : 'Y-m-d';
        }

        // @codeCoverageIgnoreStart
        return 'Y-m-d';
        // @codeCoverageIgnoreEnd
    }
}
