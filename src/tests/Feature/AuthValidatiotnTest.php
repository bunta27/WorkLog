<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Support\TestRoutes;
use Tests\TestCase;

class AuthValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_requires_fields(): void
    {
        $res = $this->post(TestRoutes::REGISTER, []);
        $res->assertSessionHasErrors(['name','email','password']);
    }

    public function test_register_password_min_8(): void
    {
        $res = $this->post(TestRoutes::REGISTER, [
            'name' => 'テスト太郎',
            'email' => 'taro@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);
        $res->assertSessionHasErrors(['password']);
    }

    public function test_register_password_confirmation_must_match(): void
    {
        $res = $this->post(TestRoutes::REGISTER, [
            'name' => 'テスト太郎',
            'email' => 'taro@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password999',
        ]);
        $res->assertSessionHasErrors(['password']);
    }

    public function test_register_persists_user(): void
    {
        $this->post(TestRoutes::REGISTER, [
            'name' => 'テスト太郎',
            'email' => 'taro@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'taro@example.com',
        ]);
    }

    public function test_login_requires_fields(): void
    {
        $res = $this->post(TestRoutes::LOGIN, []);
        $res->assertSessionHasErrors(['email','password']);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        User::factory()->create([
            'email' => 'taro@example.com',
            'password' => Hash::make('password123'),
        ]);

        $res = $this->post(TestRoutes::LOGIN, [
            'email' => 'taro@example.com',
            'password' => 'wrongpass',
        ]);

        $res->assertSessionHasErrors();
    }

    public function test_admin_login_requires_fields(): void
    {
        $res = $this->post(TestRoutes::ADMIN_LOGIN, []);
        $res->assertSessionHasErrors(['email','password']);
    }

    public function test_admin_login_fails_with_wrong_credentials(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $res = $this->post(TestRoutes::ADMIN_LOGIN, [
            'email' => 'admin@example.com',
            'password' => 'wrongpass',
        ]);

        $res->assertSessionHasErrors();
    }
}
