<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ad;

class AdController extends Controller
{
    // Метод для получения активного рекламного баннера
    public function getActiveAd(Request $request)
{
    $productId = $request->input('product_id');
    $currentDate = now()->toDateString();

    $query = Ad::where('date_from', '<=', $currentDate)
               ->where('date_to', '>=', $currentDate);

    if ($productId) {
        $query->where('product_id', $productId);
    }

    $ad = $query->first();

    if ($ad) {
        return response()->json([
            'image_path' => $ad->image_path,
            'link' => $ad->link,
            'advertiser_name' => $ad->advertiser_name,
            'advertiser_contact' => $ad->advertiser_contact,
        ]);
    }

    return response()->json(['message' => 'No active ad found'], 404);
}


    public function uploadBanner(Request $request)
    {
        // Проверяем, что файл был загружен
        if ($request->hasFile('banner_image') && $request->file('banner_image')->isValid()) {
            // Загружаем файл в папку 'public/banners' в хранилище
            $imagePath = $request->file('banner_image')->store('public/banners');

            // Сохраняем путь изображения в базе данных
            $ad = new Ad();
            $ad->image_path = Storage::url($imagePath);  // Получаем URL для доступа к файлу
            $ad->link = $request->input('link');
            $ad->date_from = $request->input('date_from');
            $ad->date_to = $request->input('date_to');
            $ad->advertiser_name = $request->input('advertiser_name');
            $ad->advertiser_contact = $request->input('advertiser_contact');
            $ad->save();

            return response()->json(['message' => 'Banner uploaded successfully']);
        }

        return response()->json(['message' => 'Invalid file or no file uploaded'], 400);
    }


}
