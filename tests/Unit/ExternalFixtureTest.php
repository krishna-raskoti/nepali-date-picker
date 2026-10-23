<?php

use Raskoti\NepaliDatePicker\NepaliDateConverter;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

/**
 * Cross-checks BS↔AD conversion against independently verified Nepali
 * New Year dates. Internal round-trip tests alone cannot detect a
 * systematic calendar-data shift, so these fixtures validate results
 * against external sources.
 *
 * Sources:
 * - BS 2075-01-01 = AD 2018-04-14: [widely reported Nepali New Year
 *   coverage from 2018]
 * - BS 2083-01-01 = AD 2026-04-14: [reported by multiple independent
 *   2026 sources]
 *
 * Range boundaries are not external fixtures because they are derived
 * from this package's own calendar data; boundary behavior is tested
 * separately.
 */
it('matches the independently verified Nepali New Year BS 2075-01-01 = AD 2018-04-14', function () {
    $converter = new NepaliDateConverter;

    $ad = $converter->toAd(new BsDate(2075, 1, 1));

    expect((string) $ad)->toBe('2018-04-14');
});

it('matches the independently verified Nepali New Year BS 2083-01-01 = AD 2026-04-14', function () {
    $converter = new NepaliDateConverter;

    $ad = $converter->toAd(new BsDate(2083, 1, 1));

    expect((string) $ad)->toBe('2026-04-14');
});

it('matches the reverse direction for both fixtures', function () {
    $converter = new NepaliDateConverter;

    expect((string) $converter->toBs(new AdDate(2018, 4, 14)))->toBe('2075-01-01')
        ->and((string) $converter->toBs(new AdDate(2026, 4, 14)))->toBe('2083-01-01');
});
