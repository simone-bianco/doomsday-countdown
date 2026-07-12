<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Seo;

use App\Models\Countdown;
use Carbon\CarbonImmutable;
use DOMDocument;
use DOMElement;
use Illuminate\Database\Eloquent\Collection;

final class PublicSitemapService
{
    private const SITEMAP_NAMESPACE = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    private const XHTML_NAMESPACE = 'http://www.w3.org/1999/xhtml';

    public function __construct(private readonly PublicUrlBuilder $urlBuilder) {}

    public function render(): string
    {
        /** @var Collection<int, Countdown> $countdowns */
        $countdowns = Countdown::query()
            ->published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'slug', 'sort_order', 'updated_at']);
        $homeLastModified = $countdowns
            ->sortByDesc(fn (Countdown $countdown): int => $countdown->updated_at?->getTimestamp() ?? 0)
            ->first()?->updated_at?->toImmutable()->utc();

        $documents = [
            ['path' => '/', 'lastmod' => $homeLastModified],
            ['path' => '/about', 'lastmod' => null],
        ];

        foreach ($countdowns as $countdown) {
            $documents[] = [
                'path' => '/countdowns/'.$countdown->slug,
                'lastmod' => $countdown->updated_at?->toImmutable()->utc(),
            ];
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = false;
        $urlset = $dom->createElementNS(self::SITEMAP_NAMESPACE, 'urlset');
        $dom->appendChild($urlset);

        foreach ($documents as $document) {
            foreach ($this->urlBuilder->supportedLocales() as $locale) {
                $this->appendUrl($dom, $urlset, $document['path'], $locale, $document['lastmod']);
            }
        }

        return (string) $dom->saveXML();
    }

    private function appendUrl(
        DOMDocument $dom,
        DOMElement $urlset,
        string $path,
        string $locale,
        ?CarbonImmutable $lastModified,
    ): void {
        $url = $dom->createElementNS(self::SITEMAP_NAMESPACE, 'url');
        $loc = $dom->createElementNS(self::SITEMAP_NAMESPACE, 'loc');
        $loc->appendChild($dom->createTextNode($this->urlBuilder->localeUrl($path, $locale)));
        $url->appendChild($loc);

        foreach ($this->urlBuilder->alternates($path) as $alternate) {
            $link = $dom->createElementNS(self::XHTML_NAMESPACE, 'xhtml:link');
            $link->setAttribute('rel', 'alternate');
            $link->setAttribute('hreflang', $alternate->hreflang);
            $link->setAttribute('href', $alternate->url);
            $url->appendChild($link);
        }

        $default = $dom->createElementNS(self::XHTML_NAMESPACE, 'xhtml:link');
        $default->setAttribute('rel', 'alternate');
        $default->setAttribute('hreflang', 'x-default');
        $default->setAttribute('href', $this->urlBuilder->neutralUrl($path));
        $url->appendChild($default);

        if ($lastModified instanceof CarbonImmutable) {
            $lastmod = $dom->createElementNS(self::SITEMAP_NAMESPACE, 'lastmod');
            $lastmod->appendChild($dom->createTextNode($lastModified->toAtomString()));
            $url->appendChild($lastmod);
        }

        $urlset->appendChild($url);
    }
}
