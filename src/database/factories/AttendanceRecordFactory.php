<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;

class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    public function definition()
    {
        $userId = User::inRandomOrder()->value('id');

        $date = $this->faker->dateTimeBetween('-10 days', '+10 days')->format('Y-m-d');
        $clockIn = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $this->faker->time('H:i:s'));
        $clockOut = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $this->faker->time('H:i:s'))->addHours(rand(6, 10));

        $totalBreakSeconds = 0;

        $workedSeconds = $clockOut->diffInSeconds($clockIn) - $totalBreakSeconds;

        return [
            'user_id' => $userId,
            'date' => $date,
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'total_break_time' => gmdate('H:i', $totalBreakSeconds),
            'total_time' => gmdate('H:i', $workedSeconds),
            'comment' => $this->faker->optional()->sentence(),
        ];
    }
}
