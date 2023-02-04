<?php

namespace Pterodactyl\Http\Controllers\Billing\Gateways;

use Illuminate\Http\Request;
use Pterodactyl\Models\Billing\Gateways\FreekassaListener;

class FreekassaGateway
{

    public function __construct()
    {
    }

    public function checkout(Request $request)
    {
        $shop = new FreekassaListener;
        return $shop->proccessPayment($request);
    }

    public function callback()
    {
        $shop = new FreekassaListener;
        $shop->handleRequest();
    }

}