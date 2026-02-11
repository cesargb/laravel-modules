<?php

namespace Cesargb\Modules\Tests\Unit;

use Cesargb\Modules\Config;
use Cesargb\Modules\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function test_can_get_default_modules_directory(): void
    {
        $directory = Config::directory();

        $this->assertEquals('modules', $directory);
    }

    public function test_can_get_modules_path(): void
    {
        $path = Config::path();

        $this->assertStringEndsWith('modules', $path);
        $this->assertStringContainsString(base_path(), $path);
    }
}
