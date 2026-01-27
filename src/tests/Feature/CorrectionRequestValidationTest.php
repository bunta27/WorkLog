<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestRoutes;
use Tests\TestCase;

class CorrectionRequestValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_clock_in_must_be_before_clock_out(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $record = AttendanceRecord::factory()->create(['user_id' => $user->id]);

        $res = $this->actingAs($user)->post(TestRoutes::attendanceUpdateUrl($record->id), [
            'new_date' => '12月13日',
            'new_clock_in' => '18:00',
            'new_clock_out' => '09:00',
            'new_break_in' => [],
            'new_break_out' => [],
            'comment' => '電車遅延のため',
        ]);

        $res->assertSessionHasErrors(['new_clock_in','new_clock_out']);
    }

    public function test_break_time_format_and_order_validation(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $record = AttendanceRecord::factory()->create(['user_id' => $user->id]);

        $res = $this->actingAs($user)->post(TestRoutes::attendanceUpdateUrl($record->id), [
            'new_date' => '12月13日',
            'new_clock_in' => '09:00',
            'new_clock_out' => '18:00',
            'new_break_in' => ['13:00'],
            'new_break_out' => ['12:00'],
            'comment' => '電車遅延のため',
        ]);

        $res->assertSessionHasErrors();
    }

    public function test_comment_required(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $record = AttendanceRecord::factory()->create(['user_id' => $user->id]);

        $res = $this->actingAs($user)->post(TestRoutes::attendanceUpdateUrl($record->id), [
            'new_date' => '12月13日',
            'new_clock_in' => '09:00',
            'new_clock_out' => '18:00',
            'new_break_in' => [],
            'new_break_out' => [],
            'comment' => '',
        ]);

        $res->assertSessionHasErrors(['comment']);
    }
}
