<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\RuleMakeCommand as BaseRuleMakeCommand;

class RuleMakeCommand extends BaseRuleMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:rule';
}
