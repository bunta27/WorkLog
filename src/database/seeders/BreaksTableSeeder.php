<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use Carbon\Carbon;

class BreaksTableSeeder extends Seeder
{
    public function run()
    {
        AttendanceRecord::query()->each(function (AttendanceRecord $record) {
            if (!$record->clock_in || !$record->clock_out) {
                return;
            }

            $baseDate = Carbon::parse($record->date)->format('Y-m-d');

            $clockIn  = Carbon::parse($baseDate . ' ' . Carbon::parse($record->clock_in)->format('H:i:s'));
            $clockOut = Carbon::parse($baseDate . ' ' . Carbon::parse($record->clock_out)->format('H:i:s'));


            if ($clockIn->gte($clockOut)) {
                return;
            }

            $rangeStart = $clockIn->copy()->addMinutes(30);
            $rangeEnd   = $clockOut->copy()->subMinutes(30);

            if ($rangeStart->gte($rangeEnd)) {
                return;
            }

            $breakCount = random_int(0, 2);

            $occupied = [];

            $totalBreakMinutes = 0;

            for ($i = 0; $i < $breakCount; $i++) {
                $duration = [15, 30, 45, 60][array_rand([15, 30, 45, 60])];

                $latestStart = $rangeEnd->copy()->subMinutes($duration);
                if ($latestStart->lt($rangeStart)) {
                    break;
                }

                $made = false;

                for ($try = 0; $try < 10; $try++) {
                    $breakIn = $this->randomQuarterBetween($rangeStart, $latestStart);
                    $breakOut = $breakIn->copy()->addMinutes($duration);

                    if ($this->overlaps($occupied, $breakIn, $breakOut)) {
                        continue;
                    }

                    AttendanceBreak::create([
                        'attendance_record_id' => $record->id,
                        'break_in' => $breakIn->format('Y-m-d H:i:s'),
                        'break_out' => $breakOut->format('Y-m-d H:i:s'),
                    ]);

                    $occupied[] = [$breakIn->timestamp, $breakOut->timestamp];
                    $totalBreakMinutes += $duration;
                    $made = true;
                    break;
                }

                if (!$made) {
                    break;
                }
            }

            if (isset($record->total_break_time)) {
                $record->total_break_time = sprintf('%02d:%02d', intdiv($totalBreakMinutes, 60), $totalBreakMinutes % 60);
                $record->save();
            }
        });
    }

    private function randomQuarterBetween(Carbon $start, Carbon $end): Carbon
    {
        $startMin = intdiv($start->timestamp, 60);
        $endMin   = intdiv($end->timestamp, 60);

        $startMin = (int) (ceil($startMin / 15) * 15);
        $endMin   = (int) (floor($endMin / 15) * 15);

        if ($endMin < $startMin) {
            return $start->copy();
        }

        $picked = random_int($startMin, $endMin);
        $picked = (int) (floor($picked / 15) * 15);

        return Carbon::createFromTimestamp($picked * 60);
    }

    private function overlaps(array $occupied, Carbon $in, Carbon $out): bool
    {
        $a1 = $in->timestamp;
        $a2 = $out->timestamp;

        foreach ($occupied as [$b1, $b2]) {
            if ($a1 < $b2 && $b1 < $a2) {
                return true;
            }
        }
        return false;
    }
}
