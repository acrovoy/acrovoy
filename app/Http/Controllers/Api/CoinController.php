<?php

namespace App\Http\Controllers\Api;

use App\Models\Coins;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoinController extends Controller
{


    public function getAllSpotCoins()
    {
        $coins = Coins::where('check_volume', 1)
        ->get();

        return $coins;

    }



    public function getAllFuturesCoins()
    {
        $coins = Coins::where('check_volume_f', 1)
        ->get();

        return $coins;

    }
}
