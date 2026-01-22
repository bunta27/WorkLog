<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceRecordsTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $start = Carbon::today()->subDays(40);
        $allDates = collect(range(0, 60))
            ->map(fn ($i) => $start->copy()->addDays($i)->toDateString());

        foreach ($users as $user) {
            $dates = $allDates->shuffle()->take(15);

            foreach ($dates as $d) {
                AttendanceRecord::factory()
                    ->for($user)
                    ->state(['date' => $d])
                    ->create();
            }
        }
    }
}
