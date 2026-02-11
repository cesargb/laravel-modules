<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ExceptionMakeCommand as BaseExceptionMakeCommand;

class ExceptionMakeCommand extends BaseExceptionMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:exception';
}
