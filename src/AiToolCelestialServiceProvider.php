<?php

namespace OpenCompany\AiToolCelestial;

use Illuminate\Support\ServiceProvider;
use OpenCompany\AiToolCore\Support\ToolProviderRegistry;

class AiToolCelestialServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CelestialService::class);
    }

    public function boot(): void
    {
        // Register with the core tool registry if it's available
        if ($this->app->bound(ToolProviderRegistry::class)) {
            $this->app->make(ToolProviderRegistry::class)
                ->register(new CelestialToolProvider());
        }
    }
}
