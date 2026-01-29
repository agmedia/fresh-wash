<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Back\Devices\Device;
use App\Models\Back\Devices\DeviceCommand;
use Illuminate\Http\Request;

class DevicePollingController extends Controller
{
    private function deviceFromToken(Request $request): Device
    {
        $token = $request->header('X-Device-Token');
        abort_unless($token, 401, 'Missing device token');

        $device = Device::where('api_token', $token)->first();
        abort_unless($device, 401, 'Invalid device token');

        return $device;
    }

    public function heartbeat(Request $request)
    {
        $device = $this->deviceFromToken($request);

        $data = $request->validate([
            'status' => ['nullable', 'string', 'max:20'],
            'firmware_version' => ['nullable', 'string', 'max:50'],
            'ip_address' => ['nullable', 'string', 'max:45'],
            'hardware_uid' => ['nullable', 'string', 'max:100'],
        ]);

        $device->forceFill([
            'last_seen_at' => now(),
            'status' => $data['status'] ?? $device->status ?? 'online',
            'firmware_version' => $data['firmware_version'] ?? $device->firmware_version,
            'ip_address' => $data['ip_address'] ?? $device->ip_address,
            'hardware_uid' => $data['hardware_uid'] ?? $device->hardware_uid,
        ])->save();

        return response()->json(['ok' => true, 'server_time' => now()->toIso8601String()]);
    }

    public function commands(Request $request)
    {
        $device = $this->deviceFromToken($request);

        // uzmi pending komande
        $commands = DeviceCommand::where('device_id', $device->id)
                                 ->where('status', 'pending')
                                 ->orderBy('id')
                                 ->limit(10)
                                 ->get();

        // označi kao "sent"
        foreach ($commands as $c) {
            $c->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }

        return response()->json([
            'device_id' => $device->id,
            'commands' => $commands->map(fn ($c) => [
                'id' => $c->id,
                'type' => $c->type,
                'locker_id' => $c->locker_id,
                'payload' => $c->payload,
                'created_at' => $c->created_at?->toIso8601String(),
            ]),
        ]);
    }

    public function ack(Request $request, DeviceCommand $command)
    {
        $device = $this->deviceFromToken($request);
        abort_unless($command->device_id === $device->id, 403, 'Not your command');

        $data = $request->validate([
            'success' => ['required', 'boolean'],
            'code' => ['nullable', 'string', 'max:50'],
            'message' => ['nullable', 'string'],
        ]);

        $command->update([
            'status' => $data['success'] ? 'acked' : 'failed',
            'acked_at' => now(),
            'ack_code' => $data['code'] ?? null,
            'ack_message' => $data['message'] ?? null,
        ]);

        return response()->json(['ok' => true]);
    }
}