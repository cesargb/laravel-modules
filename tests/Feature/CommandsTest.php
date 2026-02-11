<?php

namespace Cesargb\Modules\Tests\Feature;

use Cesargb\Modules\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class CommandsTest extends TestCase
{
    public function test_modules_list_command_is_registered(): void
    {
        $commands = Artisan::all();

        $this->assertArrayHasKey('modules:list', $commands);
    }

    public function test_modules_install_command_is_registered(): void
    {
        $commands = Artisan::all();

        $this->assertArrayHasKey('modules:install', $commands);
    }

    public function test_modules_uninstall_command_is_registered(): void
    {
        $commands = Artisan::all();

        $this->assertArrayHasKey('modules:uninstall', $commands);
    }

    public function test_all_module_make_commands_are_registered(): void
    {
        $commands = Artisan::all();

        $makeCommands = [
            'module:make:command',
            'module:make:cast',
            'module:make:channel',
            'module:make:class',
            'module:make:component',
            'module:make:config',
            'module:make:enum',
            'module:make:event',
            'module:make:exception',
            'module:make:interface',
            'module:make:job',
            'module:make:job-middleware',
            'module:make:listener',
            'module:make:mail',
            'module:make:model',
            'module:make:notification',
            'module:make:observer',
            'module:make:policy',
            'module:make:provider',
            'module:make:request',
            'module:make:resource',
            'module:make:rule',
            'module:make:scope',
            'module:make:test',
            'module:make:trait',
            'module:make:view',
        ];

        foreach ($makeCommands as $command) {
            $this->assertArrayHasKey($command, $commands, "Command {$command} is not registered");
        }
    }

    public function test_modules_list_command_displays_no_modules_message(): void
    {
        $this->artisan('modules:list')
            ->expectsOutput('No modules found.')
            ->assertExitCode(0);
    }
}
