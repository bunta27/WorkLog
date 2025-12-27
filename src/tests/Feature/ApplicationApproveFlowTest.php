<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\TestRoutes;
use Tests\TestCase;

class ApplicationApproveFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_application_appears_in_list_pending(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $record = AttendanceRecord::factory()->create(['user_id' => $user->id]);

        // 申請を直接作って確認（フォーム経由のテストは別でやってるため）
        Application::factory()->create([
            'user_id' => $user->id,
            'attendance_record_id' => $record->id,
            'approval_status' => '承認待ち',
        ]);

        $res = $this->actingAs($user)->get(TestRoutes::STAMP_CORRECTION_LIST);
        $res->assertOk();
        $res->assertSee('承認待ち');
    }

    public function test_admin_can_approve_and_application_moves_to_approved(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['email_verified_at' => now()]);
        $record = AttendanceRecord::factory()->create(['user_id' => $user->id]);

        $app = Application::factory()->create([
            'user_id' => $user->id,
            'attendance_record_id' => $record->id,
            'approval_status' => '承認待ち',
        ]);

        $this->actingAs($admin)->post(TestRoutes::approveUrl($app->id))
            ->assertRedirect();

        $this->assertSame('承認済み', $app->fresh()->approval_status);

        // 管理者の申請一覧（同じURL運用なら）
        $this->actingAs($admin)->get(TestRoutes::STAMP_CORRECTION_LIST)
            ->assertOk()
            ->assertSee('承認済み');
    }
}
