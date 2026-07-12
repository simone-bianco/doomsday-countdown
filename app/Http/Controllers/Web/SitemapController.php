<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Doomsday\Seo\PublicSitemapService;
use Illuminate\Http\Response;

final class SitemapController extends Controller
{
    public function __invoke(PublicSitemapService $sitemap): Response
    {
        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
