<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\EnumMakeCommand as BaseEnumMakeCommand;

class EnumMakeCommand extends BaseEnumMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:enum';
}
