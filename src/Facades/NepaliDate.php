<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Facades;

use Illuminate\Support\Facades\Facade;
use Raskoti\NepaliDatePicker\NepaliDateManager;
use Raskoti\NepaliDatePicker\ValueObjects\AdDate;
use Raskoti\NepaliDatePicker\ValueObjects\BsDate;

/**
 * @method static BsDate parse(string $input)
 * @method static BsDate today()
 * @method static BsDate toBs(AdDate $adDate)
 * @method static AdDate toAd(BsDate $bsDate)
 *
 * @see NepaliDateManager
 */
final class NepaliDate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return NepaliDateManager::class;
    }
}
