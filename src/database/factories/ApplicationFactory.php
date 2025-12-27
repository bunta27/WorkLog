<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create(['email_verified_at' => now()]);
        $record = AttendanceRecord::factory()->create(['user_id' => $user->id]);

        return [
            'user_id' => $user->id,
            'attendance_record_id' => $record->id,

            'approval_status' => $this->faker->randomElement(['承認待ち', '承認済み']),
            'application_date' => now(),

            'new_date' => now()->toDateString(),

            'new_clock_in' => Carbon::createFromTime(
                rand(8, 10),
                [0, 15, 30, 45][array_rand([0, 15, 30, 45])]
            )->format('H:i'),

            'new_clock_out' => Carbon::createFromTime(
                rand(17, 20),
                [0, 15, 30, 45][array_rand([0, 15, 30, 45])]
            )->format('H:i'),

            'comment' => $this->faker->randomElement([
                '体調不良のため',
                '私用のため',
                '電車遅延のため',
                '打刻漏れのため',
                '業務都合のため',
            ]),
        ];
    }
}
