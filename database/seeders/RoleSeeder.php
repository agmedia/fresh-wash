<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $abilities = [
            ['access-admin', 'Access admin panel'],
            
            ['settings.roles', 'Manage roles & permissions'],
            
            ['users.view', 'View users'],
            ['users.manage', 'Manage users'],
            ['users.delete', 'Delete users'],
            
            ['locations.view', 'View locations'],
            ['locations.manage', 'Manage locations'],
            ['locations.delete', 'Delete locations'],
            
            ['lockers.view', 'View lockers'],
            ['lockers.manage', 'Manage lockers'],
            ['lockers.delete', 'Delete lockers'],
            
            ['reservations.view', 'View reservations'],
            ['reservations.manage', 'Manage reservations'],
            ['reservations.delete', 'Delete reservations'],
        ];
        
        $abilityModels = [];
        foreach ($abilities as [$name, $title]) {
            $abilityModels[$name] = Bouncer::ability()->firstOrCreate([
                'name'  => $name,
                'title' => $title,
            ]);
        }
        
        $master = Bouncer::role()->firstOrCreate(['name' => 'master', 'title' => 'Master']);
        $admin  = Bouncer::role()->firstOrCreate(['name' => 'admin', 'title' => 'Administrator']);
        $editor = Bouncer::role()->firstOrCreate(['name' => 'editor', 'title' => 'Editor']);
        $customer = Bouncer::role()->firstOrCreate(['name' => 'customer', 'title' => 'Customer']);
        
        // Master: sve
        Bouncer::allow($master)->to(array_values($abilityModels));
        
        // Admin: sve osim settings.roles (po želji)
        Bouncer::allow($admin)->to([
            $abilityModels['access-admin'],
            
            $abilityModels['users.view'],
            $abilityModels['users.manage'],
            
            $abilityModels['locations.view'],
            $abilityModels['locations.manage'],
            
            $abilityModels['lockers.view'],
            $abilityModels['lockers.manage'],
            
            $abilityModels['reservations.view'],
            $abilityModels['reservations.manage'],
        ]);
        
        // editor/customer: ništa za admin (za sada)
    }
}