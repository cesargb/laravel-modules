<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ViewMakeCommand as BaseViewMakeCommand;

class ViewMakeCommand extends BaseViewMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:view';
}
