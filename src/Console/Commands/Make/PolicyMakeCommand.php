<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\PolicyMakeCommand as BasePolicyMakeCommand;

class PolicyMakeCommand extends BasePolicyMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:policy';
}
