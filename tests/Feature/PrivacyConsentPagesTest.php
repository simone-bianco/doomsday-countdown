<?php

declare(strict_types=1);

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

final class PrivacyConsentPagesTest extends TestCase
{
    public function test_privacy_policy_page_renders(): void
    {
        $this->get('/privacy?lang=en')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/LegalPolicy')
                ->where('page.kind', 'privacy')
                ->where('page.current_locale', 'en'));
    }

    public function test_cookie_policy_page_renders(): void
    {
        $this->get('/cookie-policy?lang=it')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/LegalPolicy')
                ->where('page.kind', 'cookies')
                ->where('page.current_locale', 'it'));
    }
}
