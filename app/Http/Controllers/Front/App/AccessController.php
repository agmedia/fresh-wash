<?php

namespace App\Http\Controllers\Front\App;

use App\Http\Controllers\Controller;
use App\Models\Back\Devices\DeviceCommand;
use App\Models\Back\Reservations\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccessController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // MVP: uzmi zadnju aktivnu rezervaciju koja je plaćena
        $reservation = Reservation::query()
                                  ->where('user_id', $user->id)
                                  ->whereIn('status', ['paid', 'active']) // prilagodi svojim statusima
                                  ->latest('id')
                                  ->with(['locker.location'])
                                  ->first();

        return Inertia::render('front/app/Access', [
            'reservation' => $reservation,
        ]);
    }

    public function open(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'reservation_id' => ['required', 'integer', 'exists:reservations,id'],
        ]);

        $reservation = Reservation::with(['locker.location'])->findOrFail($data['reservation_id']);
        abort_unless($reservation->user_id === $user->id, 403);

        // MVP check: plaćeno + ima locker + locker ima location/device
        abort_unless(in_array($reservation->status, ['paid', 'active'], true), 422, 'Rezervacija nije plaćena/aktivna.');
        abort_unless($reservation->locker_id, 422, 'Nema dodijeljenu mašinu.');

        $locker = $reservation->locker;
        $location = $locker->location()->with('device')->first();

        // pretpostavka: location ima device_id ili device veze
        $device = $location?->device;
        abort_unless($device, 422, 'Lokacija nema uređaj (device).');

        $command = DeviceCommand::create([
            'device_id' => $device->id,
            'locker_id' => $locker->id,
            'reservation_id' => $reservation->id,
            'requested_by_user_id' => $user->id,
            'type' => 'open_locker',
            'payload' => [
                'duration_ms' => 500, // koliko dugo otključati (primjer)
            ],
            'status' => 'pending',
        ]);

        return response()->json([
            'ok' => true,
            'command_id' => $command->id,
        ]);
    }

    public function commandStatus(DeviceCommand $command, Request $request)
    {
        $user = $request->user();
        abort_unless($command->requested_by_user_id === $user->id, 403);

        return response()->json([
            'id' => $command->id,
            'status' => $command->status,
            'ack_code' => $command->ack_code,
            'ack_message' => $command->ack_message,
            'acked_at' => optional($command->acked_at)->toIso8601String(),
        ]);
    }
}