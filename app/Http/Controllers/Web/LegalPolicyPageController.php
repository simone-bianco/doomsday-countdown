<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Doomsday\Locale\PublicLocaleResolver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class LegalPolicyPageController extends Controller
{
    /** @var array<string, array{label: string, native: string, flag: string}> */
    private const LANGUAGES = [
        'en' => ['label' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
        'it' => ['label' => 'Italiano', 'native' => 'Italiano', 'flag' => '🇮🇹'],
        'fr' => ['label' => 'Français', 'native' => 'Français', 'flag' => '🇫🇷'],
        'de' => ['label' => 'Deutsch', 'native' => 'Deutsch', 'flag' => '🇩🇪'],
        'es' => ['label' => 'Español', 'native' => 'Español', 'flag' => '🇪🇸'],
        'nl' => ['label' => 'Nederlands', 'native' => 'Nederlands', 'flag' => '🇳🇱'],
        'sv' => ['label' => 'Svenska', 'native' => 'Svenska', 'flag' => '🇸🇪'],
        'pl' => ['label' => 'Polski', 'native' => 'Polski', 'flag' => '🇵🇱'],
    ];

    public function privacy(Request $request, PublicLocaleResolver $localeResolver): Response
    {
        return $this->render($request, $localeResolver, 'privacy');
    }

    public function cookies(Request $request, PublicLocaleResolver $localeResolver): Response
    {
        return $this->render($request, $localeResolver, 'cookies');
    }

    private function render(Request $request, PublicLocaleResolver $localeResolver, string $kind): Response
    {
        $locale = $localeResolver->locale($request);

        return Inertia::render('Doomsday/LegalPolicy', [
            'page' => [
                'kind' => $kind,
                'title' => $this->title($kind, $locale),
                'current_locale' => $locale,
                'languages' => $this->languageOptions($locale, $request->path()),
            ],
        ]);
    }

    /** @return array<int, array<string, mixed>> */
    private function languageOptions(string $currentLocale, string $currentPath): array
    {
        $path = '/'.ltrim($currentPath, '/');

        return collect(self::LANGUAGES)
            ->map(fn (array $language, string $code): array => [
                'code' => $code,
                'label' => $language['label'],
                'native_label' => $language['native'],
                'flag' => $language['flag'],
                'url' => $path.'?lang='.$code,
                'is_current' => $code === $currentLocale,
            ])
            ->values()
            ->all();
    }

    private function title(string $kind, string $locale): string
    {
        if ($locale === 'it') {
            return $kind === 'cookies' ? 'Cookie Policy' : 'Privacy Policy';
        }

        return $kind === 'cookies' ? 'Cookie Policy' : 'Privacy Policy';
    }
}
