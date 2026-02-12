<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Modules;
use Illuminate\Console\Command;

class ModulesInstallCommand extends Command
{
    protected $signature = 'modules:install {module* : The name of the module to install}';

    protected $description = 'Install one or more modules';

    public function handle()
    {
        $moduleNames = $this->argument('module');

        foreach ($moduleNames as $moduleName) {
            $this->installModule($moduleName);
        }
    }

    private function installModule(string $moduleName): void
    {
        $module = Modules::get($moduleName);

        if (! $module) {
            $this->error("Module {$moduleName} not found.");

            return;
        }

        if ($module->installed) {
            $this->info("Module {$moduleName} is already installed.");

            return;
        }

        if (! $module->install()) {
            $this->error("Failed to install module {$moduleName}.");

            return;
        }

        $this->comment("Module {$moduleName} installed successfully.");
    }
}
