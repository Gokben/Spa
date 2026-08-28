<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_employees(): void
    {
        $this->getJson('/api/employees')->assertUnauthorized();
    }

    public function test_login_returns_current_csrf_token_for_subsequent_writes(): void
    {
        $response = $this->postJson('/login', []);

        $response->assertOk()
            ->assertJsonStructure(['accessToken', 'csrfToken'])
            ->assertJsonPath('csrfToken', session()->token());
    }

    public function test_authenticated_user_can_create_list_and_update_employee(): void
    {
        $this->actingAs(User::factory()->create());

        $payload = [
            'first_name' => 'DENİZ',
            'last_name' => 'YILMAZ',
            'registry_no' => 'S-100',
            'personnel_no' => 'P-100',
            'hire_date' => '2026-08-01',
            'termination_date' => null,
            'birth_date' => '1990-04-29',
            'blood_group' => 'A+',
            'gender' => 'Kadın',
            'phone' => '0212 555 11 22',
            'mobile_phone' => '0532 555 11 22',
            'email' => 'deniz@example.com',
            'city' => 'İstanbul',
            'district' => 'Şişli',
            'address' => 'Merkez Mahallesi',
            'photo_url' => null,
            'status' => 'aktif',
        ];

        $employeeId = $this->postJson('/api/employees', $payload)
            ->assertCreated()
            ->assertJsonPath('data.first_name', 'DENİZ')
            ->json('data.id');

        $this->getJson('/api/employees')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson("/api/employees/{$employeeId}")->assertOk()->assertJsonPath('data.personnel_no', 'P-100');
        $this->getJson("/api/employees/{$employeeId}")->assertOk()->assertJsonPath('data.mobile_phone', '0532 555 11 22');

        $this->putJson("/api/employees/{$employeeId}", [
            ...$payload,
            'termination_date' => '2026-08-28',
            'status' => 'ayrıldı',
        ])->assertOk()->assertJsonPath('data.status', 'ayrıldı');
    }

    public function test_authenticated_user_can_upload_employee_photo(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
        $employee = \App\Models\Employee::create(['first_name' => 'DENİZ', 'last_name' => 'YILMAZ', 'status' => 'aktif']);
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');

        $response = $this->postJson("/api/employees/{$employee->id}/photo", [
            'photo' => UploadedFile::fake()->createWithContent('personel.png', $png),
        ])->assertOk();

        $path = str_replace('/storage/', '', $response->json('data.photo_url'));
        Storage::disk('public')->assertExists($path);
    }

    public function test_employee_requires_name_and_last_name(): void
    {
        $this->actingAs(User::factory()->create())
            ->postJson('/api/employees', ['status' => 'aktif'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['first_name', 'last_name']);
    }
}
