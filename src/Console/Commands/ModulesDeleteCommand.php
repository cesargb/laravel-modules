<?php

namespace Cesargb\Modules\Console\Commands;

use Cesargb\Modules\Config;
use Cesargb\Modules\Modules;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModulesDeleteCommand extends Command
{
    protected $signature = 'modules:delete
                                    {module : The name of the module to delete}
                                    {--force : Force deletion without confirmation}';

    protected $description = 'Delete a module, uninstalling it first if needed';

    public function handle()
    {
        $moduleName = $this->argument('module');

        $module = Modules::get($moduleName);

        if (! $module) {
            $this->components->error("Module [{$moduleName}] not found.");

            return self::FAILURE;
        }

        if ($module->installed) {
            $this->components->warn("Module [{$moduleName}] is currently installed.");

            if (! $this->option('force')) {
                if (! $this->confirm("Uninstall and delete module [{$moduleName}]?", false)) {
                    $this->components->info('Deletion cancelled.');

                    return self::SUCCESS;
                }
            }

            if (! $module->uninstall()) {
                $this->components->error("Failed to uninstall module [{$moduleName}]. Deletion cancelled.");

                return self::FAILURE;
            }

            $this->components->info("Module [{$moduleName}] uninstalled successfully.");
        } elseif (! $this->option('force')) {
            if (! $this->confirm("Delete module [{$moduleName}]?", false)) {
                $this->components->info('Deletion cancelled.');

                return self::SUCCESS;
            }
        }

        $modulePath = Config::path().'/'.$moduleName;

        if (! File::deleteDirectory($modulePath)) {
            $this->components->error("Failed to delete module directory [{$modulePath}].");

            return self::FAILURE;
        }

        $this->components->success("Module [{$moduleName}] deleted successfully.");

        return self::SUCCESS;
    }
}
