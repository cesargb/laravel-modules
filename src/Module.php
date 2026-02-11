<?php

namespace Cesargb\Modules;

class Module
{
    public function __construct(
        public readonly string $name,
        public readonly string $packageName,
        public readonly string $version,
        public readonly bool $installed,
        public readonly string $namespace
    ) {}

    public function install(): bool
    {
        if ($this->installed) {
            return true;
        }

        return Modules::install($this->name);
    }

    public function uninstall(): bool
    {
        if (! $this->installed) {
            return true;
        }

        return Modules::uninstall($this->name);
    }
}
