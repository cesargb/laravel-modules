<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Config;
use Illuminate\Console\Command;

class ModulesConfigCommand extends Command
{
    protected $signature = 'modules:config';

    protected $description = 'Configure laravel modules';

    public function handle()
    {
        if ($this->repositoryExists()) {
            $this->info('The repository already exists in composer.json.');

            return;
        }

        $this->addRepository();

        $this->info('Repository added to composer.json successfully.');
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
