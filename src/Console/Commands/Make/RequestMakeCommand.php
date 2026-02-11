<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\RequestMakeCommand as BaseRequestMakeCommand;

class RequestMakeCommand extends BaseRequestMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:request';
}
