<?php

namespace Tests\Feature;

use App\Models\SmsSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_sms_settings(): void
    {
        $this->getJson('/api/sms')->assertUnauthorized();
    }

    public function test_settings_can_be_saved_without_exposing_api_password(): void
    {
        $this->actingAs(User::factory()->create());

        $this->putJson('/api/sms/settings', [
            'username' => 'spa@example.com',
            'password' => 'secret-api-password',
            'source_addr' => 'SOFITELSPA',
            'active' => true,
        ])->assertOk()
            ->assertJsonPath('data.username', 'spa@example.com')
            ->assertJsonPath('data.has_password', true)
            ->assertJsonMissing(['password' => 'secret-api-password']);

        $this->assertNotSame('secret-api-password', SmsSetting::query()->first()->getRawOriginal('password'));
    }

    public function test_authenticated_user_can_send_sms_through_verimor(): void
    {
        $this->actingAs(User::factory()->create());
        SmsSetting::create([
            'provider' => 'verimor',
            'username' => 'spa@example.com',
            'password' => 'secret-api-password',
            'source_addr' => 'SOFITELSPA',
            'active' => true,
        ]);
        Http::fake([
            'sms.verimor.com.tr/*' => Http::response([
                'campaign_id' => 98765432,
                'status' => '0',
            ]),
        ]);

        $this->postJson('/api/sms/send', [
            'recipient_name' => 'Ayşe Demir',
            'destination' => '0532 123 45 67',
            'message' => 'Rezervasyonunuz oluşturuldu.',
            'is_commercial' => false,
        ])->assertCreated()
            ->assertJsonPath('data.destination', '905321234567')
            ->assertJsonPath('data.campaign_id', '98765432')
            ->assertJsonPath('data.status', 'submitted');

        Http::assertSent(fn ($request) =>
            $request->url() === 'https://sms.verimor.com.tr/v2/send.json'
            && $request['username'] === 'spa@example.com'
            && $request['password'] === 'secret-api-password'
            && $request['source_addr'] === 'SOFITELSPA'
            && $request['messages'][0]['dest'] === '905321234567'
        );
        $this->assertDatabaseHas('sms_messages', [
            'destination' => '905321234567',
            'campaign_id' => '98765432',
            'status' => 'submitted',
        ]);
    }

    public function test_invalid_mobile_number_is_rejected_before_provider_call(): void
    {
        $this->actingAs(User::factory()->create());
        SmsSetting::create([
            'provider' => 'verimor',
            'username' => 'spa@example.com',
            'password' => 'secret-api-password',
            'active' => true,
        ]);
        Http::fake();

        $this->postJson('/api/sms/send', [
            'destination' => '12345',
            'message' => 'Test',
        ])->assertUnprocessable()->assertJsonValidationErrors('destination');

        Http::assertNothingSent();
    }
}
