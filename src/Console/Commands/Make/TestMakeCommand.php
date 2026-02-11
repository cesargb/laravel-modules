<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\TestMakeCommand as BaseTestMakeCommand;

class TestMakeCommand extends BaseTestMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:test';
}
