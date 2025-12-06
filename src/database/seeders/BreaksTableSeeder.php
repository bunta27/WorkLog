<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BreaksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        AttendanceRecord::all()->each(function (AttendanceRecord $record) use ($faker) {
            $clockIn = Carbon::parse($record->clock_in);
            $clockOut = Carbon::parse($record->clock_out ?? $clockIn->copy()->addHours(8));

            if ($clockIn->get($clockOut)) {
                [$clockIn, $clockOut] = [$clockOut, $clockIn];
            }

            $breakCount = rand(0, 5);

            for ($i = 0; $i < $breakCount; $i++) {
                $StartStr = $clockIn->format('Y-m-d H:i:s');
                $EndStr = $clockOut->format('Y-m-d H:i:s');
                $in = $faker->dateTimeBetween($StartStr, $EndStr);
                $inStr = $in->format('Y-m-d H:i:s');
                $out = $faker->dateTimeBetween($inStr, $EndStr);

                AttendanceBreak::create([
                    'attendance_record_id' => $record->id,
                    'break_in' => Carbon::instance($in)->format('Y-m-d H:i:s'),
                    'break_out' => Carbon::instance($out)->format('Y-m-d H:i:s'),
                ]);
            }
        });
    }
}
