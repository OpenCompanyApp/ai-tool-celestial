<?php

namespace OpenCompany\AiToolCelestial;

use Laravel\Ai\Contracts\Tool;
use OpenCompany\AiToolCelestial\Tools\QueryCelestial;
use OpenCompany\AiToolCore\Contracts\ToolProvider;

class CelestialToolProvider implements ToolProvider
{
    public function appName(): string
    {
        return 'celestial';
    }

    public function appMeta(): array
    {
        return [
            'label' => 'moon, sun, planets, sky, zodiac, eclipses, time',
            'description' => 'Astronomical calculations and night sky',
            'icon' => 'ph:moon-stars',
            'logo' => 'ph:moon-stars',
        ];
    }

    public function tools(): array
    {
        return [
            'query_celestial' => [
                'class' => QueryCelestial::class,
                'type' => 'read',
                'name' => 'Query Celestial',
                'description' => 'Moon phases, sun/moon positions, planet tracking, night sky reports, zodiac, solar/lunar eclipses, and astronomical time.',
                'icon' => 'ph:moon-stars',
            ],
        ];
    }

    public function isIntegration(): bool
    {
        return true;
    }

    public function createTool(string $class, array $context = []): Tool
    {
        return match ($class) {
            QueryCelestial::class => new QueryCelestial(
                app(CelestialService::class),
                $context['timezone'] ?? 'UTC',
            ),
            default => throw new \RuntimeException("Unknown tool class: {$class}"),
        };
    }
}
