<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Support;

use Raskoti\NepaliDatePicker\Data\BsCalendarData;
use Raskoti\NepaliDatePicker\Exceptions\InvalidBsDateException;
use Raskoti\NepaliDatePicker\Exceptions\OutOfRangeException;

final class CalendarRange
{
    private static ?int $minYear = null;

    private static ?int $maxYear = null;

    /**
     * @var array<int, int>|null
     */
    private static ?array $cumulativeDaysBeforeYear = null;

    public static function minYear(): int
    {
        return self::$minYear ??= min(array_keys(BsCalendarData::BS_MONTH_DAYS));
    }

    public static function maxYear(): int
    {
        return self::$maxYear ??= max(array_keys(BsCalendarData::BS_MONTH_DAYS));
    }

    public static function isYearSupported(int $year): bool
    {
        return array_key_exists($year, BsCalendarData::BS_MONTH_DAYS);
    }

    /**
     * @return array<int, int> Days in each of the 12 months for the given BS year.
     */
    public static function monthDays(int $year): array
    {
        if (! self::isYearSupported($year)) {
            throw OutOfRangeException::forBsYear($year, self::minYear(), self::maxYear());
        }

        return BsCalendarData::BS_MONTH_DAYS[$year];
    }

    public static function daysInMonth(int $year, int $month): int
    {
        if ($month < 1 || $month > 12) {
            throw InvalidBsDateException::invalidMonth($month);
        }

        return self::monthDays($year)[$month - 1];
    }

    public static function daysInYear(int $year): int
    {
        return array_sum(self::monthDays($year));
    }

    /**
     * @internal Low-level arithmetic helper for DayCounter. Not part of
     *           this package's public API surface.
     */
    public static function cumulativeDaysBeforeYear(int $year): int
    {
        self::buildCumulativeCache();

        return self::$cumulativeDaysBeforeYear[$year]
            ?? throw OutOfRangeException::forBsYear($year, self::minYear(), self::maxYear());
    }

    private static function buildCumulativeCache(): void
    {
        if (self::$cumulativeDaysBeforeYear !== null) {
            return;
        }

        $cache = [];
        $running = 0;

        for ($y = self::minYear(); $y <= self::maxYear(); $y++) {
            $cache[$y] = $running;
            $running += self::daysInYear($y);
        }

        self::$cumulativeDaysBeforeYear = $cache;
    }
}
