<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\ValueObjects;

use Raskoti\NepaliDatePicker\Exceptions\InvalidBsDateException;
use Raskoti\NepaliDatePicker\Formatting\DateFormatter;
use Raskoti\NepaliDatePicker\Support\CalendarRange;
use Raskoti\NepaliDatePicker\Support\FormatDefaults;

final class BsDate
{
    public readonly int $year;

    public readonly int $month;

    public readonly int $day;

    public function __construct(int $year, int $month, int $day)
    {
        if ($month < 1 || $month > 12) {
            throw InvalidBsDateException::invalidMonth($month);
        }

        // CalendarRange::daysInMonth() throws OutOfRangeException if $year is unsupported.
        $daysInMonth = CalendarRange::daysInMonth($year, $month);

        if ($day < 1 || $day > $daysInMonth) {
            throw InvalidBsDateException::invalidDay($year, $month, $day, $daysInMonth);
        }

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    public function equals(BsDate $other): bool
    {
        return $this->year === $other->year
            && $this->month === $other->month
            && $this->day === $other->day;
    }

    /**
     * @return array{year: int, month: int, day: int}
     */
    public function toArray(): array
    {
        return ['year' => $this->year, 'month' => $this->month, 'day' => $this->day];
    }

    public function format(?string $format = null, ?string $locale = null): string
    {
        return DateFormatter::forBs($this, $format ?? FormatDefaults::format(), $locale ?? FormatDefaults::locale());
    }

    public function __toString(): string
    {
        return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
    }
}
