# Nepali Date Picker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/krishna-raskoti/nepali-date-picker.svg?style=flat-square)](https://packagist.org/packages/krishna-raskoti/nepali-date-picker)
[![GitHub Style Check Action Status](https://github.com/krishna-raskoti/nepali-date-picker/actions/workflows/style-check.yml/badge.svg)](https://github.com/krishna-raskoti/nepali-date-picker/actions?query=workflow%3Astyle-check+branch%3Amain)
[![GitHub Style Check Action Status](https://github.com/krishna-raskoti/nepali-date-picker/actions/workflows/style-check.yml/badge.svg)](https://github.com/krishna-raskoti/nepali-date-picker/actions?query=workflow%3Astyle-check+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/krishna-raskoti/nepali-date-picker.svg?style=flat-square)](https://packagist.org/packages/krishna-raskoti/nepali-date-picker)

Bikram Sambat (BS) ↔ Gregorian (AD) date conversion for Laravel. Strict, immutable value objects, localization, and a fluent formatting API. No database required.

> **This release is a date conversion/formatting library only.** No Blade component, view, or any UI is included yet — despite the package name. A reusable Blade date picker is planned for a future release; until then, `resources/views` and `hasViews()` are intentionally absent from this package.

## Requirements

- PHP 8.2+
- Laravel 12.x or 13.x

## Supported Date Range

**BS 2000-01-01 to BS 2100-12-31**, equivalent to **AD 1943-04-14 to AD 2044-04-12**. Any date outside this range throws `OutOfRangeException`. The range is derived directly from the package's calendar data table — it is not an arbitrary limit.

## Installation

```bash
composer require raskoti/nepali-date-picker
```

Publish the config file:

```bash
php artisan vendor:publish --tag="nepali-date-picker-config"
```

This is the published config:

```php
return [
    // 'en' or 'np'. Used by format() when the locale argument is omitted.
    'default_locale' => 'en',

    // Timezone used when resolving "today". Null uses the Laravel app's
    // own timezone (config('app.timezone')). An invalid timezone name
    // throws InvalidTimezoneException.
    'timezone' => null,

    // Used by format() when the format argument is omitted.
    'default_format' => 'Y-m-d',
];
```
If `default_locale` or `default_format` is set to the wrong type (e.g. accidentally left as `null`, or an array), it's silently replaced with the hardcoded default (`'en'` / `'Y-m-d'`) rather than crashing — this is a type-safety guard, not value validation. A wrong-but-valid-type locale (e.g. `'default_locale' => 'fr'`) is *not* caught here; it throws `InvalidLocaleException` the next time `format()` actually runs.

## Usage

### Converting Dates

```php
use Raskoti\NepaliDatePicker\NepaliDateConverter;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

$converter = new NepaliDateConverter();

$bs = $converter->toBs(new AdDate(2026, 10, 23));
echo $bs; // 2083-07-06

$ad = $converter->toAd(new BsDate(2083, 7, 6));
echo $ad; // 2026-10-23
```

`AdDate::toDateTime(?DateTimeZone $timezone = null)` returns midnight on that date. Without an explicit timezone, it uses PHP's default timezone (`date_default_timezone_get()`) — pass one explicitly if your use case needs a specific, predictable zone rather than whatever the environment happens to default to.

### Using the Facade

```php
use Raskoti\NepaliDatePicker\Facades\NepaliDate;

NepaliDate::today();                 // BsDate for today (uses configured timezone)
NepaliDate::parse('2083-05-12');     // BsDate from a string
NepaliDate::toBs($adDate);
NepaliDate::toAd($bsDate);
```

### Parsing

```php
use Raskoti\NepaliDatePicker\Parsing\NepaliDateParser;

NepaliDateParser::parseBs('2083-05-12');   // YYYY-MM-DD
NepaliDateParser::parseBs('12/05/2083');   // DD-MM-YYYY (auto-detected)
NepaliDateParser::parseBs('2083 / 5 / 12'); // whitespace around separators is fine
```

**Devanagari input is supported directly** — useful when the value comes straight from a Nepali-locale form field:

```php
NepaliDateParser::parseBs('२०८३/०५/१२'); // same as '2083/05/12'
```

Parse errors throw `ParseException`, and always quote back the *original* input you passed in (not a normalized/converted version) — so a user who typed Devanagari digits sees their own input reflected in the error, not an ASCII conversion of it.

**Accepted input, exactly:**
- 3 segments separated by one consistent separator: `-`, `/`, or `.` (mixing separators is rejected)
- Order `YYYY-MM-DD` or `DD-MM-YYYY` — detected by which segment is exactly 4 digits (the year). The year must be the first or last segment, never the middle one.
- Month/day may be 1 or 2 digits — no zero-padding required.
- Whitespace around separators is tolerated. For example, `"2083/5/12"`, `"2083 / 5 / 12"`, `"2083 /5/ 12"` are accepted.
- Devanagari numerals are normalized automatically (`"२०८३/०५/१२"` works).

Anything else throws `ParseException`, quoting your original input back in the message.

### Facade + raw form input

A typical controller pattern: take a raw string from a request (works whether it was typed in English or Devanagari digits), and convert it to AD for storage — the primary real-world use case (BS input from the user → AD date in the database):

```php
use Raskoti\NepaliDatePicker\Facades\NepaliDate;
use Raskoti\NepaliDatePicker\Exceptions\NepaliDatePickerException;

try {
    $bsDate = NepaliDate::parse($request->input('date_of_birth'));
    $adDate = NepaliDate::toAd($bsDate);

    $user->date_of_birth = $adDate->toDateTime(); // store as a native AD date
} catch (NepaliDatePickerException $e) {
    return back()->withErrors(['date_of_birth' => 'Please enter a valid date.']);
}
```

`NepaliDatePickerException` is the abstract base every exception in this package extends, so catching it covers every failure mode (parsing, validation, range, locale, timezone) in one place.

### Storing a converted date

A `date` column has no time-of-day or timezone offset — store the plain string, not a `DateTime` instance, to avoid timezone ambiguity entirely:

Migration:
```php
$table->date('date_of_birth');
```

Model:
```php
protected $casts = [
    'date_of_birth' => 'date',
];
```

Controller:
```php
$bsDate = NepaliDate::parse($request->input('date_of_birth'));
$adDate = NepaliDate::toAd($bsDate);

$user->date_of_birth = $adDate->format('Y-m-d'); // plain string, no timezone involved
$user->save();
```

`AdDate::toDateTime()` is available if you specifically need a `DateTimeImmutable`/Carbon instance for further date arithmetic — but for storage alone, the plain formatted string above is simpler and avoids timezone questions entirely. Note that the native `DateTimeImmutable` has no `__toString()` — `echo $adDate->toDateTime();` will error; use `$adDate->toDateTime()->format('Y-m-d')`, or just `echo $adDate` directly (`AdDate` itself has `__toString()`).

### Formatting

Both `BsDate::format()` and `AdDate::format()` take the format string and locale as **optional** arguments — omit either (or both) to fall back to the configured `default_format` / `default_locale`:

```php
$date = new BsDate(2083, 5, 12);

$date->format();                    // uses config('nepali-date-picker.default_format') and default_locale
$date->format('Y-m-d');             // explicit format, config's default_locale
$date->format('Y-m-d', 'np');       // both explicit
```

`format()` accepts a **supported subset** of PHP-DateTime-style tokens, plus a few `YYYY`/`MM`/`DD` aliases. This is not full parity with PHP's `DateTime::format()` — tokens like `S`, `z`, `W`, `t`, `L`, `c`, `r`, and others are not implemented, since a BS date has no direct equivalent for several of them (e.g. day-of-year, week-of-year, or ISO-8601 string output don't map cleanly onto the BS calendar).

| Token | Meaning |
|---|---|
| `Y` / `YYYY` | 4-digit year |
| `y` | 2-digit year |
| `m` / `MM` | 2-digit month |
| `n` | month, no leading zero |
| `d` / `DD` | 2-digit day |
| `j` | day, no leading zero |
| `F` | month name, full |
| `M` | month name, short (BS has no official short form — falls back to full; AD has no standard Nepali short form either — also falls back to full) |
| `l` | weekday name, full |
| `D` | weekday name, short |
| `E` | weekday name, extra-short (single letter) |
| `N` | ISO weekday number, 1–7 (Mon–Sun) |
| `w` | weekday number, 0–6 (Sun–Sat) |
| `\X` | literal character `X` |

```php
$date->format('l, F j, Y');          // Friday, Kartik 6, 2083
$date->format('l, F j, Y', 'np');    // शुक्रबार, कार्तिक ६, २०८३
```

**Locale** is validated: only `'en'` and `'np'` are accepted. Any other value throws `InvalidLocaleException` — a typo like `'np '` or `'nepali'` fails loudly rather than silently rendering as English.

> A locale-backed PHP enum instead of a plain string is a reasonable future improvement, to make the API typo-proof at the type level rather than at runtime. Not yet implemented, to keep the current string-based API (already used across config and the facade) stable.

AD dates always use Gregorian month names (English or Nepali-translated), never BS month names — these are two separate calendars and share no month-name data.

### Date-object output helpers

Beyond `format()`, both `BsDate` and `AdDate` expose:

- `toArray()` — `['year' => int, 'month' => int, 'day' => int]`
- `equals(self $other)` — `true` if year/month/day all match
- `(string) $date` — always `Y-m-d` in English digits, no locale; a plain, stable identity string, distinct from `format()`'s localized output
- `AdDate::dayOfWeek()` — `int`, `0` (Sunday) through `6` (Saturday)
- `AdDate::toDateTime(?DateTimeZone $timezone = null)` — see above

`BsDate` has no `dayOfWeek()` of its own — use `format('N')` or `format('w')` to get the weekday number for a BS date.

## Exceptions

Every exception extends the abstract `Raskoti\NepaliDatePicker\Exceptions\NepaliDatePickerException`:

| Exception | Thrown when |
|---|---|
| `InvalidBsDateException` | An invalid BS month or day (year in range, but month/day isn't) |
| `InvalidAdDateException` | An invalid Gregorian month or day |
| `OutOfRangeException` | A BS year outside 2000–2100 |
| `ParseException` | A BS date string can't be parsed |
| `InvalidLocaleException` | A `format()` locale other than `'en'`/`'np'` |
| `InvalidTimezoneException` | An invalid timezone string in `config('nepali-date-picker.timezone')` |

## Public API

The supported, stable surface of this package is: **`NepaliDate`** (facade), **`NepaliDateConverter`**, **`BsDate`**, **`AdDate`**, and **`NepaliDateParser`**. Classes under the `Support\` namespace (`DayCounter`, `CalendarRange`) are internal arithmetic engines — marked `@internal` in their docblocks — and may change without a major version bump.

## Testing

```bash
composer test
composer analyse
composer format
```

The test suite includes an exhaustive round-trip check across every single valid date in the supported range (~36,700 dates), plus fixtures cross-checked against independently published BS↔AD equivalences. This makes the full suite noticeably slower than a typical package's tests — that's intentional, given calendar data accuracy is this package's core value.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Krishna Raskoti](https://github.com/krishna-raskoti)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.