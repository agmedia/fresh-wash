<?php

use App\Http\Controllers\Api\DevicePollingController;
use Illuminate\Support\Facades\Route;

Route::prefix('device')->group(function () {
    Route::post('heartbeat', [DevicePollingController::class, 'heartbeat']);
    Route::get('commands', [DevicePollingController::class, 'commands']);
    Route::post('commands/{command}/ack', [DevicePollingController::class, 'ack']);
});