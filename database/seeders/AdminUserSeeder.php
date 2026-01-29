<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\BouncerFacade as Bouncer;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // If Bouncer is installed, ensure the admin ability/role exist.
        if (class_exists(Bouncer::class)) {
            Bouncer::ability()->firstOrCreate([
                'name'  => 'access-admin',
                'title' => 'Access admin panel',
            ]);

            $adminRole = Bouncer::role()->firstOrCreate([
                'name'  => 'admin',
                'title' => 'Administrator',
            ]);

            $adminRole->allow('access-admin');
        }

        $users = [
            [
                'first_name'      => 'Filip',
                'last_name'       => 'Jankoski',
                'email'           => 'filip@agmedia.hr',
                'pass'            => 'majamaja001',
                'address_line1'   => 'Kovačića 23',
                'address_line2'   => null,
                'postal_code'     => '44320',
                'city'            => 'Kutina',
                'country_code'    => 'HR',
                'phone'           => null,
                'whatsapp_opt_in' => false,
                'role'            => 'master',
            ],
            [
                'first_name'      => 'Tomislav',
                'last_name'       => 'Jureša',
                'email'           => 'tomislav@agmedia.hr',
                'pass'            => 'bakanal',
                'address_line1'   => 'Malešnica bb',
                'address_line2'   => null,
                'postal_code'     => '10000',
                'city'            => 'Zagreb',
                'country_code'    => 'HR',
                'phone'           => null,
                'whatsapp_opt_in' => false,
                'role'            => 'master',
            ],
            [
                'first_name'      => 'Testko',
                'last_name'       => 'Testić',
                'email'           => 'test@test.hr',
                'pass'            => 'testtest001#',
                'address_line1'   => 'Bebe bb',
                'address_line2'   => null,
                'postal_code'     => '10000',
                'city'            => 'Zagreb',
                'country_code'    => 'HR',
                'phone'           => null,
                'whatsapp_opt_in' => false,
                'role'            => 'admin',
            ],
            [
                'first_name'      => 'Testko',
                'last_name'       => 'Prvić',
                'email'           => 'prvi@test.hr',
                'pass'            => 'prviprvi001',
                'address_line1'   => 'Bebe bb',
                'address_line2'   => null,
                'postal_code'     => '10000',
                'city'            => 'Zagreb',
                'country_code'    => 'HR',
                'phone'           => null,
                'whatsapp_opt_in' => false,
                'role'            => 'customer',
            ],
        ];

        foreach ($users as $u) {
            $name = trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? ''));

            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    // legacy column from default Laravel users table
                    'name'              => $name !== '' ? $name : ($u['email']),

                    // profile columns (added by our migration)
                    'first_name'        => $u['first_name'] ?? null,
                    'last_name'         => $u['last_name'] ?? null,
                    'address_line1'     => $u['address_line1'] ?? null,
                    'address_line2'     => $u['address_line2'] ?? null,
                    'postal_code'       => $u['postal_code'] ?? null,
                    'city'              => $u['city'] ?? null,
                    'country_code'      => $u['country_code'] ?? 'HR',
                    'phone'             => $u['phone'] ?? null,
                    'whatsapp_opt_in'   => (bool)($u['whatsapp_opt_in'] ?? false),

                    'password'          => Hash::make($u['pass']),
                    'email_verified_at' => now(),
                ]
            );

            // Assign Bouncer role/ability (if installed)
            if (class_exists(Bouncer::class)) {
                $roleName = $u['role'] ?? 'admin';

                // Ensure role exists
                $role = Bouncer::role()->firstOrCreate([
                    'name'  => $roleName,
                    'title' => ucfirst(str_replace('-', ' ', $roleName)),
                ]);

                // Grant admin access for admin-like roles
                if (in_array($roleName, ['admin', 'master', 'super-admin'], true)) {
                    $role->allow('access-admin');
                }

                $user->assign($role);
            }
        }
    }
}
