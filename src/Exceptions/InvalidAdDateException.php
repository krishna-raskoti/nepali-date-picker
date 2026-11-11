<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Exceptions;

final class InvalidAdDateException extends NepaliDatePickerException
{
    public static function invalidMonth(int $month): self
    {
        return new self("Month {$month} is invalid. Must be between 1 and 12.");
    }

    public static function invalidDate(int $year, int $month, int $day): self
    {
        return new self("Invalid AD date: {$year}-{$month}-{$day}.");
    }
}
