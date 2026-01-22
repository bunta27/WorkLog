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
        $user = User::inRandomOrder()->first()
            ?? User::factory()->create(['email_verified_at' => now()]);

        $record = AttendanceRecord::where('user_id', $user->id)->inRandomOrder()->first();

        if (! $record) {
            $record = AttendanceRecord::factory()->create(['user_id' => $user->id]);
        }


        $newDate = Carbon::parse($record->date)->toDateString();

        $minuteOptions = [0, 15, 30, 45];

        $newClockIn = Carbon::createFromTime(
            rand(8, 10),
            $minuteOptions[array_rand($minuteOptions)]
        );

        $newClockOut = (clone $newClockIn)->addHours(rand(6, 10))->min(Carbon::createFromTime(22, 0));

        return [
            'user_id' => $user->id,
            'attendance_record_id' => $record->id,

            'approval_status' => $this->faker->randomElement(['承認待ち', '承認済み']),
            'application_date' => now(),

            'new_date' => $newDate,
            'new_clock_in' => $newClockIn->format('H:i'),
            'new_clock_out' => $newClockOut->format('H:i'),

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
