<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'ユーザー1',
                'email' => 'user1@example.com',
                'password' => bcrypt('password'),
                'admin_status' => false,
                'attendance_status' => '勤務外',
            ],
            [
                'name' => 'ユーザー2',
                'email' => 'user2@example.com',
                'password' => bcrypt('password'),
                'admin_status' => false,
                'attendance_status' => '勤務外',
            ],
            [
                'name' => 'ユーザー3',
                'email' => 'user3@example.com',
                'password' => bcrypt('password'),
                'admin_status' => false,
                'attendance_status' => '勤務外',
            ],
            [
                'name' => '管理者',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'admin_status' => true,
                'email_verified_at' => now(),
                'attendance_status' => '勤務外',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
