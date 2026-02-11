<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\JobMakeCommand as BaseJobMakeCommand;

class JobMakeCommand extends BaseJobMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:job';
}
