<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\ValueObjects;

use DateTimeImmutable;
use DateTimeZone;
use Raskoti\NepaliDatePicker\Exceptions\InvalidAdDateException;
use Raskoti\NepaliDatePicker\Formatting\DateFormatter;
use Raskoti\NepaliDatePicker\Support\FormatDefaults;

final class AdDate
{
    public readonly int $year;

    public readonly int $month;

    public readonly int $day;

    public function __construct(int $year, int $month, int $day)
    {
        if ($month < 1 || $month > 12) {
            throw InvalidAdDateException::invalidMonth($month);
        }

        if (! checkdate($month, $day, $year)) {
            throw InvalidAdDateException::invalidDate($year, $month, $day);
        }

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    public static function fromDateTime(DateTimeImmutable $date): self
    {
        return new self((int) $date->format('Y'), (int) $date->format('n'), (int) $date->format('j'));
    }

    public static function today(?DateTimeZone $timezone = null): self
    {
        return self::fromDateTime(new DateTimeImmutable('now', $timezone));
    }

    /**
     * Returns this date as midnight on the given timezone.
     *
     * Uses PHP's default timezone when omitted. Pass a DateTimeZone for
     * predictable, environment-independent results.
     */
    public function toDateTime(?DateTimeZone $timezone = null): DateTimeImmutable
    {
        return (new DateTimeImmutable('now', $timezone))
            ->setDate($this->year, $this->month, $this->day)
            ->setTime(0, 0);
    }

    /**
     * Day of week: 0 = Sunday ... 6 = Saturday.
     */
    public function dayOfWeek(): int
    {
        return (int) $this->toDateTime()->format('w');
    }

    public function equals(AdDate $other): bool
    {
        return $this->year === $other->year
            && $this->month === $other->month
            && $this->day === $other->day;
    }

    /**
     * @return array{year: int, month: int, day: int}
     */
    public function toArray(): array
    {
        return ['year' => $this->year, 'month' => $this->month, 'day' => $this->day];
    }

    public function format(?string $format = null, ?string $locale = null): string
    {
        return DateFormatter::forAd($this, $format ?? FormatDefaults::format(), $locale ?? FormatDefaults::locale());
    }

    public function __toString(): string
    {
        return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
    }
}
