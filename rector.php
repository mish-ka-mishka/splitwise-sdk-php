<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;

$factory = new Airlst\RectorConfig\Factory([
    'app',
    'bootstrap',
    'build',
    'config',
    'tests',
]);

return $factory
    ->withLaravelRules()
    ->create()
    ->withCache('storage/framework/cache/rector', FileCacheStorage::class);
