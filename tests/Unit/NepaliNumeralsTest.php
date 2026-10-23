<?php

use Raskoti\NepaliDatePicker\Formatting\NepaliNumerals;

it('converts ASCII digits to Devanagari', function () {
    expect(NepaliNumerals::toDevanagari('2083'))->toBe('२०८३')
        ->and(NepaliNumerals::toDevanagari('0'))->toBe('०');
});

it('leaves non-digit characters unchanged', function () {
    expect(NepaliNumerals::toDevanagari('2083-05-12'))->toBe('२०८३-०५-१२');
});

it('converts Devanagari digits back to ASCII', function () {
    expect(NepaliNumerals::toEnglish('२०८३'))->toBe('2083');
});
