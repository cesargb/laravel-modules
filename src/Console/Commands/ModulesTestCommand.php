<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class ModulesTestCommand extends Command
{
    protected $signature = 'modules:test';

    protected $description = 'Run PHPUnit tests for all local modules';

    public function handle()
    {
        $localModules = $this->getLocalModules();

        if (empty($localModules)) {
            $this->info('No local modules found.');

            return 0;
        }

        $phpunit = base_path('vendor/bin/phpunit');

        if (! file_exists($phpunit)) {
            $this->error('PHPUnit not found at vendor/bin/phpunit.');

            return 1;
        }

        $failed = [];

        foreach ($localModules as $moduleName) {
            $modulePath = Config::path().'/'.$moduleName;
            $phpunitXml = $modulePath.'/phpunit.xml';

            if (! is_dir($modulePath)) {
                $this->warn("Module directory not found: {$modulePath}");

                continue;
            }

            if (! file_exists($phpunitXml)) {
                $this->warn("phpunit.xml not found in module: {$moduleName}");

                continue;
            }

            $this->newLine();
            $this->components->twoColumnDetail(
                "<options=bold>{$moduleName}</>",
                '<fg=yellow>Running tests...</>'
            );

            $result = Process::run([$phpunit, '--configuration', $phpunitXml]);

            $this->line($result->output());

            if (! $result->successful()) {
                $this->line($result->errorOutput());
                $failed[] = $moduleName;
            }
        }

        $this->newLine();

        if (empty($failed)) {
            $this->info('All tests passed.');

            return 0;
        }

        $this->error('Tests failed for: '.implode(', ', $failed));

        return 1;
    }

    private function getLocalModules(): array
    {
        $composerPath = base_path('composer.json');

        if (! file_exists($composerPath)) {
            return [];
        }

        $composer = json_decode(file_get_contents($composerPath), true);

        $modules = $composer['extra']['laravel_modules'] ?? [];

        return array_keys(array_filter($modules, fn ($data) => ($data['origin'] ?? null) === 'local'));
    }
}
