<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;
use App\Enums\NewsLocale;
use App\Enums\ProjectionType;
use App\Enums\VisualizationType;

final class BackofficeDoomsdayOptionService
{
    /** @return array<string, array<int, string>> */
    public function toArray(): array
    {
        return [
            'countdown_severities' => $this->values(CountdownSeverity::cases()),
            'countdown_statuses' => $this->values(CountdownStatus::cases()),
            'projection_types' => $this->values(ProjectionType::cases()),
            'visualization_types' => $this->values(VisualizationType::cases()),
            'news_locales' => $this->values(NewsLocale::cases()),
            'initiative_locales' => $this->values(InitiativeLocale::cases()),
            'initiative_types' => $this->values(InitiativeType::cases()),
            'localized_fields' => ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'],
        ];
    }

    /**
     * @param array<int, \BackedEnum> $cases
     * @return array<int, string>
     */
    private function values(array $cases): array
    {
        return array_map(static fn (\BackedEnum $case): string => (string) $case->value, $cases);
    }
}
