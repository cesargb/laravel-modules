<?php

namespace Cesargb\Modules;

class Modules
{
    private static ?array $cachedModules = null;

    /**
     * @return array <int, Module>
     */
    public static function all(): array
    {
        if (self::$cachedModules !== null) {
            return self::$cachedModules;
        }

        $modules = glob(Config::path().'/*/composer.json');

        $installedByName = array_column(self::packagesInstalled(), null, 'name');

        self::$cachedModules = array_map(function ($composerJsonPath) use ($installedByName) {
            $moduleName = basename(dirname($composerJsonPath));
            $package = json_decode(file_get_contents($composerJsonPath), true);
            $namespace = $package['autoload']['psr-4'] ? array_key_first($package['autoload']['psr-4']) : null;

            if (! $namespace) {
                throw new \Exception("Module {$moduleName} does not have a PSR-4 autoload namespace defined.", 1);
            }

            $vendorPackage = $installedByName[$package['name']] ?? null;

            return new Module(
                name: $moduleName,
                packageName: $package['name'],
                version: ($package['version'] ?? false) ? '^'.$package['version'] : '*',
                installed: static::isInstalled($package['name']),
                namespace: $namespace,
                origin: $package['extra']['laravel-module']['origin']
                    ?? $vendorPackage['extra']['laravel-module']['origin']
                    ?? 'download'
            );
        }, $modules ?: []);

        return self::$cachedModules;
    }

    /**
     * @return array <int, Module>
     */
    public static function installed(): array
    {
        return array_filter(static::all(), fn ($module) => $module->installed);
    }

    /**
     * @return array <int, Module>
     */
    public static function uninstalled(): array
    {
        return array_filter(static::all(), fn ($module) => ! $module->installed);
    }

    public static function exists(string $packageName): bool
    {
        $localModules = static::all();

        return in_array($packageName, array_column($localModules, 'name'));
    }

    public static function get(string $name): ?Module
    {
        $modules = static::all();

        foreach ($modules as $module) {
            if ($module->name === $name) {
                return $module;
            }
        }

        return null;
    }

    public static function getInstalled(string $name): ?Module
    {
        $module = static::get($name);

        if ($module && $module->installed) {
            return $module;
        }

        return null;
    }

    public static function isInstalled(string $name): bool
    {
        return in_array($name, array_column(self::packagesInstalled(), 'name'));
    }

    public static function install(string $name): bool
    {
        if (! static::exists($name)) {
            throw new \Exception("Module {$name} does not exist.", 1);
        }

        $module = static::get($name);

        $installed = Composer::require($module->packageName);

        self::$cachedModules = null;

        if ($installed) {
            self::addTestsNamespaceToComposer($module);
        }

        return $installed;
    }

    private static function addTestsNamespaceToComposer(Module $module): void
    {
        if ($module->origin !== 'local') {
            return;
        }

        $appComposerPath = base_path('composer.json');

        if (! file_exists($appComposerPath)) {
            return;
        }

        $appComposer = json_decode(file_get_contents($appComposerPath), true);

        if (! isset($appComposer['extra']['laravel_modules'][$module->name])) {
            return;
        }

        $moduleComposerPath = Config::path().'/'.$module->name.'/composer.json';

        if (! file_exists($moduleComposerPath)) {
            return;
        }

        $moduleComposer = json_decode(file_get_contents($moduleComposerPath), true);
        $testNamespaces = $moduleComposer['autoload-dev']['psr-4'] ?? [];

        if (empty($testNamespaces)) {
            return;
        }

        $moduleRelativePath = Config::directory().'/'.$module->name.'/';

        $modified = false;

        foreach ($testNamespaces as $namespace => $path) {
            $fullPath = $moduleRelativePath.$path;

            if (! isset($appComposer['autoload-dev']['psr-4'][$namespace])) {
                $appComposer['autoload-dev']['psr-4'][$namespace] = $fullPath;
                $modified = true;
            }
        }

        if ($modified) {
            file_put_contents(
                $appComposerPath,
                json_encode($appComposer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n"
            );
        }
    }

    public static function uninstall(string $name): bool
    {
        if (! static::exists($name)) {
            throw new \Exception("Module {$name} does not exist.", 1);
        }

        $module = static::get($name);

        $uninstalled = Composer::remove($module->packageName);

        self::$cachedModules = null;

        if ($uninstalled) {
            self::removeTestsNamespaceFromComposer($module);
        }

        return $uninstalled;
    }

    private static function removeTestsNamespaceFromComposer(Module $module): void
    {
        if ($module->origin !== 'local') {
            return;
        }

        $appComposerPath = base_path('composer.json');

        if (! file_exists($appComposerPath)) {
            return;
        }

        $appComposer = json_decode(file_get_contents($appComposerPath), true);

        if (! isset($appComposer['extra']['laravel_modules'][$module->name])) {
            return;
        }

        $moduleComposerPath = Config::path().'/'.$module->name.'/composer.json';

        if (! file_exists($moduleComposerPath)) {
            return;
        }

        $moduleComposer = json_decode(file_get_contents($moduleComposerPath), true);
        $testNamespaces = $moduleComposer['autoload-dev']['psr-4'] ?? [];

        if (empty($testNamespaces)) {
            return;
        }

        $modified = false;

        foreach ($testNamespaces as $namespace => $path) {
            if (isset($appComposer['autoload-dev']['psr-4'][$namespace])) {
                unset($appComposer['autoload-dev']['psr-4'][$namespace]);
                $modified = true;
            }
        }

        if ($modified) {
            file_put_contents(
                $appComposerPath,
                json_encode($appComposer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n"
            );
        }
    }

    private static function packagesInstalled(): array
    {
        $installedFile = base_path('vendor/composer/installed.json');

        if (! file_exists($installedFile)) {
            return [];
        }

        $installed = json_decode(file_get_contents($installedFile), true);

        return array_filter($installed['packages'] ?? $installed, function ($package) {
            $type = $package['dist']['type'] ?? null;
            $url = $package['dist']['url'] ?? null;

            if ($type !== 'path') {
                return false;
            }

            if (str_starts_with($url, 'modules/')) {
                return true;
            }

            return false;
        });
    }
}
