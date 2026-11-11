<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Parsing;

use Raskoti\NepaliDatePicker\Exceptions\ParseException;
use Raskoti\NepaliDatePicker\Formatting\NepaliNumerals;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

final class NepaliDateParser
{
    private const SEPARATORS = ['-', '/', '.'];

    public static function parseBs(string $input): BsDate
    {
        $original = trim($input);
        $normalized = NepaliNumerals::toEnglish($original);

        $separator = self::detectSeparator($original);
        $segments = array_map('trim', explode($separator, $normalized));

        if (count($segments) !== 3) {
            throw ParseException::wrongSegmentCount($original, count($segments));
        }

        foreach ($segments as $segment) {
            if ($segment === '' || ! ctype_digit($segment)) {
                throw ParseException::nonNumericSegment($original, $segment);
            }
        }

        [$year, $month, $day] = self::resolveOrder($segments, $original);

        return new BsDate($year, $month, $day);
    }

    /**
     * @return non-empty-string
     */
    private static function detectSeparator(string $input): string
    {
        $found = null;

        foreach (self::SEPARATORS as $candidate) {
            if (str_contains($input, $candidate)) {
                if ($found !== null) {
                    throw ParseException::mixedSeparators($input);
                }
                $found = $candidate;
            }
        }

        if ($found === null) {
            throw ParseException::noSeparatorFound($input);
        }

        return $found;
    }

    /**
     * @param  array<int, string>  $segments
     * @return array{0: int, 1: int, 2: int} [year, month, day]
     */
    private static function resolveOrder(array $segments, string $original): array
    {
        $fourDigitPositions = array_keys(array_filter($segments, fn (string $s) => strlen($s) === 4));

        if (count($fourDigitPositions) !== 1) {
            throw ParseException::ambiguousYearPosition($original);
        }

        $yearPos = $fourDigitPositions[0];

        if ($yearPos === 0) {
            return [(int) $segments[0], (int) $segments[1], (int) $segments[2]];
        }

        if ($yearPos === 2) {
            return [(int) $segments[2], (int) $segments[1], (int) $segments[0]];
        }

        throw ParseException::yearInMiddlePosition($original);
    }
}
