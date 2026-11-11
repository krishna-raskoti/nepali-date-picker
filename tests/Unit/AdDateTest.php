<?php

use Raskoti\NepaliDatePicker\Exceptions\InvalidAdDateException;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;

it('constructs a valid AD date', function () {
    $date = new AdDate(2026, 8, 28);

    expect($date->year)->toBe(2026)
        ->and($date->month)->toBe(8)
        ->and($date->day)->toBe(28);
});

it('rejects an invalid calendar date', function () {
    new AdDate(2026, 2, 30);
})->throws(InvalidAdDateException::class);

it('rejects an invalid month', function () {
    new AdDate(2026, 13, 1);
})->throws(InvalidAdDateException::class);

it('correctly identifies a leap-year Feb 29', function () {
    $date = new AdDate(2028, 2, 29);

    expect($date->day)->toBe(29);
});

it('rejects Feb 29 in a non-leap year', function () {
    new AdDate(2026, 2, 29);
})->throws(InvalidAdDateException::class);

it('is immutable: properties are readonly', function () {
    $date = new AdDate(2026, 8, 28);
    $reflection = new ReflectionProperty($date, 'year');

    expect($reflection->isReadOnly())->toBeTrue();
});

it('computes day of week correctly', function () {
    $date = new AdDate(2026, 8, 28);

    expect($date->dayOfWeek())->toBe(5);
});

it('converts to array', function () {
    $date = new AdDate(2026, 8, 28);

    expect($date->toArray())->toBe(['year' => 2026, 'month' => 8, 'day' => 28]);
});

it('toDateTime accepts an explicit timezone, defaulting to PHP\'s default timezone', function () {
    $date = new AdDate(2026, 8, 28);

    $utc = $date->toDateTime(new DateTimeZone('UTC'));
    $kathmandu = $date->toDateTime(new DateTimeZone('Asia/Kathmandu'));

    expect($utc->getTimezone()->getName())->toBe('UTC')
        ->and($kathmandu->getTimezone()->getName())->toBe('Asia/Kathmandu')
        ->and($utc->getTimestamp())->not->toBe($kathmandu->getTimestamp());
});

it('produces today() without error', function () {
    $today = AdDate::today();

    expect($today->year)->toBeGreaterThan(2000);
});
