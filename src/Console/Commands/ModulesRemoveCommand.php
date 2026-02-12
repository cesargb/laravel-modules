<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Config;
use Cesargb\Modules\Modules;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModulesRemoveCommand extends Command
{
    protected $signature = 'modules:remove
                                    {module* : The name of the module to remove}
                                    {--force : Force removal without confirmation}';

    protected $description = 'Remove one or more modules from the modules directory';

    public function handle()
    {
        $moduleNames = $this->argument('module');

        foreach ($moduleNames as $moduleName) {
            $this->removeModule($moduleName);
        }

        return 0;
    }

    private function removeModule(string $moduleName): void
    {
        $module = Modules::get($moduleName);

        if (! $module) {
            $this->error("Module {$moduleName} not found.");

            return;
        }

        $modulePath = Config::path().'/'.$moduleName;

        if (! is_dir($modulePath)) {
            $this->error("Module directory does not exist: {$modulePath}");

            return;
        }

        if (! $this->option('force')) {
            if (! $this->confirm("Are you sure you want to remove module {$moduleName}?", false)) {
                $this->comment('Module removal cancelled.');

                return;
            }
        }

        if ($module->installed) {
            $this->info("Module {$moduleName} is installed. Uninstalling first...");

            if (! $module->uninstall()) {
                $this->error("Failed to uninstall module {$moduleName}. Removal cancelled.");

                return;
            }

            $this->comment("Module {$moduleName} uninstalled successfully.");
        }

        $this->info("Removing module directory: {$modulePath}");

        if (! File::deleteDirectory($modulePath)) {
            $this->error("Failed to remove module directory: {$modulePath}");

            return;
        }

        $this->info("Module {$moduleName} removed successfully.");
    }
}
