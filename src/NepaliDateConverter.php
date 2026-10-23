<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker;

use Raskoti\NepaliDatePicker\Support\DayCounter;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

final class NepaliDateConverter
{
    /**
     * Convert a Gregorian (AD) date to Bikram Sambat (BS).
     */
    public function toBs(AdDate $adDate): BsDate
    {
        $offset = DayCounter::adOffsetFromBase($adDate->toDateTime());
        $bs = DayCounter::offsetToBs($offset);

        return new BsDate($bs['year'], $bs['month'], $bs['day']);
    }

    /**
     * Convert a Bikram Sambat (BS) date to Gregorian (AD).
     */
    public function toAd(BsDate $bsDate): AdDate
    {
        $offset = DayCounter::bsOffsetFromBase($bsDate->year, $bsDate->month, $bsDate->day);
        $base = DayCounter::baseAdDateTime();
        $target = $base->modify(sprintf('%+d days', $offset));

        return AdDate::fromDateTime($target);
    }

    /**
     * Convenience: BS date for "today". Pass a timezone explicitly, or
     * omit it to use PHP's default timezone.
     */
    public function todayInBs(?\DateTimeZone $timezone = null): BsDate
    {
        return $this->toBs(AdDate::today($timezone));
    }
}
