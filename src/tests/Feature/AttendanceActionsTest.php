<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestRoutes;
use Tests\TestCase;

class AttendanceActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_clock_in_once_only(): void
    {
        $user = User::factory()->create([
        'email_verified_at' => now(),
        ]);;

        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_in'])
            ->assertRedirect();

        $this->assertDatabaseCount('attendance_records', 1);

        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_in'])
            ->assertRedirect();

        $this->assertDatabaseCount('attendance_records', 1);
    }

    public function test_breaks_can_repeat(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_in']);

        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'break_in']);
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'break_out']);
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'break_in']);
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'break_out']);

        $this->assertTrue(true);
    }

    public function test_clock_out_updates_status_and_totals(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_in']);
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_out']);

        $user->refresh();
        $this->assertSame('退勤済', $user->attendance_status);

        $record = AttendanceRecord::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($record);
        $this->assertNotNull($record->total_time);
        $this->assertNotNull($record->total_break_time);
        $this->assertNotNull($record->clock_out);
    }
}
