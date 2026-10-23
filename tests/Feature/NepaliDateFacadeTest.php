<?php

use Raskoti\NepaliDatePicker\Exceptions\InvalidTimezoneException;
use Raskoti\NepaliDatePicker\Facades\NepaliDate;
use Raskoti\NepaliDatePicker\NepaliDateConverter;
use Raskoti\NepaliDatePicker\NepaliDateManager;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

it('resolves NepaliDateManager from the container', function () {
    expect($this->app->make(NepaliDateManager::class))->toBeInstanceOf(NepaliDateManager::class);
});

it('facade parses a BS date string', function () {
    $date = NepaliDate::parse('2083-05-12');

    expect($date)->toBeInstanceOf(BsDate::class)
        ->and((string) $date)->toBe('2083-05-12');
});

it('facade returns today in BS', function () {
    $today = NepaliDate::today();

    expect($today)->toBeInstanceOf(BsDate::class);
});

it('facade converts AD to BS', function () {
    $bs = NepaliDate::toBs(new AdDate(2026, 10, 23));

    expect((string) $bs)->toBe('2083-07-06');
});

it('facade converts BS to AD', function () {
    $ad = NepaliDate::toAd(new BsDate(2083, 07, 06));

    expect((string) $ad)->toBe('2026-10-23');
});

it('loads the package config', function () {
    expect(config('nepali-date-picker.default_locale'))->toBe('en')
        ->and(config('nepali-date-picker.default_format'))->toBe('Y-m-d');
});

it('facade today() respects an explicit configured timezone', function () {
    config(['nepali-date-picker.timezone' => 'Asia/Kathmandu']);

    $today = NepaliDate::today();
    $expected = (new NepaliDateConverter)
        ->todayInBs(new DateTimeZone('Asia/Kathmandu'));

    expect($today->equals($expected))->toBeTrue();
});

it('BsDate::format() uses config default_locale when locale is omitted', function () {
    config(['nepali-date-picker.default_locale' => 'np']);

    $date = new BsDate(2083, 5, 12);

    expect($date->format('l, F j, Y'))->toBe('शुक्रबार, भदौ १२, २०८३');
});

it('BsDate::format() uses config default_format when format is omitted', function () {
    config(['nepali-date-picker.default_format' => 'YYYY/MM/DD']);

    $date = new BsDate(2083, 5, 12);

    expect($date->format())->toBe('2083/05/12');
});

it('AdDate::format() uses config defaults when both are omitted', function () {
    config([
        'nepali-date-picker.default_locale' => 'np',
        'nepali-date-picker.default_format' => 'YYYY-MM-DD',
    ]);

    $date = new AdDate(2026, 8, 28);

    expect($date->format())->toBe('२०२६-०८-२८');
});

it('facade today() throws InvalidTimezoneException for a garbage configured timezone', function () {
    config(['nepali-date-picker.timezone' => 'Not/A/Real/Zone']);

    try {
        NepaliDate::today();
        test()->fail('Expected InvalidTimezoneException was not thrown.');
    } catch (InvalidTimezoneException $e) {
        expect($e->getMessage())->toContain('Not/A/Real/Zone');
    }
});

it('BsDate::format() falls back to en when default_locale is explicitly null in config', function () {
    config(['nepali-date-picker.default_locale' => null]);

    $date = new BsDate(2083, 5, 12);

    expect($date->format('l, F j, Y'))->toBe('Friday, Bhadra 12, 2083');
});

it('BsDate::format() falls back to Y-m-d when default_format is explicitly null in config', function () {
    config(['nepali-date-picker.default_format' => null]);

    $date = new BsDate(2083, 5, 12);

    expect($date->format())->toBe('2083-05-12');
});

it('facade today() throws InvalidTimezoneException when timezone config is false', function () {
    config(['nepali-date-picker.timezone' => false]);

    try {
        NepaliDate::today();
        test()->fail('Expected InvalidTimezoneException was not thrown.');
    } catch (InvalidTimezoneException $e) {
        expect($e->getMessage())->not->toBe('');
    }
});

it('facade today() throws InvalidTimezoneException when timezone config is an array', function () {
    config(['nepali-date-picker.timezone' => ['not', 'a', 'string']]);

    try {
        NepaliDate::today();
        test()->fail('Expected InvalidTimezoneException was not thrown.');
    } catch (InvalidTimezoneException $e) {
        expect($e->getMessage())->toContain('array');
    }
});

it('facade today() throws InvalidTimezoneException when timezone config is an empty string', function () {
    config(['nepali-date-picker.timezone' => '']);

    try {
        NepaliDate::today();
        test()->fail('Expected InvalidTimezoneException was not thrown.');
    } catch (InvalidTimezoneException $e) {
        expect($e->getMessage())->not->toBe('');
    }
});

it('BsDate::format() falls back to en when default_locale config is the wrong type (an integer)', function () {
    config(['nepali-date-picker.default_locale' => 42]);

    $date = new BsDate(2083, 5, 12);

    expect($date->format('l, F j, Y'))->toBe('Friday, Bhadra 12, 2083');
});

it('BsDate::format() falls back to Y-m-d when default_format config is the wrong type (an array)', function () {
    config(['nepali-date-picker.default_format' => ['not', 'a', 'string']]);

    $date = new BsDate(2083, 5, 12);

    expect($date->format())->toBe('2083-05-12');
});
