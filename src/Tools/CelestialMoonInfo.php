<?php

namespace OpenCompany\AiToolCelestial\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use OpenCompany\AiToolCelestial\CelestialService;

class CelestialMoonInfo implements Tool
{
    public function __construct(
        private CelestialService $service,
        private string $defaultTimezone = 'UTC',
    ) {}

    public function description(): string
    {
        return 'Get moon position, illumination, and visibility from a specific location.';
    }

    public function handle(Request $request): string
    {
        $date = $request['date'] ?? null;
        $lat = isset($request['latitude']) ? (float) $request['latitude'] : 0;
        $lon = isset($request['longitude']) ? (float) $request['longitude'] : 0;
        $timezone = $request['timezone'] ?? $this->defaultTimezone;

        try {
            return $this->service->moonInfo($date, $lat, $lon, $timezone);
        } catch (\Throwable $e) {
            return "Celestial calculation error: {$e->getMessage()}";
        }
    }

    /** @return array<string, mixed> */
    public function schema(JsonSchema $schema): array
    {
        return [
            'latitude' => $schema
                ->number()
                ->description('Observer latitude (-90 to 90).')
                ->required(),
            'longitude' => $schema
                ->number()
                ->description('Observer longitude (-180 to 180).')
                ->required(),
            'date' => $schema
                ->string()
                ->description("ISO date or datetime (e.g. '2024-06-15'). Defaults to now."),
            'timezone' => $schema
                ->string()
                ->description("Timezone for display (e.g. 'Europe/Amsterdam'). Defaults to org timezone."),
        ];
    }
}
