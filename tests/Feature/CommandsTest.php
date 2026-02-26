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
            ->expectsOutputToContain('No modules found.')
            ->assertExitCode(0);
    }

    public function test_modules_config_command_is_registered(): void
    {
        $commands = Artisan::all();

        $this->assertArrayHasKey('modules:config', $commands);
    }

    public function test_modules_config_command_adds_repository_when_not_exists(): void
    {
        $composerPath = base_path('composer.json');
        $originalContent = file_get_contents($composerPath);

        // Asegurar que no existe el repositorio
        $composer = json_decode($originalContent, true);
        if (isset($composer['repositories'])) {
            $composer['repositories'] = array_filter($composer['repositories'], function ($repo) {
                return ! ($repo['type'] === 'path' && $repo['url'] === 'modules/*');
            });
        }
        file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->artisan('modules:config')
            ->assertExitCode(0);

        $updatedComposer = json_decode(file_get_contents($composerPath), true);
        $repositories = $updatedComposer['repositories'] ?? [];

        $found = false;
        foreach ($repositories as $repository) {
            if ($repository['type'] === 'path' && $repository['url'] === 'modules/*') {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, 'Repository was not added to composer.json');

        file_put_contents($composerPath, $originalContent);
    }

    public function test_modules_config_command_does_not_add_repository_when_already_exists(): void
    {
        $composerPath = base_path('composer.json');
        $originalContent = file_get_contents($composerPath);

        $composer = json_decode($originalContent, true);
        $repositories = $composer['repositories'] ?? [];

        $exists = false;
        foreach ($repositories as $repository) {
            if ($repository['type'] === 'path' && $repository['url'] === 'modules/*') {
                $exists = true;
                break;
            }
        }

        if (! $exists) {
            $repositories[] = [
                'type' => 'path',
                'url' => 'modules/*',
            ];
            $composer['repositories'] = $repositories;
            file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        $countBefore = count(json_decode(file_get_contents($composerPath), true)['repositories'] ?? []);

        $this->artisan('modules:config')
            ->assertExitCode(0);

        $countAfter = count(json_decode(file_get_contents($composerPath), true)['repositories'] ?? []);

        $this->assertEquals($countBefore, $countAfter, 'Repository count should not change');

        file_put_contents($composerPath, $originalContent);
    }
}
