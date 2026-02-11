<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ComponentMakeCommand as BaseComponentMakeCommand;

class ComponentMakeCommand extends BaseComponentMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:component';
}
