<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ConsoleMakeCommand;

class MakeCommand extends ConsoleMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:command';
}
