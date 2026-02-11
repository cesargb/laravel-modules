<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ScopeMakeCommand as BaseScopeMakeCommand;

class ScopeMakeCommand extends BaseScopeMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:scope';
}
