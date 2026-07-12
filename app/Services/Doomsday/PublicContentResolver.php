<?php

declare(strict_types=1);

namespace App\Services\Doomsday;

use Illuminate\Support\Str;

final class PublicContentResolver
{
    public function excerpt(?string $excerpt, ?string $fallback = null): string
    {
        $source = trim((string) $excerpt);
        if ($source === '') {
            $source = trim((string) $fallback);
        }

        $configuredLimit = config('doomsday.content.preview_excerpt_limit', 220);
        $limit = is_numeric($configuredLimit) && (int) $configuredLimit > 0 ? (int) $configuredLimit : 220;

        return Str::limit($source, $limit, '…');
    }

    public function imageUrl(?string $previewImageUrl, ?string $imagePath, string $fallbackPath): string
    {
        $remote = $this->httpsUrl($previewImageUrl);
        if ($remote !== null) {
            return $remote;
        }

        $imagePath = trim((string) $imagePath);
        if ($imagePath !== '' && parse_url($imagePath, PHP_URL_SCHEME) === null && ! str_starts_with($imagePath, '//')) {
            return asset($imagePath);
        }

        return asset($fallbackPath);
    }

    public function httpsUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        return $url !== ''
            && filter_var($url, FILTER_VALIDATE_URL) !== false
            && strtolower((string) parse_url($url, PHP_URL_SCHEME)) === 'https'
                ? $url
                : null;
    }
}
