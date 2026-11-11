<?php

use Raskoti\NepaliDatePicker\Formatting\LocaleData;

it('has exactly 12 month names in every month array', function () {
    expect(LocaleData::BS_MONTHS_EN_FULL)->toHaveCount(12)
        ->and(LocaleData::BS_MONTHS_NP_FULL)->toHaveCount(12)
        ->and(LocaleData::AD_MONTHS_EN_FULL)->toHaveCount(12)
        ->and(LocaleData::AD_MONTHS_EN_SHORT)->toHaveCount(12)
        ->and(LocaleData::AD_MONTHS_NP_FULL)->toHaveCount(12)
        ->and(LocaleData::AD_MONTHS_NP_SHORT)->toHaveCount(12);
});

it('has exactly 7 weekday names in every weekday array', function () {
    expect(LocaleData::WEEKDAYS_EN_FULL)->toHaveCount(7)
        ->and(LocaleData::WEEKDAYS_EN_SHORT)->toHaveCount(7)
        ->and(LocaleData::WEEKDAYS_EN_EXTRA)->toHaveCount(7)
        ->and(LocaleData::WEEKDAYS_NP_FULL)->toHaveCount(7)
        ->and(LocaleData::WEEKDAYS_NP_SHORT)->toHaveCount(7)
        ->and(LocaleData::WEEKDAYS_NP_EXTRA)->toHaveCount(7);
});

it('has matching full/short/extra-short weekday indexes (English, spot-checked)', function () {
    // Index 0 = Sunday across every English weekday array.
    expect(LocaleData::WEEKDAYS_EN_FULL[0])->toBe('Sunday')
        ->and(LocaleData::WEEKDAYS_EN_SHORT[0])->toBe('Sun')
        ->and(LocaleData::WEEKDAYS_EN_EXTRA[0])->toBe('S');

    // Index 5 = Friday across every English weekday array.
    expect(LocaleData::WEEKDAYS_EN_FULL[5])->toBe('Friday')
        ->and(LocaleData::WEEKDAYS_EN_SHORT[5])->toBe('Fri')
        ->and(LocaleData::WEEKDAYS_EN_EXTRA[5])->toBe('F');
});

it('has matching full/short/extra-short weekday indexes (Nepali, spot-checked)', function () {
    expect(LocaleData::WEEKDAYS_NP_FULL[0])->toBe('आइतबार')
        ->and(LocaleData::WEEKDAYS_NP_SHORT[0])->toBe('आइत')
        ->and(LocaleData::WEEKDAYS_NP_EXTRA[0])->toBe('आ');

    expect(LocaleData::WEEKDAYS_NP_FULL[5])->toBe('शुक्रबार')
        ->and(LocaleData::WEEKDAYS_NP_SHORT[5])->toBe('शुक्र')
        ->and(LocaleData::WEEKDAYS_NP_EXTRA[5])->toBe('शु');
});

it('has matching AD month name indexes across English full/short and Nepali (spot-checked)', function () {
    // Index 0 = January in every AD month array.
    expect(LocaleData::AD_MONTHS_EN_FULL[0])->toBe('January')
        ->and(LocaleData::AD_MONTHS_EN_SHORT[0])->toBe('Jan')
        ->and(LocaleData::AD_MONTHS_NP_FULL[0])->toBe('जनवरी');

    // Index 7 = August in every AD month array.
    expect(LocaleData::AD_MONTHS_EN_FULL[7])->toBe('August')
        ->and(LocaleData::AD_MONTHS_EN_SHORT[7])->toBe('Aug')
        ->and(LocaleData::AD_MONTHS_NP_FULL[7])->toBe('अगस्ट');
});
