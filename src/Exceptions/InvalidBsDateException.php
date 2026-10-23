<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Exceptions;

final class InvalidBsDateException extends NepaliDatePickerException
{
    public static function invalidMonth(int $month): self
    {
        return new self("Month {$month} is invalid. Must be between 1 and 12.");
    }

    public static function invalidDay(int $year, int $month, int $day, int $daysInMonth): self
    {
        return new self("Day {$day} is invalid for BS {$year}-{$month} (has {$daysInMonth} days).");
    }
}
