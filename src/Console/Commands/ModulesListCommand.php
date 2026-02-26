<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Modules;
use Illuminate\Console\Command;

class ModulesListCommand extends Command
{
    protected $signature = 'modules:list';

    protected $description = 'List all available modules';

    public function handle()
    {
        $modules = new Modules()->all();

        if (empty($modules)) {
            $this->components->warn('No modules found.');
            $this->newLine();
            $this->line('  <options=bold>Get started by creating or installing a module:</>');
            $this->newLine();
            $this->line('  <fg=green>Create a new module:</>');
            $this->line('    php artisan modules:create <fg=yellow>vendor/module-name</>');
            $this->newLine();
            $this->line('  <fg=green>Install an existing module:</>');
            $this->line('    php artisan modules:install <fg=yellow>module-name</>');
            $this->newLine();
            $this->line('  <fg=gray>For more information, see the README.md file.</>');
            $this->newLine();

            return self::SUCCESS;
        }
        $this->newLine();
        $this->info('Available modules:');
        $this->newLine();

        foreach ($modules as $module) {
            $this->components->twoColumnDetail(
                "<options=bold>{$module->name}</> <fg=gray>{$module->packageName}:{$module->version} ({$module->origin})</>",
                $module->installed ? '<fg=green;options=bold>INSTALLED</>' : '<fg=yellow>Available</>',
            );
        }

        $this->newLine();

        return self::SUCCESS;
    }
}
