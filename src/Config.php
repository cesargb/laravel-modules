<?php

namespace Cesargb\Modules;

class Config
{
    public static function directory(): string
    {
        return config('laravel-modules.modules_directory', 'modules');
    }

    public static function path(): string
    {
        return base_path(self::directory());
    }
}
