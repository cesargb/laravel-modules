<?php

namespace Cesargb\Modules;

use Cesargb\Modules\Console\Commands\Make\CastMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ChannelMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ClassMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ComponentMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ConfigMakeCommand;
use Cesargb\Modules\Console\Commands\Make\EnumMakeCommand;
use Cesargb\Modules\Console\Commands\Make\EventMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ExceptionMakeCommand;
use Cesargb\Modules\Console\Commands\Make\InterfaceMakeCommand;
use Cesargb\Modules\Console\Commands\Make\JobMakeCommand;
use Cesargb\Modules\Console\Commands\Make\JobMiddlewareMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ListenerMakeCommand;
use Cesargb\Modules\Console\Commands\Make\MailMakeCommand;
use Cesargb\Modules\Console\Commands\Make\MakeCommand;
use Cesargb\Modules\Console\Commands\Make\ModelMakeCommand;
use Cesargb\Modules\Console\Commands\Make\NotificationMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ObserverMakeCommand;
use Cesargb\Modules\Console\Commands\Make\PolicyMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ProviderMakeCommand;
use Cesargb\Modules\Console\Commands\Make\RequestMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ResourceMakeCommand;
use Cesargb\Modules\Console\Commands\Make\RuleMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ScopeMakeCommand;
use Cesargb\Modules\Console\Commands\Make\TestMakeCommand;
use Cesargb\Modules\Console\Commands\Make\TraitMakeCommand;
use Cesargb\Modules\Console\Commands\Make\ViewMakeCommand;
use Cesargb\Modules\Console\Commands\ModulesInstallCommand;
use Cesargb\Modules\Console\Commands\ModulesListCommand;
use Cesargb\Modules\Console\Commands\ModulesUninstallCommand;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'laravel-modules'
        );
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModulesListCommand::class,
                ModulesInstallCommand::class,
                ModulesUninstallCommand::class,
                MakeCommand::class,
                CastMakeCommand::class,
                ChannelMakeCommand::class,
                ClassMakeCommand::class,
                ComponentMakeCommand::class,
                ConfigMakeCommand::class,
                EnumMakeCommand::class,
                EventMakeCommand::class,
                ExceptionMakeCommand::class,
                InterfaceMakeCommand::class,
                JobMakeCommand::class,
                JobMiddlewareMakeCommand::class,
                ListenerMakeCommand::class,
                MailMakeCommand::class,
                ModelMakeCommand::class,
                NotificationMakeCommand::class,
                ObserverMakeCommand::class,
                PolicyMakeCommand::class,
                ProviderMakeCommand::class,
                RequestMakeCommand::class,
                ResourceMakeCommand::class,
                RuleMakeCommand::class,
                ScopeMakeCommand::class,
                TestMakeCommand::class,
                TraitMakeCommand::class,
                ViewMakeCommand::class,
            ]);
        }
    }
}
