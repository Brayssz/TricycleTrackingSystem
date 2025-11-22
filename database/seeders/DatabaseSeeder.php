<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // ----------- USERS -----------
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'test@mail.com',
            'position' => 'admin',
            'password' => bcrypt('password'), 
            'status' => 'active',
        ]);

        // ----------- DRIVERS -----------
        $drivers = [
            [
                'name' => 'Alex Cruz',
                'license_number' => 'LIC123456',
                'phone' => '09171234567',
                'address' => 'Manila',
                'username' => 'alex.cruz',
                'password' => bcrypt('password'),
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Maria Santos',
                'license_number' => 'LIC654321',
                'phone' => '09179876543',
                'address' => 'Cebu City',
                'username' => 'maria.santos',
                'password' => bcrypt('password'),
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('drivers')->insert($drivers);

        // ----------- DEVICES -----------
        $devices = [
            [
                'device_name' => 'Tracker 01',
                'sim_number' => 'SIM1234567890',
                'device_identifier' => 'DEV-001',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'device_name' => 'Tracker 02',
                'sim_number' => 'SIM0987654321',
                'device_identifier' => 'gps-002',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('devices')->insert($devices);

        // ----------- TRICYCLES -----------
        $tricycles = [
            [
                'plate_number' => 'TRI-001',
                'motorcycle_model' => 'Honda Wave',
                'color' => 'Blue',
                'driver_id' => 1,
                'device_id' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_number' => 'TRI-002',
                'motorcycle_model' => 'Yamaha NMAX',
                'color' => 'Red',
                'driver_id' => 2,
                'device_id' => 2,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('tricycles')->insert($tricycles);

        // ----------- DEVICE COORDINATES -----------
        $coordinates = [
            ['device_id' => 1, 'latitude' => 14.599512, 'longitude' => 120.984222],
            ['device_id' => 1, 'latitude' => 14.676041, 'longitude' => 121.043700],
            ['device_id' => 2, 'latitude' => 10.315699, 'longitude' => 123.885437],
            ['device_id' => 2, 'latitude' => 7.190707, 'longitude' => 125.455334],
        ];

        foreach ($coordinates as $coord) {
            DB::table('device_coordinates')->insert([
                'device_id' => $coord['device_id'],
                'latitude' => $coord['latitude'],
                'longitude' => $coord['longitude'],
                'recorded_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
