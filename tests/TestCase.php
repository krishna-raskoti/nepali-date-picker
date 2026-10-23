<?php

namespace Raskoti\NepaliDatePicker\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Raskoti\NepaliDatePicker\NepaliDatePickerServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            NepaliDatePickerServiceProvider::class,
        ];
    }
}
