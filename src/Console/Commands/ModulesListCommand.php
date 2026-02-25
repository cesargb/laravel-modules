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
            $this->info('No modules found.');

            return;
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
