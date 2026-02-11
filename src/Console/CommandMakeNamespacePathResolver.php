<?php

namespace Cesargb\Modules\Console;

use Cesargb\Modules\Config;
use Cesargb\Modules\Modules;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

trait CommandMakeNamespacePathResolver
{
    protected function getArguments()
    {
        $arguments = parent::getArguments();

        array_unshift($arguments, [
            'module', InputArgument::REQUIRED, 'The name of the module to create the command in',
        ]);

        return $arguments;
    }

    protected function rootNamespace()
    {
        $moduleName = $this->argument('module');

        $module = Modules::getInstalled($moduleName);

        if (! $module) {
            $this->error("Module {$moduleName} not found.");

            exit(1);
        }

        return $module->namespace;
    }

    protected function getPath($name): string
    {
        $moduleName = $this->argument('module');

        $module = Modules::getInstalled($moduleName);

        if (! $module) {
            $this->error("Module {$moduleName} not found.");

            exit(1);
        }
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return Config::path().'/'.$module->name.'/src/'.str_replace('\\', '/', $name).'.php';
    }
}
