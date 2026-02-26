<?php

namespace Cesargb\Modules;

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class Composer
{
    public static function require(string $packageName, ?string $version = null): bool
    {
        $app = new Application;
        $app->setAutoExit(false);

        $packageVersion = $version ? "{$packageName}:{$version}" : $packageName;

        $input = new ArrayInput([
            'command' => 'require',
            'packages' => [$packageVersion],
            '--working-dir' => base_path(),
        ]);

        return $app->run($input) === 0;
    }

    public static function remove(string $packageName): bool
    {
        $app = new Application;
        $app->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'remove',
            'packages' => [$packageName],
            '--working-dir' => base_path(),
        ]);

        return $app->run($input) === 0;
    }

    public static function dump(): bool
    {
        $app = new Application;
        $app->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'dump-autoload',
            '--working-dir' => base_path(),
        ]);

        return $app->run($input) === 0;
    }

    public static function getVersion(): string
    {
        $app = new Application;

        return $app->getVersion();
    }

    public static function getLongVersion(): string
    {
        $app = new Application;

        return $app->getLongVersion();
    }
}
