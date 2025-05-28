<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OctoSetting;
use App\Models\Manager;
use App\Models\ManagerProduct;
use App\Models\Sale;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        
        $user = User::firstOrCreate(
            ['email' => $request['email']],
            [
                'name' => $request['email'],
                'password' => Hash::make($request['password']),
            ]
        );
    
       
        // Создание Sale
        if ($request['promocode'] != 'NIL'){
            $manager = Manager::where('promocode', $request['promocode'])->first();
            $managerproduct = ManagerProduct::where('product_id', $request['product_id'])
            ->where('manager_id', $manager->id)
            ->first();
            $manager_id = $manager->id;
            $selling_price = $managerproduct->price;
        }
        else {
            $manager_id = 1;
            $selling_price = $request['own_price'];
        }

        $existingSale = Sale::where('product_id', $request['product_id'])
            ->where('buyer_id', $user->id)
            ->first();

        if ($existingSale && $existingSale != 0) {
            return response()->json(['message' => 'This product has already bought'], 409); // HTTP 409 Conflict
        }

        // Если записи нет — создаём новую
        $sale = Sale::create([
            'site_price' => $request['own_price'],
            'price' => $selling_price,
            'own_price' => $request['min_price'],
            'manager_id' => $manager_id,
            'product_id' => $request['product_id'],
            'buyer_id' => $user->id,
        ]);
       
       
       if ($request['product_id'] == 1) {
            Setting::create([
                'user_id' => $user->id,
                'display_length' => 'S',
                'font_size' => 'M',
                'lv1_volume' => 500000,
                'lv2_volume' => 1000000,
                'lv3_volume' => 3000000,
                'scan_distance' => 3.00,
                'additional_spot' => 'BTC, ETH',
                'additional_futures' => 'BTC, ETH',
                'blacklisted_spot' => 'TST, MOVE',
                'blacklisted_futures' => 'TST, MOVE',
                'market' => '2',
            ]);
        } elseif ($request['product_id'] == 2) {
            OctoSetting::create([
                'user_id' => $user->id,
                'theme' => 2,
                'signal' => 1,
                'gmt_id' => 2,
            ]);
        } 
        // Создание токена
        $tokenName = 'app_' . $request['product_id'];
        $token = $user->createToken($tokenName)->plainTextToken;
          
        // Возвращение токена
        return response()->json(['token' => $token, 'sale' => $sale->id, 'price' => $selling_price], 200);
    }


    public function login(Request $request)
{
    $data = $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
        'product_id' => 'required|integer',
    ]);

    $user = User::where('email', $data['email'])->first();

    if (!$user || !Hash::check($data['password'], $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    // Преобразуем product_id в строку токена, например: "app_1"
    $tokenName = 'app_' . $data['product_id'];

    // Удалим старый токен именно для этого приложения (если был)
    $user->tokens()->where('name', $tokenName)->delete();

    // Создаём новый токен для этого приложения
    $token = $user->createToken($tokenName)->plainTextToken;

    return response()->json(['token' => $token]);
}


    public function logout(Request $request) 
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out succesfully.']);
    }


}
