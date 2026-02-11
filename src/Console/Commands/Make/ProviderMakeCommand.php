<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ProviderMakeCommand as BaseProviderMakeCommand;

class ProviderMakeCommand extends BaseProviderMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:provider';
}
