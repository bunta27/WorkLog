<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestRoutes;
use Tests\TestCase;

class AttendanceDatetimeAndStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_page_shows_datetime_in_expected_format(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $res = $this->actingAs($user)->get(TestRoutes::ATTENDANCE_INDEX);
        $res->assertOk();

        $res->assertSeeTextMatches('/\d{4}年\d{1,2}月\d{1,2}日/');

        $res->assertSeeTextMatches('/\d{2}:\d{2}/');

    }

    public function test_status_text_is_shown(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'attendance_status' => '勤務外',
        ]);

        $this->actingAs($user)->get(TestRoutes::ATTENDANCE_INDEX)
            ->assertOk()
            ->assertSee('勤務外');
    }
}
