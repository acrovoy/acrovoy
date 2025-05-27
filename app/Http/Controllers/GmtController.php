<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Gmt;

class GmtController extends Controller
{
     /**
     * Получить список всех часовых поясов (GMT).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $gmts = Gmt::all();
        return response()->json($gmts);
    }
}
