<?php

namespace App\Http\Controllers\Api;

use App\Models\Coins;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CoinController extends Controller
{


    public function getAllSpotCoins()
    {

        // $user = Auth::user();

        // if (!$user) {
        //     return response()->json([
        //         'error' => 'Unauthorized',
        //         'user' => null
        //     ], 401);
        // }

        $coins = Coins::where('check_volume', 1)
        ->get();

        return $coins;

    }



    public function getAllFuturesCoins()
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized',
                'user' => null
            ], 401);
        }

        $coins = Coins::where('check_volume_f', 1)
        ->get();

        return $coins;

    }
}
