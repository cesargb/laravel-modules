<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\CastMakeCommand as BaseCastMakeCommand;

class CastMakeCommand extends BaseCastMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:cast';
}
