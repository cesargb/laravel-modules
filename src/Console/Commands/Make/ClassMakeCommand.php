<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ClassMakeCommand as BaseClassMakeCommand;

class ClassMakeCommand extends BaseClassMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:class';
}
