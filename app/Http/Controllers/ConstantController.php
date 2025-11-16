<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Models\AccountArticle;
use App\Models\SupplyCategory;
use App\Models\Unit;
use App\Models\PaymentTerm;
use App\Models\PriceType;

class ConstantController extends Controller
{

    protected $connection = 'mysql_rattan'; // если таблица на другом соединении
    public function index()
    {
        $incomeCategories = IncomeCategory::orderBy('name')->get();
        $expenseCategories = ExpenseCategory::orderBy('name')->get();
        return view('constants.index', compact('incomeCategories', 'expenseCategories'));
    }

    // Категории дохода
    public function storeIncome(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:mysql_rattan.income_categories,name',
        ]);

        IncomeCategory::create(['name' => $request->name]);

        return redirect()->route('constants.index')->with('success', 'Категория дохода добавлена!');
    }

    public function updateIncome(Request $request, IncomeCategory $constant)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:mysql_rattan.income_categories,name,' . $constant->id,
        ]);

        $constant->update(['name' => $request->name]);

        return redirect()->route('constants.index')->with('success', 'Категория дохода обновлена!');
    }

    public function destroyIncome(IncomeCategory $constant)
    {
        $constant->delete();
        return redirect()->route('constants.index')->with('success', 'Категория дохода удалена!');
    }

    // Категории расходов
    public function storeExpense(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:mysql_rattan.expense_categories,name',
        ]);

        ExpenseCategory::create(['name' => $request->name]);

        return redirect()->route('constants.index')->with('success', 'Категория расхода добавлена!');
    }

    public function updateExpense(Request $request, ExpenseCategory $constant)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:mysql_rattan.expense_categories,name,' . $constant->id,
        ]);

        $constant->update(['name' => $request->name]);

        return redirect()->route('constants.index')->with('success', 'Категория расхода обновлена!');
    }

    public function destroyExpense(ExpenseCategory $constant)
    {
        $constant->delete();
        return redirect()->route('constants.index')->with('success', 'Категория расхода удалена!');
    }


    // СПОСОБЫ ОПЛАТЫ
public function storePayment(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.payment_methods,name',
    ]);

    PaymentMethod::create(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Способ оплаты добавлен!');
}

public function updatePayment(Request $request, PaymentMethod $constant)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.payment_methods,name,' . $constant->id,
    ]);

    $constant->update(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Способ оплаты обновлен!');
}

public function destroyPayment(PaymentMethod $constant)
{
    $constant->delete();
    return redirect()->route('constants.index')->with('success', 'Способ оплаты удален!');
}

// СТАТЬИ УЧЁТА
public function storeAccountArticle(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.account_articles,name',
    ]);

    AccountArticle::create(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Статья учёта добавлена!');
}

public function updateAccountArticle(Request $request, AccountArticle $constant)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.account_articles,name,' . $constant->id,
    ]);

    $constant->update(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Статья учёта обновлена!');
}

public function destroyAccountArticle(AccountArticle $constant)
{
    $constant->delete();
    return redirect()->route('constants.index')->with('success', 'Статья учёта удалена!');
}


// КАТЕГОРИИ ПОСТАВОК
public function storeSupply(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.supply_categories,name',
    ]);

    SupplyCategory::create(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Категория поставки добавлена!');
}

public function updateSupply(Request $request, SupplyCategory $constant)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.supply_categories,name,' . $constant->id,
    ]);

    $constant->update(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Категория поставки обновлена!');
}

public function destroySupply(SupplyCategory $constant)
{
    $constant->delete();
    return redirect()->route('constants.index')->with('success', 'Категория поставки удалена!');
}


// ЕДИНИЦЫ ИЗМЕРЕНИЯ
public function storeUnit(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.units,name',
    ]);

    Unit::create(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Единица измерения добавлена!');
}

public function updateUnit(Request $request, Unit $constant)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.units,name,' . $constant->id,
    ]);

    $constant->update(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Единица измерения обновлена!');
}

public function destroyUnit(Unit $constant)
{
    $constant->delete();
    return redirect()->route('constants.index')->with('success', 'Единица измерения удалена!');
}


// УСЛОВИЯ ОПЛАТЫ
public function storePaymentTerm(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.payment_terms,name',
    ]);

    PaymentTerm::create(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Условие оплаты добавлено!');
}

public function updatePaymentTerm(Request $request, PaymentTerm $constant)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:mysql_rattan.payment_terms,name,' . $constant->id,
    ]);

    $constant->update(['name' => $request->name]);

    return redirect()->route('constants.index')->with('success', 'Условие оплаты обновлено!');
}

public function destroyPaymentTerm(PaymentTerm $constant)
{
    $constant->delete();
    return redirect()->route('constants.index')->with('success', 'Условие оплаты удалено!');
}



// Добавление нового типа цены
public function storePriceType(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255',
    ]);

    PriceType::create([
        'name' => $request->name,
        'code' => $request->code,
    ]);

    return redirect()->back()->with('success', 'Тип цены успешно добавлен.');
}

// Обновление существующего типа цены
public function updatePriceType(Request $request, PriceType $priceType)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255' . $priceType->id,
    ]);

    $priceType->update([
        'name' => $request->name,
        'code' => $request->code,
    ]);

    return redirect()->back()->with('success', 'Тип цены успешно обновлён.');
}

// Удаление типа цены
public function destroyPriceType(PriceType $priceType)
{
    $priceType->delete();

    return redirect()->back()->with('success', 'Тип цены успешно удалён.');
}


}
