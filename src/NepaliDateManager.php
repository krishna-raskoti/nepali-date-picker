<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker;

use DateTimeZone;
use Raskoti\NepaliDatePicker\Exceptions\InvalidTimezoneException;
use Raskoti\NepaliDatePicker\Parsing\NepaliDateParser;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;
use Throwable;

/**
 * The class bound behind the NepaliDate facade. Combines the converter
 * and parser into the primary entry points a Laravel app reaches for:
 * parse(), today(), plus the strict toBs()/toAd() pair.
 */
final class NepaliDateManager
{
    public function __construct(
        private readonly NepaliDateConverter $converter
    ) {}

    /**
     * Parse a BS date string (e.g. "2083-05-12" or "12/05/2083") into a BsDate.
     */
    public function parse(string $input): BsDate
    {
        return NepaliDateParser::parseBs($input);
    }

    /**
     * Today's date, in BS. Resolves the timezone from the package config
     * (nepali-date-picker.timezone), falling back to the Laravel app's
     * own timezone (config('app.timezone')) if not set.
     */
    public function today(): BsDate
    {
        return $this->converter->todayInBs($this->resolveTimezone());
    }

    public function toBs(AdDate $adDate): BsDate
    {
        return $this->converter->toBs($adDate);
    }

    public function toAd(BsDate $bsDate): AdDate
    {
        return $this->converter->toAd($bsDate);
    }

    private function resolveTimezone(): DateTimeZone
    {
        $timezoneName = config('nepali-date-picker.timezone') ?? config('app.timezone');

        if (! is_string($timezoneName) || $timezoneName === '') {
            throw InvalidTimezoneException::forName(
                is_scalar($timezoneName) ? var_export($timezoneName, true) : gettype($timezoneName)
            );
        }

        try {
            return new DateTimeZone($timezoneName);
        } catch (Throwable) {
            throw InvalidTimezoneException::forName($timezoneName);
        }
    }
}
