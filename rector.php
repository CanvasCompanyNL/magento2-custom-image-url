<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Helper',
        __DIR__ . '/Model',
        __DIR__ . '/Plugin',
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_83,
    ])
    ->withTypeCoverageLevel(0);
