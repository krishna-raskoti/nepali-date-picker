<?php

use Raskoti\NepaliDatePicker\Exceptions\OutOfRangeException;
use Raskoti\NepaliDatePicker\Support\DayCounter;

it('rejects a BS year before the minimum supported year', function () {
    DayCounter::bsOffsetFromBase(1999, 1, 1);
})->throws(OutOfRangeException::class);

it('rejects a negative offset when resolving to BS', function () {
    DayCounter::offsetToBs(-1);
})->throws(OutOfRangeException::class);

it('rejects an offset that resolves beyond the maximum supported BS year', function () {
    // Far larger than any possible span within the supported 2000-2100 range.
    DayCounter::offsetToBs(999_999);
})->throws(OutOfRangeException::class);

it('computes a negative day offset for an AD date before the base reference', function () {
    $beforeBase = new DateTimeImmutable('1900-01-01');

    $offset = DayCounter::adOffsetFromBase($beforeBase);

    expect($offset)->toBeLessThan(0);
});
