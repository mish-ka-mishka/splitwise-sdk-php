<?php

declare(strict_types=1);

$factory = new Airlst\PhpCsFixerConfig\Factory([
    'app',
    'bootstrap',
    'build',
    'config',
    'tests',
]);

return $factory->create()
    ->setCacheFile('storage/framework/cache/php-cs-fixer/.php-cs-fixer.cache');
