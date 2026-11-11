<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Support;

use DateTimeImmutable;
use Raskoti\NepaliDatePicker\Data\BsCalendarData;
use Raskoti\NepaliDatePicker\Exceptions\OutOfRangeException;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

final class DayCounter
{
    public static function bsOffsetFromBase(int $year, int $month, int $day): int
    {
        new BsDate($year, $month, $day);

        [$baseYear, , $baseDay] = BsCalendarData::BASE_BS;

        $offset = CalendarRange::cumulativeDaysBeforeYear($year)
            - CalendarRange::cumulativeDaysBeforeYear($baseYear);

        for ($m = 1; $m < $month; $m++) {
            $offset += CalendarRange::daysInMonth($year, $m);
        }

        $offset += $day - 1;

        if ($year === $baseYear) {
            $offset -= ($baseDay - 1);
        }

        return $offset;
    }

    /**
     * @return array{year: int, month: int, day: int}
     */
    public static function offsetToBs(int $offset): array
    {
        if ($offset < 0) {
            throw OutOfRangeException::negativeOffset();
        }

        [$baseYear] = BsCalendarData::BASE_BS;

        $year = $baseYear;
        $remaining = $offset;

        while (true) {
            $daysInYear = CalendarRange::daysInYear($year);

            if ($remaining < $daysInYear) {
                break;
            }

            $remaining -= $daysInYear;
            $year++;

            if (! CalendarRange::isYearSupported($year)) {
                throw OutOfRangeException::resolvedYearBeyondMaximum($year, CalendarRange::maxYear());
            }
        }

        $month = 1;
        while (true) {
            $daysInMonth = CalendarRange::daysInMonth($year, $month);

            if ($remaining < $daysInMonth) {
                break;
            }

            $remaining -= $daysInMonth;
            $month++;
        }

        return ['year' => $year, 'month' => $month, 'day' => $remaining + 1];
    }

    /**
     * Signed day difference between BASE_AD and the given AD date.
     * Positive if the date is after BASE_AD.
     */
    public static function adOffsetFromBase(DateTimeImmutable $date): int
    {
        [$baseYear, $baseMonth, $baseDay] = BsCalendarData::BASE_AD;
        $base = (new DateTimeImmutable)->setDate($baseYear, $baseMonth, $baseDay)->setTime(0, 0);
        $target = $date->setTime(0, 0);

        $diff = $base->diff($target);
        $days = (int) $diff->format('%a');

        return $diff->invert === 1 ? -$days : $days;
    }

    public static function baseAdDateTime(): DateTimeImmutable
    {
        [$baseYear, $baseMonth, $baseDay] = BsCalendarData::BASE_AD;

        return (new DateTimeImmutable)->setDate($baseYear, $baseMonth, $baseDay)->setTime(0, 0);
    }
}
