<?php

use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

it('formats BS date with numeric tokens', function () {
    $date = new BsDate(2083, 5, 12);

    expect($date->format('Y-m-d'))->toBe('2083-05-12')
        ->and($date->format('YYYY/MM/DD'))->toBe('2083/05/12')
        ->and($date->format('y-n-j'))->toBe('83-5-12');
});

it('formats BS date with English month/weekday names', function () {
    $date = new BsDate(2083, 5, 12);

    expect($date->format('l, F j, Y'))->toBe('Friday, Bhadra 12, 2083')
        ->and($date->format('D, M j'))->toBe('Fri, Bhadra 12');
});

it('formats BS date with Nepali locale names and numerals', function () {
    $date = new BsDate(2083, 5, 12);

    expect($date->format('l, F j, Y', 'np'))->toBe('शुक्रबार, भदौ १२, २०८३');
});

it('formats BS weekday extra-short token', function () {
    $date = new BsDate(2083, 5, 12);

    expect($date->format('E'))->toBe('F')
        ->and($date->format('E', 'np'))->toBe('शु');
});

it('formats AD date using Gregorian month names, not BS month names', function () {
    $date = new AdDate(2026, 8, 28);

    expect($date->format('l, F j, Y'))->toBe('Friday, August 28, 2026');
});

it('formats AD date in Nepali locale with translated Gregorian month name', function () {
    $date = new AdDate(2026, 8, 28);

    expect($date->format('l, F j, Y', 'np'))->toBe('शुक्रबार, अगस्ट २८, २०२६');
});

it('supports escaped literal characters', function () {
    $date = new BsDate(2083, 5, 12);

    expect($date->format('\\Y Y'))->toBe('Y 2083');
});

it('passes through unrecognized characters literally', function () {
    $date = new BsDate(2083, 5, 12);

    expect($date->format('Y#m#d'))->toBe('2083#05#12');
});

it('formats AD date with short Gregorian month name', function () {
    $date = new AdDate(2026, 8, 28);

    expect($date->format('M'))->toBe('Aug')
        ->and($date->format('M', 'np'))->toBe('अगस्ट'); // no Nepali short form; falls back to full
});

it('formats weekday number tokens', function () {
    $date = new BsDate(2083, 5, 12); // Friday

    expect($date->format('N'))->toBe('5')   // ISO: 1=Mon...7=Sun, Friday=5
        ->and($date->format('w'))->toBe('5') // 0=Sun...6=Sat, Friday=5
        ->and($date->format('w', 'np'))->toBe('५');
});

it('outputs a trailing lone backslash literally without crashing', function () {
    $date = new BsDate(2083, 5, 12);

    // No character follows the backslash, so the escape logic can't
    // consume a next char — it must fall through to the literal branch.
    expect($date->format('Y\\'))->toBe('2083\\');
});
