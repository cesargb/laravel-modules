<?php

namespace Cesargb\Modules\Console\Commands\Make;

use Cesargb\Modules\Console\CommandMakeNamespacePathResolver;
use Illuminate\Foundation\Console\NotificationMakeCommand as BaseNotificationMakeCommand;

class NotificationMakeCommand extends BaseNotificationMakeCommand
{
    use CommandMakeNamespacePathResolver;

    protected $name = 'module:make:notification';
}
