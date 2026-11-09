<?php

namespace Database\Seeders;

use App\Models\BookingSettings;
use App\Models\OpeningHour;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_PASSWORD');

        if (blank($password)) {
            throw new RuntimeException('Définissez ADMIN_PASSWORD dans le fichier .env avant de lancer le seeder.');
        }

        User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL')],
            ['name' => env('ADMIN_NAME'), 'password' => $password],
        );

        BookingSettings::current();

        if (OpeningHour::query()->doesntExist()) {
            foreach ([1, 2, 3, 4, 5] as $weekday) {
                OpeningHour::create(['weekday' => $weekday, 'starts_at' => '09:00', 'ends_at' => '12:00']);
                OpeningHour::create(['weekday' => $weekday, 'starts_at' => '14:00', 'ends_at' => '18:00']);
            }
        }
    }
}
