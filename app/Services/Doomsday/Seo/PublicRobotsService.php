<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Seo;

final class PublicRobotsService
{
    public function __construct(private readonly PublicUrlBuilder $urlBuilder) {}

    public function render(): string
    {
        $backoffice = trim((string) config('ai-starter.backoffice_path'), '/');
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /login',
            'Disallow: /agent/',
            'Disallow: /*-data',
        ];

        if ($backoffice !== '') {
            $lines[] = 'Disallow: /'.$backoffice.'/';
        }

        $lines[] = '';
        $lines[] = 'Sitemap: '.$this->urlBuilder->sitemapUrl();

        return implode("\n", $lines)."\n";
    }
}
