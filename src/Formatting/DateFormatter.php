<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Formatting;

use Raskoti\NepaliDatePicker\Exceptions\InvalidLocaleException;
use Raskoti\NepaliDatePicker\Support\DayCounter;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

/**
 * Resolves a format string (PHP-DateTime-style single-char tokens, plus
 * the custom multi-char aliases YYYY/MM/DD) against a BsDate or AdDate,
 * applying the requested locale (en/np) for names and digits.
 *
 * Supported tokens:
 *   Y / YYYY  4-digit year        y   2-digit year
 *   m / MM    2-digit month       n   month, no leading zero
 *   d / DD    2-digit day         j   day, no leading zero
 *   F         month name, full    M   month name, short (BS has no
 *                                     official short form; falls back to full)
 *   l         weekday, full       D   weekday, short      E   weekday, extra-short
 *   N         ISO weekday (1-7, Mon-Sun)   w   weekday (0-6, Sun-Sat)
 *   \X        literal character X (escape)
 */
final class DateFormatter
{
    public static function forBs(BsDate $date, string $format, string $locale = 'en'): string
    {
        self::assertValidLocale($locale);

        $weekday = self::bsWeekdayIndex($date);
        [$full, $short] = $locale === 'np'
            ? [LocaleData::BS_MONTHS_NP_FULL, LocaleData::BS_MONTHS_NP_FULL]
            : [LocaleData::BS_MONTHS_EN_FULL, LocaleData::BS_MONTHS_EN_FULL];

        return self::render($format, $locale, $date->year, $date->month, $date->day, $weekday, $full, $short);
    }

    public static function forAd(AdDate $date, string $format, string $locale = 'en'): string
    {
        self::assertValidLocale($locale);

        $weekday = $date->dayOfWeek();
        [$full, $short] = $locale === 'np'
            ? [LocaleData::AD_MONTHS_NP_FULL, LocaleData::AD_MONTHS_NP_SHORT]
            : [LocaleData::AD_MONTHS_EN_FULL, LocaleData::AD_MONTHS_EN_SHORT];

        return self::render($format, $locale, $date->year, $date->month, $date->day, $weekday, $full, $short);
    }

    private static function bsWeekdayIndex(BsDate $date): int
    {
        $offset = DayCounter::bsOffsetFromBase($date->year, $date->month, $date->day);
        $baseWeekday = (int) DayCounter::baseAdDateTime()->format('w');

        return (($baseWeekday + $offset) % 7 + 7) % 7;
    }

    /**
     * @param  array<int, string>  $monthsFull
     * @param  array<int, string>  $monthsShort
     */
    private static function render(
        string $format,
        string $locale,
        int $year,
        int $month,
        int $day,
        int $weekdayIndex,
        array $monthsFull,
        array $monthsShort
    ): string {
        $weekdaysFull = $locale === 'np' ? LocaleData::WEEKDAYS_NP_FULL : LocaleData::WEEKDAYS_EN_FULL;
        $weekdaysShort = $locale === 'np' ? LocaleData::WEEKDAYS_NP_SHORT : LocaleData::WEEKDAYS_EN_SHORT;
        $weekdaysExtra = $locale === 'np' ? LocaleData::WEEKDAYS_NP_EXTRA : LocaleData::WEEKDAYS_EN_EXTRA;

        $isoWeekday = $weekdayIndex === 0 ? 7 : $weekdayIndex;

        $out = '';
        $len = strlen($format);

        for ($i = 0; $i < $len; $i++) {
            $char = $format[$i];

            if ($char === '\\' && $i + 1 < $len) {
                $out .= $format[++$i];

                continue;
            }

            if (substr($format, $i, 4) === 'YYYY') {
                $out .= self::numeral(sprintf('%04d', $year), $locale);
                $i += 3;

                continue;
            }

            if (substr($format, $i, 2) === 'MM') {
                $out .= self::numeral(sprintf('%02d', $month), $locale);
                $i += 1;

                continue;
            }

            if (substr($format, $i, 2) === 'DD') {
                $out .= self::numeral(sprintf('%02d', $day), $locale);
                $i += 1;

                continue;
            }

            $out .= match ($char) {
                'Y' => self::numeral(sprintf('%04d', $year), $locale),
                'y' => self::numeral(sprintf('%02d', $year % 100), $locale),
                'm' => self::numeral(sprintf('%02d', $month), $locale),
                'n' => self::numeral((string) $month, $locale),
                'd' => self::numeral(sprintf('%02d', $day), $locale),
                'j' => self::numeral((string) $day, $locale),
                'F' => $monthsFull[$month - 1],
                'M' => $monthsShort[$month - 1],
                'l' => $weekdaysFull[$weekdayIndex],
                'D' => $weekdaysShort[$weekdayIndex],
                'E' => $weekdaysExtra[$weekdayIndex],
                'N' => self::numeral((string) $isoWeekday, $locale),
                'w' => self::numeral((string) $weekdayIndex, $locale),
                default => $char,
            };
        }

        return $out;
    }

    private static function assertValidLocale(string $locale): void
    {
        if (! in_array($locale, LocaleData::SUPPORTED_LOCALES, true)) {
            throw InvalidLocaleException::unsupported(
                $locale,
                LocaleData::SUPPORTED_LOCALES
            );
        }
    }

    private static function numeral(string $digits, string $locale): string
    {
        return $locale === 'np' ? NepaliNumerals::toDevanagari($digits) : $digits;
    }
}
