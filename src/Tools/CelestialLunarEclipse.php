<?php

namespace OpenCompany\AiToolCelestial\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use OpenCompany\AiToolCelestial\CelestialService;

class CelestialLunarEclipse implements Tool
{
    public function __construct(
        private CelestialService $service,
        private string $defaultTimezone = 'UTC',
    ) {}

    public function description(): string
    {
        return 'Get lunar eclipse data for a specific date — eclipse type, magnitude, gamma, contact times (P1-P4, U1-U4), and semi-durations.';
    }

    public function handle(Request $request): string
    {
        $date = $request['date'] ?? date('Y-m-d');

        try {
            return $this->service->lunarEclipse($date);
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
                ->description("ISO date (e.g. '2024-09-18'). Defaults to today.")
                ->required(),
        ];
    }
}
