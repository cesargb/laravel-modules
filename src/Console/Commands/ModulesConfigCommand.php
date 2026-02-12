<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Composer;
use Cesargb\Modules\Config;
use Illuminate\Console\Command;

class ModulesConfigCommand extends Command
{
    protected $signature = 'modules:config';

    protected $description = 'Configure laravel modules';

    public function handle()
    {
        if (! $this->checkRequirements()) {
            return 1;
        }

        if ($this->repositoryExists()) {
            $this->info('The repository already exists in composer.json.');

            return;
        }

        $this->addRepository();

        $this->info('Repository added to composer.json successfully.');
    }

    private function checkRequirements(): bool
    {
        $this->info('Checking requirements...');

        $allChecksPassed = true;

        $this->line('  - Checking composer.json exists...');
        if (! file_exists(base_path('composer.json'))) {
            $this->error('    ✗ composer.json file not found.');
            $allChecksPassed = false;
        } else {
            $this->info('    ✓ composer.json found.');
        }

        $this->line('  - Checking Composer version...');
        $version = Composer::getVersion();
        $majorVersion = (int) explode('.', $version)[0];

        if ($majorVersion < 2) {
            $this->error('    ✗ Composer version 2 or higher is required. Current version: '.$version);
            $allChecksPassed = false;
        } else {
            $this->info('    ✓ Composer version '.$version.' detected.');
        }

        $this->newLine();

        return $allChecksPassed;
    }

    private function repositoryExists(): bool
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $repositories = $composer['repositories'] ?? [];

        foreach ($repositories as $repository) {
            $type = $repository['type'] ?? null;
            $url = $repository['url'] ?? null;

            if ($type === 'path' && $url === Config::directory().'/*') {
                return true;
            }
        }

        return false;
    }

    private function addRepository()
    {
        $composerPath = base_path('composer.json');
        $composer = json_decode(file_get_contents($composerPath), true);

        $repositories = $composer['repositories'] ?? [];

        $repositories[] = [
            'type' => 'path',
            'url' => Config::directory().'/*',
        ];

        $composer['repositories'] = $repositories;

        file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
