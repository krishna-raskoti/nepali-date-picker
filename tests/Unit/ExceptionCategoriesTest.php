<?php

use Raskoti\NepaliDatePicker\Exceptions\InvalidAdDateException;
use Raskoti\NepaliDatePicker\Exceptions\InvalidBsDateException;
use Raskoti\NepaliDatePicker\Exceptions\InvalidLocaleException;
use Raskoti\NepaliDatePicker\Exceptions\OutOfRangeException;
use Raskoti\NepaliDatePicker\Exceptions\ParseException;
use Raskoti\NepaliDatePicker\Parsing\NepaliDateParser;
use Raskoti\NepaliDatePicker\Support\DayCounter;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

/**
 * Consolidated pass over every public error category, one test each,
 * matching the exact scenarios requested during review.
 */
it('invalid separator throws ParseException', function () {
    NepaliDateParser::parseBs('2083|05|12');
})->throws(ParseException::class);

it('Nepali digit input parses after normalization', function () {
    $date = NepaliDateParser::parseBs('२०८३-०५-१२');

    expect((string) $date)->toBe('2083-05-12');
});

it('BS month 13 throws InvalidBsDateException', function () {
    new BsDate(2083, 13, 1);
})->throws(InvalidBsDateException::class);

it('BS day 32 throws InvalidBsDateException', function () {
    new BsDate(2083, 1, 32);
})->throws(InvalidBsDateException::class);

it('BS year 1999 throws OutOfRangeException', function () {
    new BsDate(1999, 1, 1);
})->throws(OutOfRangeException::class);

it('BS year 2101 throws OutOfRangeException', function () {
    new BsDate(2101, 1, 1);
})->throws(OutOfRangeException::class);

it('invalid AD 2026-02-30 throws InvalidAdDateException', function () {
    new AdDate(2026, 2, 30);
})->throws(InvalidAdDateException::class);

it('invalid locale throws InvalidLocaleException', function () {
    $date = new BsDate(2083, 5, 12);
    $date->format('Y-m-d', 'fr');
})->throws(InvalidLocaleException::class);

it('DayCounter::bsOffsetFromBase rejects invalid direct calls immediately', function () {
    DayCounter::bsOffsetFromBase(2083, 99, 500);
})->throws(InvalidBsDateException::class);

it('DayCounter::bsOffsetFromBase rejects an out-of-range year directly (sentinel edge case)', function () {
    // Regression guard: the prefix-sum cache's sentinel entry at maxYear()+1
    // must not allow this to silently succeed.
    DayCounter::bsOffsetFromBase(2101, 1, 1);
})->throws(OutOfRangeException::class);

it('ParseException truncates very long untrusted input in its message', function () {
    $longInput = str_repeat('9', 200);

    try {
        NepaliDateParser::parseBs($longInput);
        $this->fail('Expected ParseException was not thrown.');
    } catch (ParseException $e) {
        expect(strlen($e->getMessage()))->toBeLessThan(150)
            ->and($e->getMessage())->toContain('...(truncated)');
    }
});
