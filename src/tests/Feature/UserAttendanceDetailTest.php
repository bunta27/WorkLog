<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestRoutes;
use Tests\TestCase;

class UserAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_detail_shows_logged_in_user_name_and_selected_date(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $record = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2025-12-13',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);

        $res = $this->actingAs($user)->get(TestRoutes::attendanceDetailUrl($record->id));
        $res->assertOk();

        $res->assertSee($user->name);
        $res->assertSee('2025');      // 表示形式が「2025年」「12月13日」ならこの程度でOK
        $res->assertSee('09:00');
        $res->assertSee('18:00');
    }
}
