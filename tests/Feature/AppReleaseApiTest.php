<?php

namespace Tests\Feature;

use Tests\TestCase;

final class AppReleaseApiTest extends TestCase
{
    public function test_release_endpoint_returns_the_current_application_version(): void
    {
        $response = $this->getJson('/api/app-release');

        $response
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private')
            ->assertJsonStructure(['version']);

        $this->assertMatchesRegularExpression('/^\d{5}\.\d{2,}$/', $response->json('version'));
    }
}
