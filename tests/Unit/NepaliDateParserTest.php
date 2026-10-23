<?php

use Raskoti\NepaliDatePicker\Exceptions\InvalidBsDateException;
use Raskoti\NepaliDatePicker\Exceptions\ParseException;
use Raskoti\NepaliDatePicker\Parsing\NepaliDateParser;

it('parses YYYY-MM-DD order', function () {
    $date = NepaliDateParser::parseBs('2083-5-12');

    expect((string) $date)->toBe('2083-05-12');
});

it('parses DD-MM-YYYY order', function () {
    $date = NepaliDateParser::parseBs('12/05/2083');

    expect((string) $date)->toBe('2083-05-12');
});

it('accepts dot separator', function () {
    $date = NepaliDateParser::parseBs('2083.05.12');

    expect((string) $date)->toBe('2083-05-12');
});

it('normalizes Devanagari numerals before parsing (YYYY-MM-DD order)', function () {
    $date = NepaliDateParser::parseBs('२०८३-०५-१२');

    expect((string) $date)->toBe('2083-05-12');
});

it('normalizes Devanagari numerals before parsing (DD-MM-YYYY order)', function () {
    $date = NepaliDateParser::parseBs('१२/०५/२०८३');

    expect((string) $date)->toBe('2083-05-12');
});

it('tolerates whitespace around separators', function () {
    $date = NepaliDateParser::parseBs('2083 / 5 / 12');

    expect((string) $date)->toBe('2083-05-12');
});

it('shows the original Devanagari input, not the normalized ASCII version, in error messages', function () {
    try {
        NepaliDateParser::parseBs('२०८३-०५'); // wrong segment count
        test()->fail('Expected ParseException was not thrown.');
    } catch (ParseException $e) {
        expect($e->getMessage())->toContain('२०८३-०५')
            ->not->toContain('2083-05');
    }
});

it('rejects wrong segment count', function () {
    NepaliDateParser::parseBs('2083-05');
})->throws(ParseException::class);

it('rejects mixed separators', function () {
    NepaliDateParser::parseBs('2083-05/12');
})->throws(ParseException::class);

it('rejects ambiguous year position (no 4-digit segment)', function () {
    NepaliDateParser::parseBs('83-05-12');
})->throws(ParseException::class);

it('rejects non-numeric segments', function () {
    NepaliDateParser::parseBs('2083-May-12');
})->throws(ParseException::class);

it('still validates the resulting date via BsDate', function () {
    NepaliDateParser::parseBs('2083-13-01');
})->throws(InvalidBsDateException::class);

it('rejects a 4-digit segment in the middle position', function () {
    NepaliDateParser::parseBs('05-2083-12'); // year can't be the middle segment
})->throws(ParseException::class);

it('rejects input with no recognized separator at all', function () {
    NepaliDateParser::parseBs('20830512');
})->throws(ParseException::class);
