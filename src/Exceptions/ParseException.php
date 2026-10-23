<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Exceptions;

final class ParseException extends NepaliDatePickerException
{
    private const MAX_INPUT_LENGTH = 50;

    public static function wrongSegmentCount(string $input, int $count): self
    {
        return new self(sprintf(
            "Could not parse BS date '%s': expected 3 segments, got %d.",
            self::safeInput($input),
            $count
        ));
    }

    public static function mixedSeparators(string $input): self
    {
        return new self(sprintf(
            "Could not parse BS date '%s': mixed separators are not allowed.",
            self::safeInput($input)
        ));
    }

    public static function noSeparatorFound(string $input): self
    {
        return new self(sprintf(
            "Could not parse BS date '%s': no recognized separator (-, /, .) found.",
            self::safeInput($input)
        ));
    }

    public static function nonNumericSegment(string $input, string $segment): self
    {
        return new self(sprintf(
            "Could not parse BS date '%s': segment '%s' is not numeric.",
            self::safeInput($input),
            self::safeInput($segment)
        ));
    }

    public static function ambiguousYearPosition(string $input): self
    {
        return new self(sprintf(
            "Could not parse BS date '%s': exactly one 4-digit year segment is required to determine order.",
            self::safeInput($input)
        ));
    }

    public static function yearInMiddlePosition(string $input): self
    {
        return new self(sprintf(
            "Could not parse BS date '%s': year must be the first or last segment.",
            self::safeInput($input)
        ));
    }

    /**
     * Truncates untrusted input before it's embedded in an exception
     * message, so a very long garbage string can't bloat logs.
     */
    private static function safeInput(string $input): string
    {
        if (mb_strlen($input) <= self::MAX_INPUT_LENGTH) {
            return $input;
        }

        return mb_substr($input, 0, self::MAX_INPUT_LENGTH).'...(truncated)';
    }
}
