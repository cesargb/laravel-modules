<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ModulesCreateCommand extends Command
{
    protected $signature = 'modules:create
                                    {package : The package name in vendor/package format}
                                    {--install : Install the module after creation}';

    protected $description = 'Create a new module with a predefined structure and optionally install it';

    public function handle()
    {
        $package = $this->argument('package');

        if (! $this->validatePackageFormat($package)) {
            $this->error('Invalid package format. Please use vendor/package format.');

            return 1;
        }

        [$vendor, $moduleName] = explode('/', $package, 2);

        $moduleDirectory = Config::path().'/'.$moduleName;

        if (is_dir($moduleDirectory)) {
            $this->error("Module directory already exists: {$moduleDirectory}");

            return 1;
        }

        $this->info("Creating module {$package}...");

        $replacements = $this->getReplacements($vendor, $moduleName);

        $this->createModuleStructure($moduleDirectory, $replacements);

        $this->info("Module {$package} created successfully!");
        $this->comment("Location: {$moduleDirectory}");

        $this->installModule($moduleName);

        return 0;
    }

    private function installModule(string $moduleName): void
    {
        if (! $this->option('install')) {
            return;
        }

        $this->newLine();
        $this->info("Installing module {$moduleName}...");
        $this->call('modules:install', ['module' => [$moduleName]]);
    }

    private function validatePackageFormat(string $package): bool
    {
        return preg_match('/^[a-z0-9_-]+\/[a-z0-9_-]+$/', $package) === 1;
    }

    private function getReplacements(string $vendor, string $moduleName): array
    {
        $vendorStudly = Str::studly($vendor);
        $moduleNameStudly = Str::studly($moduleName);

        return [
            '{{VENDOR}}' => $vendorStudly,
            '{{VENDOR_LOWER}}' => strtolower($vendor),
            '{{MODULE_NAME}}' => $moduleNameStudly,
            '{{MODULE_NAME_LOWER}}' => strtolower($moduleName),
            '{{COMMAND_NAME}}' => $moduleNameStudly.'Command',
        ];
    }

    private function createModuleStructure(string $moduleDirectory, array $replacements): void
    {
        $stubsPath = dirname(__DIR__, 3).'/stubs';

        $directories = [
            $moduleDirectory,
            $moduleDirectory.'/src',
            $moduleDirectory.'/src/Console',
            $moduleDirectory.'/src/Console/Commands',
            $moduleDirectory.'/config',
        ];

        foreach ($directories as $directory) {
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
                $this->line("Created directory: {$directory}");
            }
        }

        $files = [
            'composer.json.stub' => 'composer.json',
            '.gitignore.stub' => '.gitignore',
            'src/ServiceProvider.php.stub' => 'src/'.$replacements['{{MODULE_NAME}}'].'ServiceProvider.php',
            'src/Console/Commands/Command.php.stub' => 'src/Console/Commands/'.$replacements['{{COMMAND_NAME}}'].'.php',
            'config/config.php.stub' => 'config/config.php',
        ];

        foreach ($files as $stub => $destination) {
            $stubPath = $stubsPath.'/'.$stub;
            $destinationPath = $moduleDirectory.'/'.$destination;

            if (! file_exists($stubPath)) {
                $this->warn("Stub not found: {$stubPath}");

                continue;
            }

            $content = file_get_contents($stubPath);
            $content = str_replace(array_keys($replacements), array_values($replacements), $content);

            file_put_contents($destinationPath, $content);
            $this->line("Created file: {$destinationPath}");
        }
    }
}
