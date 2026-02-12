<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Modules;
use Illuminate\Console\Command;

class ModulesUninstallCommand extends Command
{
    protected $signature = 'modules:uninstall {module* : The name of the module to uninstall}';

    protected $description = 'Uninstall one or more modules';

    public function handle()
    {
        ini_set('memory_limit', '512M');

        $moduleNames = $this->argument('module');

        foreach ($moduleNames as $moduleName) {
            $this->uninstallModule($moduleName);
        }
    }

    private function uninstallModule(string $moduleName): void
    {
        $module = Modules::get($moduleName);

        if (! $module) {
            $this->error("Module {$moduleName} not found.");

            return;
        }

        if (! $module->installed) {
            $this->info("Module {$moduleName} is not installed.");

            return;
        }

        if (! $module->uninstall()) {
            $this->error("Failed to uninstall module {$moduleName}.");

            return;
        }

        $this->comment("Module {$moduleName} uninstalled successfully.");
    }
}
