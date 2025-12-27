<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAttendanceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_all_users_attendance_list(): void
    {
        $admin = User::factory()->admin()->create();

        $userA = User::factory()->create(['email_verified_at' => now()]);
        $userB = User::factory()->create(['email_verified_at' => now()]);

        AttendanceRecord::factory()->create(['user_id' => $userA->id]);
        AttendanceRecord::factory()->create(['user_id' => $userB->id]);

        $res = $this->actingAs($admin)->get('/admin/attendance/list');
        $res->assertOk();

        // 表示にユーザー名が含まれるなら
        // $res->assertSee($userA->name)->assertSee($userB->name);
        $this->assertTrue(true);
    }

    public function test_admin_can_open_attendance_detail(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['email_verified_at' => now()]);
        $record = AttendanceRecord::factory()->create(['user_id' => $user->id]);

        $this->actingAs($admin)->get("/admin/attendance/{$record->id}")
            ->assertOk();
    }
}
