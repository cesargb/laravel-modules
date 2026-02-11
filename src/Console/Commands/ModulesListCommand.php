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

        $this->table(
            ['Name', 'Package', 'Version', 'Installed'],
            array_map(function ($module) {
                return [
                    $module->name,
                    $module->packageName,
                    $module->version,
                    $module->installed ? '<fg=green>✓</>' : '<fg=red>✗</>',
                ];
            }, $modules)
        );
    }
}
