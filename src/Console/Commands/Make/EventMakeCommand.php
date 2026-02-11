<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\EventMakeCommand as BaseEventMakeCommand;

class EventMakeCommand extends BaseEventMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:event';
}
