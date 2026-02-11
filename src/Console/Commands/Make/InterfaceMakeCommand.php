<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\InterfaceMakeCommand as BaseInterfaceMakeCommand;

class InterfaceMakeCommand extends BaseInterfaceMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:interface';
}
