<?php

use Raskoti\NepaliDatePicker\Exceptions\InvalidBsDateException;
use Raskoti\NepaliDatePicker\Exceptions\OutOfRangeException;
use Raskoti\NepaliDatePicker\Support\CalendarRange;

it('reports the correct min and max supported year', function () {
    expect(CalendarRange::minYear())->toBe(2000)
        ->and(CalendarRange::maxYear())->toBe(2100);
});

it('confirms year support correctly', function () {
    expect(CalendarRange::isYearSupported(2000))->toBeTrue()
        ->and(CalendarRange::isYearSupported(2100))->toBeTrue()
        ->and(CalendarRange::isYearSupported(1999))->toBeFalse()
        ->and(CalendarRange::isYearSupported(2101))->toBeFalse();
});

it('returns days in a known month', function () {
    expect(CalendarRange::daysInMonth(2082, 1))->toBe(31)
        ->and(CalendarRange::daysInMonth(2083, 5))->toBe(31);
});

it('throws OutOfRangeException for an unsupported year', function () {
    CalendarRange::daysInMonth(1999, 1);
})->throws(OutOfRangeException::class);

it('throws InvalidBsDateException for an invalid month', function () {
    CalendarRange::daysInMonth(2083, 13);
})->throws(InvalidBsDateException::class);

it('sums a full year correctly', function () {
    $total = CalendarRange::daysInYear(2083);
    expect($total)->toBeGreaterThanOrEqual(365)->toBeLessThanOrEqual(366);
});
