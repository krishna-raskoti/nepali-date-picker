<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Exceptions;

final class InvalidLocaleException extends NepaliDatePickerException
{
    /**
     * @param  array<int, string>  $supportedLocales
     */
    public static function unsupported(string $locale, array $supportedLocales): self
    {
        return new self(sprintf(
            "Unsupported locale '%s'. Supported locales: %s.",
            $locale,
            implode(', ', $supportedLocales)
        ));
    }
}
