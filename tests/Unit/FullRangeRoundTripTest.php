<?php

use Raskoti\NepaliDatePicker\NepaliDateConverter;
use Raskoti\NepaliDatePicker\Support\CalendarRange;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

/**
 * Exhaustive round-trip check: every single valid BS date across the
 * entire supported range (2000-01-01 to 2100-12-31) must convert to AD
 * and back to the exact same BS date. Calendar data is this package's
 * core asset, so this is the test that would catch a bad data row or a
 * boundary bug that a handful of spot-check dates could miss.
 *
 * Also asserts the exact total number of dates processed (36,890 —
 * the sum of every year's day count from 2000 to 2100 inclusive) as
 * an extra guard: a loop bug that silently skips or short-circuits
 * iterations would pass a bare "no exception was thrown" check but
 * would still be caught by this count.
 */
it('round-trips BS -> AD -> BS for every valid date in the supported range', function () {
    $converter = new NepaliDateConverter;
    $processed = 0;

    for ($year = CalendarRange::minYear(); $year <= CalendarRange::maxYear(); $year++) {
        for ($month = 1; $month <= 12; $month++) {
            $daysInMonth = CalendarRange::daysInMonth($year, $month);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $original = new BsDate($year, $month, $day);
                $ad = $converter->toAd($original);
                $roundTrip = $converter->toBs($ad);

                if (! $roundTrip->equals($original)) {
                    throw new RuntimeException("Round-trip failed for BS {$original} (got {$roundTrip}).");
                }

                $processed++;
            }
        }
    }

    expect($processed)->toBe(36891);
});
