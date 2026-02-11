<?php

namespace Cesargb\Modules\Tests\Unit;

use Cesargb\Modules\Module;
use Cesargb\Modules\Tests\TestCase;

class ModuleTest extends TestCase
{
    public function test_module_can_be_instantiated(): void
    {
        $module = new Module(
            name: 'test-module',
            packageName: 'vendor/test-module',
            version: '^1.0.0',
            installed: false,
            namespace: 'Vendor\\TestModule\\'
        );

        $this->assertEquals('test-module', $module->name);
        $this->assertEquals('vendor/test-module', $module->packageName);
        $this->assertEquals('^1.0.0', $module->version);
        $this->assertFalse($module->installed);
        $this->assertEquals('Vendor\\TestModule\\', $module->namespace);
    }

    public function test_module_installed_returns_true_when_already_installed(): void
    {
        $module = new Module(
            name: 'test-module',
            packageName: 'vendor/test-module',
            version: '^1.0.0',
            installed: true,
            namespace: 'Vendor\\TestModule\\'
        );

        $result = $module->install();

        $this->assertTrue($result);
    }

    public function test_module_uninstall_returns_true_when_not_installed(): void
    {
        $module = new Module(
            name: 'test-module',
            packageName: 'vendor/test-module',
            version: '^1.0.0',
            installed: false,
            namespace: 'Vendor\\TestModule\\'
        );

        $result = $module->uninstall();

        $this->assertTrue($result);
    }
}
