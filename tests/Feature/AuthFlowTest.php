<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_credentials_are_still_required_outside_local_environment(): void
    {
        config(['spa.login_required' => true]);
        $this->postJson('/login', [])->assertUnprocessable();
    }

    public function test_login_can_continue_without_credentials_when_requirement_is_disabled(): void
    {
        config(['spa.login_required' => false]);
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->postJson('/login', [])->assertOk();
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'spa@localhost.invalid']);
    }
}
