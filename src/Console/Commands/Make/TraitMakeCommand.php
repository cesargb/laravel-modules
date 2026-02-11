<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\TraitMakeCommand as BaseTraitMakeCommand;

class TraitMakeCommand extends BaseTraitMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:trait';
}
