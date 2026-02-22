<?php

namespace OpenCompany\AiToolCelestial\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use OpenCompany\AiToolCelestial\CelestialService;

class CelestialTimeInfo implements Tool
{
    public function __construct(
        private CelestialService $service,
        private string $defaultTimezone = 'UTC',
    ) {}

    public function description(): string
    {
        return 'Get astronomical time data — Julian Day, sidereal time (GMST/GAST), and equation of time.';
    }

    public function handle(Request $request): string
    {
        $date = $request['date'] ?? null;

        try {
            return $this->service->timeInfo($date);
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
                ->description("ISO date or datetime (e.g. '2024-06-15'). Defaults to now."),
        ];
    }
}
