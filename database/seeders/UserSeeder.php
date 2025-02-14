<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'test1',
                'password' => Hash::make('123'),
                'role' => 'pengaduan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'counter' => '1',
            ],
            [
                'name' => 'test2',
                'password' => Hash::make('123'),
                'role' => 'pengaduan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'counter' => '2',
            ],
            [
                'name' => 'test3',
                'password' => Hash::make('123'),
                'role' => 'pengaduan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'counter' => '3',
            ],
            [
                'name' => 'test4',
                'password' => Hash::make('123'),
                'role' => 'sambunganbaru',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'counter' => '4',
            ],
            [
                'name' => 'test5',
                'password' => Hash::make('123'),
                'role' => 'sambunganbaru',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'counter' => '5',
            ],
            [
                'name' => 'test6',
                'password' => Hash::make('123'),
                'role' => 'sambunganbaru',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'counter' => '6',
            ],
            [
                'name' => 'test7',
                'password' => Hash::make('123'),
                'role' => 'teller',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'counter' => '7',
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('123'),
                'role' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'counter' => '0',
            ],
        ]);
        DB::table('role')->insert([
            [
                'name' => 'admin',
                'kode' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'pengaduan',
                'kode' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'sambunganbaru',
                'kode' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'teller',
                'kode' => '3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
