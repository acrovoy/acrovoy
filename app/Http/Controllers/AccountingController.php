<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Models\AccountArticle;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\Supply;
use App\Models\ManufacturedProduct;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $incomeQuery = Income::query();
        $expenseQuery = Expense::query();

        if ($request->filled('date_from')) {
            $incomeQuery->where('date', '>=', $request->date_from);
            $expenseQuery->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $incomeQuery->where('date', '<=', $request->date_to);
            $expenseQuery->where('date', '<=', $request->date_to);
        }

        if ($request->filled('income_category')) {
            $incomeQuery->where('category', $request->income_category);
        }

        if ($request->filled('expense_category')) {
            $expenseQuery->where('category', $request->expense_category);
        }

        $latestSupplies = Supply::with('warehouse', 'supplier')
        ->latest('date_received')
        ->take(10)
        ->get();

        $suppliesRaw = Supply::where('category_id', 1)
        ->latest('date_received')
        ->get();
        $suppliesReady = Supply::where('category_id', 2)
        ->latest('date_received')
        ->get();



        $incomes = $incomeQuery->with('clientRelation')->latest()->get();
        $expenses = $expenseQuery->with('supplierRelation')->latest()->get();

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $grossProfit = $totalIncome - $totalExpense;
        $operatingExpenses = $expenses->where('category', 'Операционные')->sum('amount');
        $netProfit = $grossProfit - $operatingExpenses;
        $taxRate = 6000;
        $tax = 0;
        $profitAfterTax = $netProfit - $tax;
        $rental = $totalIncome > 0 ? ($profitAfterTax / $totalIncome) * 100 : 0;


        

        // === Данные для виджетов ===
       
       
        // Получаем все изделия произведенные но не перемещенные на склад

        $producedProducts = ManufacturedProduct::with('warehouse', 'category')
        ->where('status', 'produced')
        ->latest('created_at')
        ->get();




        $warehouseBalances = Warehouse::all();

         // Получаем все склады с поставками
        $warehouses = Warehouse::with('supplies')->get();

        // Последние доходы (например, 5 последних)
       $latestIncomes = Income::with('clientRelation')->latest()->take(10)->get();

       $incomeCategories = IncomeCategory::all();
        $expenseCategories = ExpenseCategory::all();



        return view('accounting.index', compact(
            'incomes', 'expenses', 'totalIncome', 'totalExpense',
            'grossProfit', 'operatingExpenses', 'netProfit', 'tax', 'profitAfterTax', 'rental', 
            'warehouseBalances','producedProducts', 'suppliesRaw', 'suppliesReady','latestSupplies','warehouses','latestIncomes','incomeCategories', 'expenseCategories'
        ));
    }

    // === ДОХОДЫ ===
    public function createIncome()
    {
        return view('accounting.income_create', [
            'clients' => Client::all(),
            'categories' => IncomeCategory::all(),
            'paymentMethods' => PaymentMethod::all(),
            'articles' => AccountArticle::all(),
            'warehouses' => Warehouse::all(),
        ]);
    }

    public function storeIncome(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'client_name' => 'nullable|string',
            'description' => 'required',
            'amount' => 'required|numeric',
        ]);

        // Если выбран "новый клиент"
        if (!$request->client_id && $request->filled('client_name')) {
            $client = Client::create(['name' => $request->client_name]);
            $request->merge(['client_id' => $client->id]);
        }

        Income::create([
    'date' => $request->date,
    'document_number' => $request->document_number,
    'client_id' => $request->client_id, // вместо client
    'description' => $request->description,
    'category' => $request->category,
    'amount' => $request->amount,
    'payment_method' => $request->payment_method,
    'account_article' => $request->account_article,
    'warehouse' => $request->warehouse,
    'comment' => $request->comment,
]);

        return redirect()->route('reports.index', ['tab' => 'incomes'])->with('success', 'Доход добавлен');
    }

    // === РАСХОДЫ ===
    public function createExpense()
    {
        return view('accounting.expense_create', [
            'suppliers' => Supplier::all(),
            'categories' => ExpenseCategory::all(),
            'paymentMethods' => PaymentMethod::all(),
            'articles' => AccountArticle::all(),
            'warehouses' => Warehouse::all(),
        ]);
    }

    public function storeExpense(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'supplier_name' => 'nullable|string',
        'description' => 'required',
        'amount' => 'required|numeric',
        'document_number' => 'nullable|string',
        'category' => 'nullable|string',
        'payment_method' => 'nullable|string',
        'account_article' => 'nullable|string',
        'warehouse' => 'nullable|string',
        'comment' => 'nullable|string',
    ]);

    // Если выбран "новый поставщик"
    if (!$request->supplier_id && $request->filled('supplier_name')) {
        $supplier = Supplier::create(['name' => $request->supplier_name]);
        $request->merge(['supplier_id' => $supplier->id]);
    }

    Expense::create([
        'date' => $request->date,
        'document_number' => $request->document_number,
        'supplier_id' => $request->supplier_id,
        'description' => $request->description,
        'category' => $request->category,
        'amount' => $request->amount,
        'payment_method' => $request->payment_method,
        'account_article' => $request->account_article,
        'warehouse' => $request->warehouse,
        'comment' => $request->comment,
    ]);

    return redirect()->route('reports.index', ['tab' => 'expenses'])->with('success', 'Расход добавлен');
}

    // === РЕДАКТИРОВАНИЕ / УДАЛЕНИЕ ===
    public function editIncome(Income $income)
    {
        return view('accounting.income_edit', [
            'income' => $income,
            'clients' => Client::all(),
            'categories' => IncomeCategory::all(),
            'paymentMethods' => PaymentMethod::all(),
            'articles' => AccountArticle::all(),
            'warehouses' => Warehouse::all(),
        ]);
    }

    public function updateIncome(Request $request, Income $income)
    {
        $income->update($request->all());
        return redirect()->route('reports.index', ['tab' => 'incomes'])
                 ->with('success', 'Доход обновлён');
    }

    public function destroyIncome(Income $income)
    {
        $income->delete();
        return redirect()->route('reports.index', ['tab' => 'incomes'])->with('success', 'Доход удалён');
    }

    public function editExpense(Expense $expense)
    {
        return view('accounting.expense_edit', [
            'expense' => $expense,
            'suppliers' => Supplier::all(),
            'categories' => ExpenseCategory::all(),
            'paymentMethods' => PaymentMethod::all(),
            'articles' => AccountArticle::all(),
            'warehouses' => Warehouse::all(),
        ]);
    }

    public function updateExpense(Request $request, Expense $expense)
    {
        $expense->update($request->all());
        return redirect()->route('reports.index', ['tab' => 'expenses'])
                     ->with('success', 'Расход обновлён');
    }

    public function destroyExpense(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('reports.index', ['tab' => 'expenses'])->with('success', 'Расход удалён');
    }
}
