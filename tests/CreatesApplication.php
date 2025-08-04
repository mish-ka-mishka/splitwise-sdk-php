<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\SDKBuilder\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application // @phpstan-ignore-line
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap(); // @phpstan-ignore-line

        return $app;
    }
}
