<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Exceptions;

final class OutOfRangeException extends NepaliDatePickerException
{
    public static function forBsYear(int $year, int $minYear, int $maxYear): self
    {
        return new self(sprintf(
            'BS year %d is outside supported range (%d-%d).',
            $year,
            $minYear,
            $maxYear
        ));
    }

    public static function resolvedYearBeyondMaximum(int $year, int $maxYear): self
    {
        return new self(sprintf(
            'Resolved BS year %d is beyond the maximum supported year %d.',
            $year,
            $maxYear
        ));
    }

    public static function negativeOffset(): self
    {
        return new self('Resolved date falls before the minimum supported BS year.');
    }
}
