<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ConfigMakeCommand as BaseConfigMakeCommand;

class ConfigMakeCommand extends BaseConfigMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:config';
}
