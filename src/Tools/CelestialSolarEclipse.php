<?php

namespace OpenCompany\AiToolCelestial\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use OpenCompany\AiToolCelestial\CelestialService;

class CelestialSolarEclipse implements Tool
{
    public function __construct(
        private CelestialService $service,
        private string $defaultTimezone = 'UTC',
    ) {}

    public function description(): string
    {
        return 'Get solar eclipse data for a specific date and location — eclipse type, obscuration, contacts, and magnitude.';
    }

    public function handle(Request $request): string
    {
        $date = $request['date'] ?? date('Y-m-d');
        $lat = isset($request['latitude']) ? (float) $request['latitude'] : 0;
        $lon = isset($request['longitude']) ? (float) $request['longitude'] : 0;

        try {
            return $this->service->solarEclipse($date, $lat, $lon);
        } catch (\Throwable $e) {
            return "Celestial calculation error: {$e->getMessage()}";
        }
    }

    /** @return array<string, mixed> */
    public function schema(JsonSchema $schema): array
    {
        return [
            'date' => $schema
                ->string()
                ->description("ISO date (e.g. '2024-04-08'). Defaults to today.")
                ->required(),
            'latitude' => $schema
                ->number()
                ->description('Observer latitude (-90 to 90).')
                ->required(),
            'longitude' => $schema
                ->number()
                ->description('Observer longitude (-180 to 180).')
                ->required(),
        ];
    }
}
