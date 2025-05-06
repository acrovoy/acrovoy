<?php

namespace App\Http\Controllers;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    /**
     * Показать страницу Dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Получаем связанные с пользователем продукты
        $userProducts = DB::table('user_product')
            ->join('products', 'user_product.product_id', '=', 'products.id')
            ->where('user_product.user_id', $user->id)
            ->select('user_product.*', 'products.name as product_name') // Добавляем название продукта
            ->get();



        // Передаем данные в представление (view)
        return view('dashboard', compact('user', 'userProducts'));
    }
}
