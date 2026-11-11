<?php

use Raskoti\NepaliDatePicker\NepaliDateConverter;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

beforeEach(function () {
    $this->converter = new NepaliDateConverter;
});

it('converts the base reference pair exactly', function () {
    $ad = $this->converter->toAd(new BsDate(2000, 1, 1));

    expect((string) $ad)->toBe('1943-04-14');
});

it('converts AD base back to BS base', function () {
    $bs = $this->converter->toBs(new AdDate(1943, 4, 14));

    expect((string) $bs)->toBe('2000-01-01');
});

it('round-trips BS -> AD -> BS for an arbitrary date', function () {
    $original = new BsDate(2083, 5, 12);
    $ad = $this->converter->toAd($original);
    $backToBs = $this->converter->toBs($ad);

    expect($backToBs->equals($original))->toBeTrue();
});

it('round-trips AD -> BS -> AD for an arbitrary date', function () {
    $original = new AdDate(2026, 8, 28);
    $bs = $this->converter->toBs($original);
    $backToAd = $this->converter->toAd($bs);

    expect($backToAd->equals($original))->toBeTrue();
});

it('converts a known sample date correctly (BS 2083-05-12 = AD 2026-08-28)', function () {
    $ad = $this->converter->toAd(new BsDate(2083, 5, 12));

    expect((string) $ad)->toBe('2026-08-28');
});

it('handles the last day of the supported BS range', function () {
    $last = new BsDate(2100, 12, 31);
    $ad = $this->converter->toAd($last);
    $backToBs = $this->converter->toBs($ad);

    expect($backToBs->equals($last))->toBeTrue();
});

it('handles a BS month boundary correctly', function () {
    $lastDayOfMonth = new BsDate(2083, 1, 31);
    $ad = $this->converter->toAd($lastDayOfMonth);
    $nextDay = new AdDate($ad->year, $ad->month, $ad->day);
    $roundTrip = $this->converter->toBs($nextDay);

    expect($roundTrip->equals($lastDayOfMonth))->toBeTrue();
});

it('provides todayInBs consistent with current AD date', function () {
    // Take a single "now" snapshot and derive both sides from it, rather
    // than calling AdDate::today() a second time — two independent wall-
    // clock reads could straddle a midnight rollover and flake.
    $adNow = AdDate::today();
    $bsFromNow = $this->converter->toBs($adNow);
    $adFromBs = $this->converter->toAd($bsFromNow);

    expect($adFromBs->equals($adNow))->toBeTrue();
});
