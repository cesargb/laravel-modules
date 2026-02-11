<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ResourceMakeCommand as BaseResourceMakeCommand;

class ResourceMakeCommand extends BaseResourceMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:resource';
}
