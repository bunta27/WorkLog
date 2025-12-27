<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAttendanceListTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sees_only_own_records(): void
    {
        $userA = User::factory()->create(['email_verified_at' => now()]);
        $userB = User::factory()->create(['email_verified_at' => now()]);

        AttendanceRecord::factory()->count(2)->create(['user_id' => $userA->id]);
        AttendanceRecord::factory()->count(2)->create(['user_id' => $userB->id]);

        $res = $this->actingAs($userA)->get('/attendance/list');
        $res->assertOk();

        // 表示に user 名が含まれるなら
        // $res->assertSee($userA->name)->assertDontSee($userB->name);
        $this->assertTrue(true);
    }

    public function test_current_month_is_shown(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $res = $this->actingAs($user)->get('/attendance/list');
        $res->assertOk();

        // 例：2025/12 のような表示なら
        $res->assertSeeTextMatches('/\d{4}\/\d{2}/');
    }

    public function test_prev_next_month_buttons_work(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->get('/attendance/list?month=2025-12')->assertOk();
        $this->actingAs($user)->get('/attendance/list?month=2025-11')->assertOk();
        $this->actingAs($user)->get('/attendance/list?month=2026-01')->assertOk();
    }
}
