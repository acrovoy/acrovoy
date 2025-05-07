<?php

namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\User;
use App\Models\Manager;
use App\Models\Products;
use App\Models\Invoices;
use App\Models\ManagerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();
        $grandTotalAmount = 0;
        $products = null;
        $promocode = null;
        $manager = null;

        $user->is_manager = Manager::where("user_id", $user->id)->exists() ? 1 : 2;

        if ($user->is_manager == 1) {
            $manager = Manager::where("user_id", $user->id)->firstOrFail();
            $user->manager_id = $manager->id;

            $promocode = $manager->promocode;

            $products = ManagerProduct::where("manager_id", $manager->id)->get();
            

            foreach ($products as $product) {
            
            $allsales = Sale::with(['manager.user', 'product'])
                ->where('manager_id', $manager->id)
                ->where('product_id', $product->product_id)
                ->get();


            $productDescription = Products::where('id', $product->product_id)    
                ->first();

                    $totalCount = $allsales->count();
                    $product->totalCount = $totalCount;
                    $paidCount = 0;
                    $totalAmount = 0;

                    foreach ($allsales as $sale) {
                            $invoice = Invoices::where('id', $sale->invoice_id)->first();
                            $sale->invoice = $invoice;

                                if ($invoice && $invoice->is_paid == 1) {
                                    $paidCount++;
                                    $totalAmount += ($sale->price - $sale->own_price);
                                    }
                
                            }

                     
                $product->totalAmount = $totalAmount;
                $product->paidCount = $paidCount;
                $product->productDescription = $productDescription;

                $grandTotalAmount += $totalAmount;


            }



        }

       
        // Получаем все продажи пользователя
        $sales = Sale::with(['product', 'manager.user'])
                    ->where('buyer_id', $user->id)
                    ->get();

        // Получаем все инвойсы для этих продуктов
        $productIds = $sales->pluck('product_id')->unique();
        $invoices = Invoices::where('user_id', $user->id)
                            ->whereIn('product_id', $productIds)
                            ->get()
                            ->keyBy('product_id');

        // Добавим инвойс к каждой продаже вручную
        foreach ($sales as $sale) {
            $sale->invoiceDetails = $invoices->get($sale->product_id);

            $manager = Manager::where('id', $sale->manager_id)
            ->first();
            $user = User::where('id', $manager->user_id)->first();
            $manager->user = $user;
            $sale->manager = $manager;




        }
        return view('dashboard', compact('user', 'sales', 'grandTotalAmount', 'products', 'promocode', 'manager'));
    }

    

    public function salesPage($product_id)
    {
        $user = Auth::user();

        $manager = Manager::where("user_id", $user->id)->firstOrFail();
        $user->manager_id = $manager->id;

        $manager_product = ManagerProduct::where("manager_id", $manager->id)
        ->where("product_id", $product_id)
        ->first();
        $current_price = $manager_product->price;

        $product = Products::findOrFail($product_id);

        $sales = Sale::with(['manager.user', 'product'])
            ->where('manager_id', $manager->id)
            ->where('product_id', $product_id)
            ->paginate(10);

        $allsales = Sale::with(['manager.user', 'product'])
            ->where('manager_id', $manager->id)
            ->where('product_id', $product_id)
            ->get();

            foreach ($sales as $sale) {
                $invoice = Invoices::where('id', $sale->invoice_id)->first();
                $sale->invoice = $invoice;
    
                $buyers_email = User::where('id', $sale->buyer_id)->first();
                $sale->buyers_email = $buyers_email->email;
    
               
        
       
    
            }        


            $totalCount = $allsales->count();
            $paidCount = 0;
            $totalAmount = 0;
        foreach ($allsales as $sale) {
            $invoice = Invoices::where('id', $sale->invoice_id)->first();
            $sale->invoice = $invoice;

           

            if ($invoice && $invoice->is_paid == 1) {
                $paidCount++;

                $totalAmount += ($sale->price - $sale->own_price);
    
                }

        }

        return view('salespage', compact('user', 'sales', 'product', 'totalCount', 'paidCount', 'totalAmount', 'current_price'));
    }


    public function updatePrice(Request $request)
{
    $request->validate([
        'manager_id' => 'required|integer',
        'product_id' => 'required|integer',
        'price' => 'required|numeric',
    ]);

    $managerProduct = ManagerProduct::where('manager_id', $request->manager_id)
        ->where('product_id', $request->product_id)
        ->first();


        $product = Products::where('id', $request->product_id)
        ->first();


    if ($managerProduct) {
        $managerProduct->price = $request->price;
        if($request->price < $product->min_price){
            $managerProduct->price = $product->min_price;

        }

        $managerProduct->save();
        return back()->with('success', 'Price updated successfully.');
    }

    return back()->with('error', 'Manager product not found.');

}

public function addProducts()
    {
        $user = Auth::user();

  
        $manager = Manager::where("user_id", $user->id)->firstOrFail();
        $user->manager_id = $manager->id;
         

        $allproducts = Products::all();

    // Получаем product_id всех продуктов, привязанных к текущему менеджеру
    $managerProductIds = ManagerProduct::where("manager_id", $manager->id)->pluck('product_id')->toArray();

    // Отмечаем, какие продукты уже привязаны
    foreach ($allproducts as $product) {
        $product->checked = in_array($product->id, $managerProductIds) ? 'checked' : '';
    }

    return view('add_product', compact('user', 'allproducts'));
    }





    public function updateProductList(Request $request)
{
    $user = Auth::user();
    $manager = Manager::where('user_id', $user->id)->firstOrFail();

    session(['manager_id' => $manager->id]);

    $productIds = $request->input('products', []); // если пусто, будет []

    // Удалим все предыдущие продукты менеджера
    ManagerProduct::where('manager_id', $manager->id)->delete();

    

    // Добавим новые (если есть)
    foreach ($productIds as $productId) {

        $product = Products::where('id', $productId)
        ->first();

        ManagerProduct::create([
            'manager_id' => $manager->id,
            'product_id' => $productId,
            'price' => $product->min_price, // если нужно — можно сюда передать цену из формы
        ]);
    }

    return redirect()->back()->with('success', 'Saved');
}




}
