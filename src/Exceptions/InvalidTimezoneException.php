<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Exceptions;

final class InvalidTimezoneException extends NepaliDatePickerException
{
    public static function forName(string $timezone): self
    {
        return new self("Invalid timezone '{$timezone}' configured for nepali-date-picker.");
    }
}
