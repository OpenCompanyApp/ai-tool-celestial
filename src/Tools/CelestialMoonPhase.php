<?php

namespace OpenCompany\AiToolCelestial\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use OpenCompany\AiToolCelestial\CelestialService;

class CelestialMoonPhase implements Tool
{
    public function __construct(
        private CelestialService $service,
        private string $defaultTimezone = 'UTC',
    ) {}

    public function description(): string
    {
        return 'Get current moon phase, illumination percentage, age, zodiac sign, and dates of next new/full moon.';
    }

    public function handle(Request $request): string
    {
        $date = $request['date'] ?? null;
        $timezone = $request['timezone'] ?? $this->defaultTimezone;

        try {
            return $this->service->moonPhase($date, $timezone);
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
                ->description("ISO date or datetime (e.g. '2024-06-15' or '2024-06-15 22:00:00'). Defaults to now."),
            'timezone' => $schema
                ->string()
                ->description("Timezone for display (e.g. 'Europe/Amsterdam'). Defaults to org timezone."),
        ];
    }
}
