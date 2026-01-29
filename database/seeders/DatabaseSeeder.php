<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Obavezni seeders (uvijek)
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            SettingsSeeder::class,
        ]);

        // Opcionalni dummy podaci
        // - Interaktivno: pita u konzoli (php artisan db:seed)
        // - Neinteraktivno: postavi SEED_DUMMY=true u .env
        $seedDummy = filter_var(env('SEED_DUMMY', false), FILTER_VALIDATE_BOOL);

        if (! $seedDummy && $this->command) {
            $seedDummy = $this->command->confirm('Želiš li seedati dummy podatke (demo / test)?', false);
        }

        if ($seedDummy) {
            $this->call([
                QuickSeedSeeder::class,
            ]);
        }
    }
}
