<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use Illuminate\Validation\ValidationException;

final class BackofficeDoomsdayInputNormalizer
{
    /** @param array<string, mixed> $value @return array<string, string> */
    public function localizedText(array $value, string $field): array
    {
        $normalized = $this->optionalLocalizedText($value) ?? [];

        if (($normalized['en'] ?? '') === '') {
            throw ValidationException::withMessages([$field . '.en' => 'The English value is required.']);
        }

        return $normalized;
    }

    /** @param array<string, mixed>|null $value @return array<string, string>|null */
    public function optionalLocalizedText(?array $value): ?array
    {
        if ($value === null) {
            return null;
        }

        $normalized = [];
        foreach ($value as $locale => $text) {
            if (! is_string($locale) || ! is_scalar($text)) {
                continue;
            }

            $trimmed = trim((string) $text);
            if ($trimmed !== '') {
                $normalized[$locale] = $trimmed;
            }
        }

        return $normalized === [] ? null : $normalized;
    }

    /** @param array<string, mixed>|null $value @return array<string, array<int, string>>|null */
    public function localizedList(?array $value): ?array
    {
        if ($value === null) {
            return null;
        }

        $normalized = [];
        foreach ($value as $locale => $items) {
            if (! is_string($locale) || ! is_array($items)) {
                continue;
            }

            $strings = array_values(array_filter(
                array_map(static fn (mixed $item): string => trim((string) $item), $items),
                static fn (string $item): bool => $item !== '',
            ));

            if ($strings !== []) {
                $normalized[$locale] = $strings;
            }
        }

        return $normalized === [] ? null : $normalized;
    }

    public function nullableString(?string $value): ?string
    {
        $value = $value !== null ? trim($value) : null;

        return $value !== '' ? $value : null;
    }

    public function nullableDate(?string $value): ?string
    {
        return $this->nullableString($value);
    }
}
