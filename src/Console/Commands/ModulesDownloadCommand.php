<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class ModulesDownloadCommand extends Command
{
    protected $signature = 'modules:download
                                    {repo : The git repository URL}
                                    {--branch= : The branch to download}
                                    {--tag= : The tag to download}
                                    {--name= : The directory name (default: repository name)}';

    protected $description = 'Download code from a git repository into the modules directory';

    public function handle()
    {
        $repo = $this->argument('repo');
        $branch = $this->option('branch');
        $tag = $this->option('tag');
        $name = $this->option('name');

        if ($branch && $tag) {
            $this->error('Cannot specify both --branch and --tag options. Please choose one.');

            return 1;
        }

        if (! $this->isValidGitUrl($repo)) {
            $this->error('Invalid git repository URL.');

            return 1;
        }

        $directoryName = $name ?: $this->extractRepositoryName($repo);

        if (! $directoryName) {
            $this->error('Could not determine repository name. Please specify a directory name using --name option.');

            return 1;
        }

        $targetPath = Config::path().'/'.$directoryName;

        if (is_dir($targetPath)) {
            $this->error("Directory already exists: {$targetPath}");

            return 1;
        }

        $this->info("Downloading repository to {$directoryName}...");

        if (! $this->downloadRepository($repo, $targetPath, $branch, $tag)) {
            $this->error('Failed to download repository.');

            return 1;
        }

        $this->info('Repository downloaded successfully!');
        $this->comment("Location: {$targetPath}");

        $this->registerModuleInComposer($directoryName, $repo);

        if ($this->hasComposerJson($targetPath)) {
            $this->newLine();
            $this->info('Found composer.json in the downloaded module.');
            $this->comment("You can install it using: php artisan modules:install {$directoryName}");
        }

        return 0;
    }

    private function registerModuleInComposer(string $moduleName, string $repo): void
    {
        $composerPath = base_path('composer.json');

        if (! file_exists($composerPath)) {
            $this->warn('composer.json not found, skipping registration.');

            return;
        }

        $composer = json_decode(file_get_contents($composerPath), true);

        if (isset($composer['extra']['laravel_modules'][$moduleName])) {
            return;
        }

        $composer['extra']['laravel_modules'][$moduleName] = [
            'origin' => 'vsc',
            'url' => $repo,
        ];

        file_put_contents(
            $composerPath,
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n"
        );

        $this->line("Registered module {$moduleName} in composer.json");
    }

    private function isValidGitUrl(string $url): bool
    {
        return preg_match('/^(https?:\/\/|git@)[\w\-\.]+/', $url) === 1;
    }

    private function extractRepositoryName(string $repo): ?string
    {
        $pattern = '/\/([^\/]+?)(\.git)?$/';

        if (preg_match($pattern, $repo, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function downloadRepository(string $repo, string $targetPath, ?string $branch, ?string $tag): bool
    {
        $command = ['git', 'clone'];

        if ($branch) {
            $command[] = '--branch';
            $command[] = $branch;
        } elseif ($tag) {
            $command[] = '--branch';
            $command[] = $tag;
        }

        $command[] = $repo;
        $command[] = $targetPath;

        $result = Process::run($command);

        if (! $result->successful()) {
            $this->error($result->errorOutput());

            return false;
        }

        return true;
    }

    private function hasComposerJson(string $path): bool
    {
        return file_exists($path.'/composer.json');
    }
}
