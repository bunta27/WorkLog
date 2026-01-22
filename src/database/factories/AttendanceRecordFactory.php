<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    public function definition()
    {
        $userId = User::inRandomOrder()->value('id')
            ?? User::factory()->create()->id;

        static $datePoolByUser = [];

        if (!isset($datePoolByUser[$userId]) || count($datePoolByUser[$userId]) === 0) {
            $start = Carbon::now()->subDays(30)->startOfDay();
            $end   = Carbon::now()->addDays(30)->startOfDay();

            $dates = [];
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $dates[] = $d->copy();
            }

            shuffle($dates);
            $datePoolByUser[$userId] = $dates;
        }

        $workDate = array_pop($datePoolByUser[$userId]);

        $clockIn = $this->randomTimeOnDate($workDate, 7, 11);

        $minOut = $clockIn->copy()->addHours(6);
        $maxOut = $clockIn->copy()->addHours(10);
        $endCap = $workDate->copy()->setTime(22, 0);

        $clockOutUpper = $maxOut->greaterThan($endCap) ? $endCap : $maxOut;
        if ($clockOutUpper->lessThanOrEqualTo($minOut)) {
            $clockOutUpper = $minOut->copy()->addMinutes(15);
        }

        $clockOut = $this->randomTimeBetween($minOut, $clockOutUpper);

        $totalBreakMinutes = 0;
        $workedMinutes = max(0, $clockIn->diffInMinutes($clockOut) - $totalBreakMinutes);

        return [
            'user_id' => $userId,

            'date' => $workDate->toDateString(),

            'clock_in' => $clockIn->format('H:i:s'),
            'clock_out' => $clockOut->format('H:i:s'),
            'total_break_time' => '00:00',
            'total_time' => sprintf('%02d:%02d', intdiv($workedMinutes, 60), $workedMinutes % 60),
            'comment' => $this->faker->optional()->randomElement([
                '電車遅延のため',
                '体調不良のため',
                '私用のため',
                '業務都合のため',
                '打刻漏れのため',
                'システム不具合のため',
                '交通渋滞のため',
                '急用のため',
            ]),
        ];
    }

    private function randomTimeOnDate(Carbon $date, int $startHour, int $endHour): Carbon
    {
        $minuteOptions = [0, 15, 30, 45];

        $hour = $this->faker->numberBetween($startHour, $endHour);
        $minute = $minuteOptions[array_rand($minuteOptions)];

        return $date->copy()->setTime($hour, $minute, 0);
    }

    private function randomTimeBetween(Carbon $start, Carbon $end): Carbon
    {
        $startTs = $start->timestamp;
        $endTs = $end->timestamp;

        if ($endTs <= $startTs) {
            return $start->copy();
        }

        $startMin = intdiv($startTs, 60);
        $endMin = intdiv($endTs, 60);

        $startMin = (int) (ceil($startMin / 15) * 15);
        $endMin = (int) (floor($endMin / 15) * 15);

        if ($endMin < $startMin) {
            return $start->copy();
        }

        $pickedMin = $this->faker->numberBetween($startMin, $endMin);
        $pickedMin = (int) (floor($pickedMin / 15) * 15);

        return Carbon::createFromTimestamp($pickedMin * 60);
    }
}
