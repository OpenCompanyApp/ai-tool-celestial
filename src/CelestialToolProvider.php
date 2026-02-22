<?php

namespace OpenCompany\AiToolCelestial;

use Laravel\Ai\Contracts\Tool;
use OpenCompany\AiToolCelestial\Tools\CelestialLunarEclipse;
use OpenCompany\AiToolCelestial\Tools\CelestialMoonInfo;
use OpenCompany\AiToolCelestial\Tools\CelestialMoonPhase;
use OpenCompany\AiToolCelestial\Tools\CelestialNightSky;
use OpenCompany\AiToolCelestial\Tools\CelestialPlanetPosition;
use OpenCompany\AiToolCelestial\Tools\CelestialSolarEclipse;
use OpenCompany\AiToolCelestial\Tools\CelestialSunInfo;
use OpenCompany\AiToolCelestial\Tools\CelestialTimeInfo;
use OpenCompany\AiToolCelestial\Tools\CelestialZodiacReport;
use OpenCompany\IntegrationCore\Contracts\ToolProvider;

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
            'celestial_moon_phase' => [
                'class' => CelestialMoonPhase::class,
                'type' => 'read',
                'name' => 'Moon Phase',
                'description' => 'Current moon phase, illumination, age, and next new/full moon.',
                'icon' => 'ph:moon',
            ],
            'celestial_sun_info' => [
                'class' => CelestialSunInfo::class,
                'type' => 'read',
                'name' => 'Sun Info',
                'description' => 'Sunrise/sunset, altitude, twilight, and day length for a location.',
                'icon' => 'ph:sun',
            ],
            'celestial_moon_info' => [
                'class' => CelestialMoonInfo::class,
                'type' => 'read',
                'name' => 'Moon Info',
                'description' => 'Moon position, illumination, and visibility from a location.',
                'icon' => 'ph:moon-stars',
            ],
            'celestial_planet_position' => [
                'class' => CelestialPlanetPosition::class,
                'type' => 'read',
                'name' => 'Planet Position',
                'description' => 'Planet altitude, azimuth, zodiac position, and rise/set times.',
                'icon' => 'ph:planet',
            ],
            'celestial_solar_eclipse' => [
                'class' => CelestialSolarEclipse::class,
                'type' => 'read',
                'name' => 'Solar Eclipse',
                'description' => 'Solar eclipse type, obscuration, contacts, and magnitude.',
                'icon' => 'ph:sun-dim',
            ],
            'celestial_lunar_eclipse' => [
                'class' => CelestialLunarEclipse::class,
                'type' => 'read',
                'name' => 'Lunar Eclipse',
                'description' => 'Lunar eclipse type, magnitude, gamma, and contact times.',
                'icon' => 'ph:moon',
            ],
            'celestial_night_sky' => [
                'class' => CelestialNightSky::class,
                'type' => 'read',
                'name' => 'Night Sky',
                'description' => 'What\'s visible now: positions, darkness, and stargazing quality.',
                'icon' => 'ph:star',
            ],
            'celestial_zodiac_report' => [
                'class' => CelestialZodiacReport::class,
                'type' => 'read',
                'name' => 'Zodiac Report',
                'description' => 'All celestial bodies mapped to zodiac signs with alignments.',
                'icon' => 'ph:shooting-star',
            ],
            'celestial_time_info' => [
                'class' => CelestialTimeInfo::class,
                'type' => 'read',
                'name' => 'Astronomical Time',
                'description' => 'Julian Day, sidereal time, and equation of time.',
                'icon' => 'ph:clock',
            ],
        ];
    }

    public function isIntegration(): bool
    {
        return true;
    }

    /** @param  array<string, mixed>  $context */
    public function createTool(string $class, array $context = []): Tool
    {
        $service = app(CelestialService::class);

        return new $class($service, $context['timezone'] ?? 'UTC');
    }
}
