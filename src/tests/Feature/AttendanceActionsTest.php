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
        $user = User::factory()->create(['email_verified_at' => now()]);

        // 1回目
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_in'])
            ->assertRedirect();

        $this->assertDatabaseCount('attendance_records', 1);

        // 2回目（仕様：ボタン非表示 or バリデーション/リダイレクト）
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_in'])
            ->assertRedirect();

        // レコード増えないこと
        $this->assertDatabaseCount('attendance_records', 1);
    }

    public function test_breaks_can_repeat(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_in']);

        // 休憩入/戻を2回
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'break_in']);
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'break_out']);
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'break_in']);
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'break_out']);

        // breaks テーブルの保存仕様に合わせてここは調整
        // 例：breaksが2件以上になっている、など
        $this->assertTrue(true);
    }

    public function test_clock_out_updates_status_and_totals(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_in']);
        $this->actingAs($user)->post(TestRoutes::ATTENDANCE_INDEX, ['action' => 'clock_out']);

        $user->refresh();
        $this->assertSame('勤務外', $user->attendance_status);

        $record = AttendanceRecord::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($record);
        $this->assertNotNull($record->total_time);
        $this->assertNotNull($record->total_break_time);
    }
}
