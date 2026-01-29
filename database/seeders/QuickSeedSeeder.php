<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class QuickSeedSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('hr_HR');

        DB::transaction(function () use ($faker) {

            // -------------------------
            // Helpers
            // -------------------------
            $filterByColumns = function (string $table, array $data): array {
                if (!Schema::hasTable($table)) {
                    return [];
                }
                $cols = Schema::getColumnListing($table);
                return array_intersect_key($data, array_flip($cols));
            };

            $insert = function (string $table, array $data) use ($filterByColumns) {
                if (!Schema::hasTable($table)) return null;
                $payload = $filterByColumns($table, $data);
                if (empty($payload)) return null;
                return DB::table($table)->insertGetId($payload);
            };

            // upsert helper: unique by $uniqueWhere, return id
            $upsertGetId = function (string $table, array $uniqueWhere, array $data) use ($filterByColumns) {
                if (!Schema::hasTable($table)) return null;

                $payload = $filterByColumns($table, $data);
                if (empty($payload)) return null;

                // updateOrInsert radi, ali ne vraća id -> dohvatimo ga nakon
                DB::table($table)->updateOrInsert($uniqueWhere, $payload);

                return DB::table($table)->where($uniqueWhere)->value('id');
            };

            $now = now();

            // -------------------------
            // USERS (customers)
            // -------------------------
            $customerIds = [];

            if (Schema::hasTable('users')) {
                for ($i = 0; $i < 10; $i++) {
                    $first = $faker->firstName();
                    $last  = $faker->lastName();

                    $email = $faker->unique()->safeEmail();

                    // upsert po emailu (da možeš rerun bez duplikata)
                    $userId = $upsertGetId('users',
                        ['email' => $email],
                        [
                            'name'              => trim($first . ' ' . $last),
                            'first_name'        => $first,
                            'last_name'         => $last,
                            'email'             => $email,
                            'email_verified_at' => $now,
                            'password'          => Hash::make('password'),
                            'address_line1'     => $faker->streetAddress(),
                            'address_line2'     => null,
                            'postal_code'       => (string) $faker->postcode(),
                            'city'              => $faker->city(),
                            'country_code'      => 'HR',
                            'phone'             => $faker->optional()->phoneNumber(),
                            'whatsapp_opt_in'   => $faker->boolean(30),
                            'created_at'        => $now,
                            'updated_at'        => $now,
                        ]
                    );

                    if ($userId) {
                        $customerIds[] = $userId;

                        // Optional: bouncer role assign
                        try {
                            if (class_exists(\Silber\Bouncer\BouncerFacade::class)) {
                                \Silber\Bouncer\BouncerFacade::assign('customer')->to($userId);
                            }
                        } catch (\Throwable $e) {
                            // ignore
                        }
                    }
                }
            }

            // -------------------------
            // LOCATIONS (FIX: upsert by slug)
            // -------------------------
            $locationIds = [];

            if (Schema::hasTable('locations')) {
                $cities = ['Zagreb', 'Split', 'Rijeka', 'Osijek', 'Zadar', 'Kutina'];

                for ($i = 0; $i < 4; $i++) {
                    $name = 'Lokacija ' . ($i + 1) . ' - ' . $cities[$i];
                    $slug = str($name)->slug()->toString();

                    // FIX: izbaci kolone koje vjerojatno ne postoje (address, zip)
                    $locId = $upsertGetId('locations',
                        ['slug' => $slug],
                        [
                            'name'          => $name,
                            'slug'          => $slug,
                            'address_line1' => $faker->streetAddress(),
                            'address_line2' => null,
                            'postal_code'   => (string) $faker->postcode(),
                            'city'          => $cities[$i],
                            'country_code'  => 'HR',
                            'is_active'     => true,
                            'note'          => $faker->optional()->sentence(),
                            'created_at'    => $now,
                            'updated_at'    => $now,
                        ]
                    );

                    if ($locId) $locationIds[] = $locId;
                }

                $locationIds = array_values(array_unique(array_filter($locationIds)));
            }

            // -------------------------
            // DEVICES
            // -------------------------
            $deviceIds = [];

            if (Schema::hasTable('devices')) {
                foreach ($locationIds as $locId) {

                    // ako želiš 1 device po lokaciji -> upsert po location_id
                    $code = 'DEV-' . str_pad((string) $faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);

                    // hardware_uid mora biti unique, napravi ga deterministički po lokaciji
                    $hardwareUid = 'HW-' . strtoupper(Str::random(8)) . '-' . $locId;

                    $devId = $upsertGetId('devices',
                        ['location_id' => $locId],
                        [
                            'location_id'       => $locId,
                            'code'              => $code,
                            'hardware_uid'      => $hardwareUid,

                            'name'              => 'Uređaj ' . $code,
                            'model'             => $faker->randomElement(['ESP32', 'ESP8266', 'RP2040']),
                            'firmware_version'  => $faker->randomElement(['1.0.0', '1.0.1', '1.1.0']),
                            'ip_address'        => $faker->optional()->ipv4(),
                            'mac_address'       => $faker->optional()->macAddress(),
                            'api_token'         => Str::random(60),
                            'last_seen_at'      => $faker->optional()->dateTimeBetween('-3 days', 'now'),
                            'status'            => $faker->randomElement(['online', 'offline', 'maintenance']),
                            'is_active'         => true,
                            'note'              => $faker->optional()->sentence(),
                            'created_at'        => $now,
                            'updated_at'        => $now,

                            // kompatibilnost sa starim kolumnama (ako ih ima)
                            'uid'               => $hardwareUid,
                            'firmware'          => $faker->randomElement(['1.0.0', '1.0.1', '1.1.0']),
                        ]
                    );

                    if ($devId) $deviceIds[] = $devId;
                }

                $deviceIds = array_values(array_unique(array_filter($deviceIds)));
            }

            // -------------------------
            // LOCKERS / WASHERS (lockers)
            // -------------------------
            $lockerIds = [];

            if (Schema::hasTable('lockers')) {
                foreach ($locationIds as $locId) {
                    for ($i = 1; $i <= 6; $i++) {
                        $code = 'L' . $locId . '-' . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
                        $name = 'VešMašina ' . $i;
                        $slug = str($code . '-' . $name)->slug()->toString();

                        // upsert po code (ili slug), da ne duplira
                        $lockerId = $upsertGetId('lockers',
                            ['code' => $code],
                            [
                                'location_id'  => $locId,
                                'code'         => $code,
                                'name'         => $name,
                                'slug'         => $slug,
                                'size'         => $faker->randomElement(['S', 'M', 'L', 'XL']),
                                'hardware_uid' => strtoupper($faker->bothify('HW-########')) . '-' . $locId . '-' . $i,
                                'is_active'    => true,
                                'is_available' => $faker->boolean(80),
                                'note'         => $faker->optional()->sentence(),
                                'created_at'   => $now,
                                'updated_at'   => $now,
                            ]
                        );

                        if ($lockerId) $lockerIds[] = $lockerId;
                    }
                }

                $lockerIds = array_values(array_unique(array_filter($lockerIds)));
            }

            // -------------------------
            // SERVICES
            // -------------------------
            $serviceIds = [];

            if (Schema::hasTable('services')) {
                $services = [
                    ['Pranje', 'wash'],
                    ['Sušenje', 'dry'],
                    ['Peglanje', 'iron'],
                    ['Kemijsko', 'dry-clean'],
                ];

                foreach ($services as [$title, $code]) {
                    $srvId = $upsertGetId('services',
                        ['code' => $code],
                        [
                            'name'       => $title,
                            'title'      => $title,
                            'code'       => $code,
                            'slug'       => $code,
                            'is_active'  => true,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                    if ($srvId) $serviceIds[] = $srvId;
                }

                $serviceIds = array_values(array_unique(array_filter($serviceIds)));
            }

            // -------------------------
            // TARIFFS (unique service_id + location_id)
            // -------------------------
            if (Schema::hasTable('tariffs')) {
                foreach ($locationIds as $locId) {
                    foreach ($serviceIds as $srvId) {

                        $exists = DB::table('tariffs')
                                    ->where('location_id', $locId)
                                    ->where('service_id', $srvId)
                                    ->exists();

                        if ($exists) continue;

                        $priceNet   = (float) $faker->randomElement([3.00, 3.50, 4.00, 4.50, 5.00, 6.00]);
                        $vatRate    = 25.00;
                        $priceGross = round($priceNet * (1 + $vatRate / 100), 2);

                        $insert('tariffs', [
                            'location_id' => $locId,
                            'service_id'  => $srvId,
                            'price_net'   => $priceNet,
                            'vat_rate'    => $vatRate,
                            'price_gross' => $priceGross,
                            'currency'    => 'EUR',
                            'is_active'   => true,
                            'valid_from'  => null,
                            'valid_to'    => null,
                            'created_at'  => $now,
                            'updated_at'  => $now,
                        ]);
                    }
                }
            }

            // -------------------------
            // PAGES
            // -------------------------
            $pageCategoryIds = [];
            $pageTagIds = [];

            if (Schema::hasTable('page_categories')) {
                foreach (['Novosti', 'Upute', 'Ponude'] as $cName) {
                    $slug = str($cName)->slug()->toString();

                    $cid = $upsertGetId('page_categories',
                        ['slug' => $slug],
                        [
                            'name'        => $cName,
                            'slug'        => $slug,
                            'description' => $faker->optional()->sentence(),
                            'is_active'   => true,
                            'created_at'  => $now,
                            'updated_at'  => $now,
                        ]
                    );

                    if ($cid) $pageCategoryIds[] = $cid;
                }
                $pageCategoryIds = array_values(array_unique(array_filter($pageCategoryIds)));
            }

            if (Schema::hasTable('page_tags')) {
                foreach (['akcija', 'promo', 'servis', 'upute'] as $tName) {
                    $slug = str($tName)->slug()->toString();

                    $tid = $upsertGetId('page_tags',
                        ['slug' => $slug],
                        [
                            'name'       => $tName,
                            'slug'       => $slug,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );

                    if ($tid) $pageTagIds[] = $tid;
                }
                $pageTagIds = array_values(array_unique(array_filter($pageTagIds)));
            }

            $pageIds = [];
            if (Schema::hasTable('pages')) {
                for ($i = 0; $i < 8; $i++) {
                    $type  = $faker->randomElement(['page', 'post']);
                    $title = ($type === 'page' ? 'Stranica: ' : 'Blog: ') . $faker->sentence(4);
                    $slug  = str($title)->slug()->toString() . '-' . $faker->unique()->numberBetween(10, 999);

                    $pid = $upsertGetId('pages',
                        ['slug' => $slug],
                        [
                            'category_id'      => !empty($pageCategoryIds) ? $faker->optional(0.8)->randomElement($pageCategoryIds) : null,
                            'title'            => $title,
                            'slug'             => $slug,
                            'body'             => $faker->paragraphs(5, true),
                            'meta_title'       => $faker->optional()->sentence(6),
                            'meta_description' => $faker->optional()->sentence(12),
                            'type'             => $type,
                            'published_at'     => $faker->optional(0.7)->dateTimeBetween('-30 days', 'now'),
                            'is_active'        => $faker->boolean(85),
                            'created_at'       => $now,
                            'updated_at'       => $now,
                        ]
                    );

                    if ($pid) $pageIds[] = $pid;
                }
                $pageIds = array_values(array_unique(array_filter($pageIds)));
            }

            // page_tag pivot (pokušaj najčešćih tablica i stupaca)
            $pivotCandidates = [
                'page_page_tag' => ['page_id', 'page_tag_id'],
                'page_tag'      => ['page_id', 'tag_id'],
                'page_tags_pivot' => ['page_id', 'tag_id'],
                'page_page_tags' => ['page_id', 'tag_id'],
            ];

            $pivotTable = null;
            $pivotCols = null;
            foreach ($pivotCandidates as $t => $cols) {
                if (Schema::hasTable($t)) {
                    $pivotTable = $t;
                    $pivotCols = $cols;
                    break;
                }
            }

            if ($pivotTable && $pivotCols && !empty($pageIds) && !empty($pageTagIds)) {
                [$cPageId, $cTagId] = $pivotCols;

                foreach ($pageIds as $pid) {
                    $tagSample = $faker->randomElements($pageTagIds, min(2, count($pageTagIds)));

                    foreach ($tagSample as $tid) {
                        $exists = DB::table($pivotTable)->where([$cPageId => $pid, $cTagId => $tid])->exists();
                        if ($exists) continue;

                        DB::table($pivotTable)->insert(
                            array_filter([
                                $cPageId    => $pid,
                                $cTagId     => $tid,
                                'created_at'=> $now,
                                'updated_at'=> $now,
                            ])
                        );
                    }
                }
            }

            // -------------------------
            // RESERVATIONS + ORDERS + PAYMENTS
            // -------------------------
            $reservationIds = [];

            if (Schema::hasTable('reservations') && !empty($customerIds) && !empty($lockerIds)) {

                // map locker -> location (da rezervaciji možemo setati location_id)
                $lockerLocationMap = [];
                if (Schema::hasTable('lockers')) {
                    $rows = DB::table('lockers')->whereIn('id', $lockerIds)->get(['id', 'location_id']);
                    foreach ($rows as $r) $lockerLocationMap[$r->id] = $r->location_id;
                }

                for ($i = 0; $i < 20; $i++) {
                    $userId   = $faker->randomElement($customerIds);
                    $lockerId = $faker->randomElement($lockerIds);
                    $locId    = $lockerLocationMap[$lockerId] ?? null;

                    $start = Carbon::now()->addDays($faker->numberBetween(-3, 10))->setTime($faker->numberBetween(8, 20), 0);
                    $end   = (clone $start)->addMinutes($faker->randomElement([30, 60, 90, 120]));

                    $reservationStatus = $faker->randomElement(['new', 'confirmed', 'completed', 'cancelled']);
                    $amountNet = (float) $faker->randomElement([3.5, 4.0, 5.0, 6.0, 7.0, 8.0]);

                    $reservationId = $insert('reservations', [
                        'user_id'     => $userId,
                        'locker_id'   => $lockerId,
                        'location_id' => $locId,  // FIX: poveži na lokaciju
                        'starts_at'   => $start,
                        'ends_at'     => $end,
                        'status'      => $reservationStatus,
                        'total'       => $amountNet, // ako ti schema ima total
                        'amount'      => $amountNet, // ako ti schema ima amount
                        'currency'    => 'EUR',
                        'note'        => $faker->optional()->sentence(),
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ]);

                    if (!$reservationId) continue;

                    $reservationIds[] = $reservationId;

                    // ORDER
                    $orderId = null;
                    if (Schema::hasTable('orders')) {
                        $ordCode = 'ORD-' . now()->format('Y') . '-' . str_pad((string) $faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT);

                        // FIX: order status values (tvoja schema: pending|paid|cancelled|refunded)
                        $orderStatus = match ($reservationStatus) {
                            'confirmed', 'completed' => 'paid',
                            'cancelled' => 'cancelled',
                            default => 'pending',
                        };

                        $vatRate = 25.00;
                        $totalNet = $amountNet;
                        $totalGross = round($totalNet * (1 + $vatRate / 100), 2);

                        $orderId = $insert('orders', [
                            'code'           => $ordCode, // FIX: required
                            'user_id'        => $userId,
                            'location_id'    => $locId,
                            'reservation_id' => $reservationId,
                            'status'         => $orderStatus,
                            'currency'       => 'EUR',

                            'total_net'      => $totalNet,
                            'vat_rate'       => $vatRate,
                            'total_gross'    => $totalGross,

                            // kompatibilnost ako schema ima drugačije
                            'total'          => $totalGross,
                            'amount'         => $totalGross,

                            'note'           => $faker->optional()->sentence(),
                            'created_at'     => $now,
                            'updated_at'     => $now,
                        ]);
                    }

                    // PAYMENT
                    if (Schema::hasTable('payments')) {
                        $paid = in_array($reservationStatus, ['confirmed', 'completed'], true) && $faker->boolean(70);

                        $insert('payments', [
                            'user_id'    => $userId,
                            'order_id'   => $orderId,
                            'amount'     => $paid ? round($amountNet * 1.25, 2) : round($amountNet * 1.25, 2), // gross, ako ti tako držiš
                            'currency'   => 'EUR',
                            'status'     => $paid ? 'paid' : 'pending',
                            'provider'   => $faker->randomElement(['cash', 'card', 'bank', 'corvus']),
                            'reference'  => strtoupper($faker->bothify('PAY-########')),
                            'paid_at'    => $paid ? $faker->dateTimeBetween('-10 days', 'now') : null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }

            // -------------------------
            // NOTIFICATIONS (log)
            // -------------------------
            $notifTable = Schema::hasTable('notification_logs')
                ? 'notification_logs'
                : (Schema::hasTable('notifications') ? 'notifications' : null);

            if ($notifTable && !empty($customerIds)) {
                for ($i = 0; $i < 25; $i++) {
                    $userId = $faker->randomElement($customerIds);

                    $insert($notifTable, [
                        'user_id'     => $userId,
                        'channel'     => $faker->randomElement(['email', 'whatsapp']),
                        'to'          => $faker->randomElement(['test@example.com', '+3859' . $faker->numerify('########')]),
                        'subject'     => $faker->sentence(4),
                        'message'     => $faker->sentence(14),
                        'status'      => $faker->randomElement(['queued', 'sent', 'failed']),
                        'sent_at'     => $faker->optional()->dateTimeBetween('-10 days', 'now'),
                        'meta'        => json_encode(['seed' => true], JSON_UNESCAPED_UNICODE),
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ]);
                }
            }

        });
    }
}