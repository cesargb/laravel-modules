<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\JobMiddlewareMakeCommand as BaseJobMiddlewareMakeCommand;

class JobMiddlewareMakeCommand extends BaseJobMiddlewareMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:job-middleware';
}
