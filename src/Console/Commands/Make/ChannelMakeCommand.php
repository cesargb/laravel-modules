<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\ChannelMakeCommand as BaseChannelMakeCommand;

class ChannelMakeCommand extends BaseChannelMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:channel';
}
