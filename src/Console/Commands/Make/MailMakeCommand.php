<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\MailMakeCommand as BaseMailMakeCommand;

class MailMakeCommand extends BaseMailMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:mail';
}
