<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ListenerMakeCommand as BaseListenerMakeCommand;

class ListenerMakeCommand extends BaseListenerMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:listener';
}
