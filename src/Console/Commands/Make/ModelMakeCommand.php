<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ModelMakeCommand as BaseModelMakeCommand;

class ModelMakeCommand extends BaseModelMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:model';
}
