<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Back\Locations\Location;
use App\Models\Back\Lockers\Locker;
use App\Models\Back\Devices\Device;
use App\Models\Back\Reservations\Reservation;
use App\Models\Back\Orders\Order;
use App\Models\Back\Orders\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = Carbon::today();

        // Payments schema differs per project; be defensive.
        $paymentsTable = 'payments';

        $amountGrossColumn = Schema::hasColumn($paymentsTable, 'amount_gross')
            ? 'amount_gross'
            : (Schema::hasColumn($paymentsTable, 'amount') ? 'amount' : null);

        // Revenue today
        $paidTodayQuery = Payment::query()
            ->whereDate('created_at', $today)
            ->where('status', 'paid');

        $revenueTodayGross = $amountGrossColumn
            ? (float) $paidTodayQuery->clone()->sum($amountGrossColumn)
            : 0.0;

        $revenueTodayNet = Schema::hasColumn($paymentsTable, 'amount_net')
            ? (float) $paidTodayQuery->clone()->sum('amount_net')
            : (float) round($revenueTodayGross / 1.25, 2);

        // Revenue last 7 days (including today)
        $from = Carbon::today()->subDays(6);
        $to = Carbon::today()->endOfDay();

        $raw = collect();
        if ($amountGrossColumn) {
            $raw = Payment::query()
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 'paid')
                ->selectRaw('DATE(created_at) as d, SUM(' . $amountGrossColumn . ') as gross')
                ->groupBy('d')
                ->orderBy('d')
                ->get()
                ->keyBy('d');
        }

        $revenueLast7Days = [];
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::today()->subDays(6 - $i);
            $key = $day->toDateString();

            $gross = (float) (($raw->get($key)->gross ?? 0) ?: 0);
            $net = (float) round($gross / 1.25, 2);

            $revenueLast7Days[] = [
                'date'  => $key,
                'label' => $day->format('d.m.'),
                'gross' => $gross,
                'net'   => $net,
            ];
        }

        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'users'        => User::count(),
                'locations'    => Location::count(),
                'lockers'      => Locker::count(),
                'devices'      => Device::count(),
                'reservations' => Reservation::count(),
                'orders'       => Order::count(),
                'payments'     => Payment::count(),

                'pending_orders'   => Order::where('status', 'pending')->count(),
                'pending_payments' => Payment::where('status', 'pending')->count(),

                'revenue_today_net' => $revenueTodayNet,
                'revenue_today_gross' => $revenueTodayGross,
                'revenue_last_7_days' => $revenueLast7Days,
            ],
        ]);
    }
}