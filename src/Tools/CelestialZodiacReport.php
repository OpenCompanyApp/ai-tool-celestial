<?php

namespace OpenCompany\AiToolCelestial\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use OpenCompany\AiToolCelestial\CelestialService;

class CelestialZodiacReport implements Tool
{
    public function __construct(
        private CelestialService $service,
        private string $defaultTimezone = 'UTC',
    ) {}

    public function description(): string
    {
        return 'Get all celestial bodies mapped to zodiac signs with alignments for a given date.';
    }

    public function handle(Request $request): string
    {
        $date = $request['date'] ?? null;

        try {
            return $this->service->zodiacReport($date);
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
