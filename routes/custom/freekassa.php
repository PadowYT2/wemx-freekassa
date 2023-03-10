<?php

use Illuminate\Support\Facades\Route;
use Pterodactyl\Http\Controllers\Billing\Gateways\FreekassaGateway;

Route::post('/gateway/freekassa/checkout', [FreekassaGateway::class, 'checkout'])->name('freekassa.checkout');

Route::group(['middleware' => 'guest'], function () {
    Route::post('/remote/freekassa/webhook', [FreekassaGateway::class, 'callback'])->name('freekassa.callback')->withoutMiddleware(['web', 'auth', 'csrf']);
});