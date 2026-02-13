<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Composer;
use Cesargb\Modules\Config;
use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Pipeline;

class ModulesConfigCommand extends Command
{
    protected $signature = 'modules:config';

    protected $description = 'Configure laravel modules';

    public function handle()
    {
        $this->newLine();

        $this->info('Checking requirements...');

        $pass = Pipeline::send(true)
            ->through([
                fn ($pass, $next) => $this->validateComposerExists($pass, $next),
                fn ($pass, $next) => $this->validateComposerVersion($pass, $next),
                fn ($pass, $next) => $this->validateRepositoryExists($pass, $next),
                fn ($pass, $next) => $this->validateModuleDirectoryExists($pass, $next),
            ])
            ->thenReturn();

        $this->newLine();

        if ($pass) {
            $this->info('Your application is now configured to use laravel modules!');

            $this->printHelp();
        } else {
            $this->error('Your application does not meet the requirements to use laravel modules. Please address the issues above and run this command again.');
        }

        return 0;
    }

    private function validateComposerExists(bool $pass, Closure $next): bool
    {
        if (! file_exists(base_path('composer.json'))) {
            $pass = false;
        }

        $this->printChecking($pass, 'composer.json file found');

        return $next($pass);
    }

    private function validateComposerVersion(bool $pass, Closure $next): bool
    {
        try {
            $version = Composer::getVersion();
            $majorVersion = (int) explode('.', $version)[0];
            $message = 'Composer version '.$version.' detected.';

            if ($majorVersion < 2) {
                $message = 'Composer version 2 or higher is required. Current version: '.$version;
                $pass = false;
            }
        } catch (\Exception $e) {
            $message = 'Failed to determine Composer version: '.$e->getMessage();
            $pass = false;
        }

        $this->printChecking($pass, $message);

        return $next($pass);
    }

    private function validateRepositoryExists(bool $pass, Closure $next): bool
    {
        $message = 'Path repository for modules already exists in composer.json';

        if (! $this->repositoryExists()) {
            $pass = $this->addRepository();
            $message = $pass
                ? 'Path repository for modules added to composer.json'
                : 'Failed to add path repository for modules to composer.json';
        }

        $this->printChecking($pass, $message);

        return $next($pass);
    }

    private function validateModuleDirectoryExists(bool $pass, Closure $next): bool
    {
        $path = Config::path();
        $directory = Config::directory();

        $message = "Modules directory exists at {$directory}";

        if (! file_exists($path)) {
            $pass = mkdir($path, 0755, true);
            $message = $pass
                ? 'Modules directory created}'
                : "Failed to create modules directory at {$directory}";
        }

        if (is_file($path)) {
            $pass = false;
            $message = "A file exists at the modules directory path ({$directory}). Please remove it and run this command again.";
        }

        $this->printChecking($pass, $message);

        return $next($pass);
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

    private function addRepository(): bool
    {
        $composerPath = base_path('composer.json');
        $composer = json_decode(file_get_contents($composerPath), true);

        $repositories = $composer['repositories'] ?? [];

        $repositories[] = [
            'type' => 'path',
            'url' => Config::directory().'/*',
        ];

        $composer['repositories'] = $repositories;

        $saved = file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $saved !== false;
    }

    private function printChecking(bool $success, string $message)
    {
        if ($success) {
            $this->line("  <fg=green>✓</> <fg=gray>{$message}</>");
        } else {
            $this->line("  <fg=red>✗</> <fg=gray>{$message}</>");
        }
    }

    private function printHelp()
    {
        $this->newLine();
        $this->info('Next steps:');
        $this->newLine();
        $this->line('You can now create a module using the <fg=cyan>modules:make</> command. For example:');
        $this->line('  <fg=cyan>php artisan modules:make cesargb/blog --install</>');
        $this->newLine();
        $this->line('More info: https://github.com/cesargb/laravel-modules');

        $this->newLine();
    }
}
