<?php

use Raskoti\NepaliDatePicker\Exceptions\InvalidBsDateException;
use Raskoti\NepaliDatePicker\Exceptions\OutOfRangeException;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

it('constructs a valid BS date', function () {
    $date = new BsDate(2083, 5, 12);

    expect($date->year)->toBe(2083)
        ->and($date->month)->toBe(5)
        ->and($date->day)->toBe(12);
});

it('rejects an invalid month', function () {
    new BsDate(2083, 13, 1);
})->throws(InvalidBsDateException::class);

it('rejects a month below range', function () {
    new BsDate(2083, 0, 1);
})->throws(InvalidBsDateException::class);

it('rejects a day beyond the month length', function () {
    new BsDate(2083, 9, 31);
})->throws(InvalidBsDateException::class);

it('rejects a year outside the supported range', function () {
    new BsDate(1999, 1, 1);
})->throws(OutOfRangeException::class);

it('is immutable: properties are readonly', function () {
    $date = new BsDate(2083, 5, 12);
    $reflection = new ReflectionProperty($date, 'year');

    expect($reflection->isReadOnly())->toBeTrue();
});

it('has no public setters', function () {
    $methods = (new ReflectionClass(BsDate::class))->getMethods();
    $setterNames = array_filter(
        array_map(fn ($m) => $m->getName(), $methods),
        fn ($name) => str_starts_with($name, 'set')
    );

    expect($setterNames)->toBeEmpty();
});

it('compares equality correctly', function () {
    $a = new BsDate(2083, 5, 12);
    $b = new BsDate(2083, 5, 12);
    $c = new BsDate(2083, 5, 13);

    expect($a->equals($b))->toBeTrue()
        ->and($a->equals($c))->toBeFalse();
});

it('converts to array', function () {
    $date = new BsDate(2083, 5, 12);

    expect($date->toArray())->toBe(['year' => 2083, 'month' => 5, 'day' => 12]);
});

it('casts to string as Y-m-d', function () {
    $date = new BsDate(2083, 5, 12);

    expect((string) $date)->toBe('2083-05-12');
});
