<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ObserverMakeCommand as BaseObserverMakeCommand;

class ObserverMakeCommand extends BaseObserverMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:observer';
}
