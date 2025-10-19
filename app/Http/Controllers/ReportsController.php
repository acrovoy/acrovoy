<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Warehouse;
use App\Models\Supply;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // -----------------------
        // 🔹 Фильтры по дате
        // -----------------------
        $from = $request->input('from'); // формат YYYY-MM-DD
        $to = $request->input('to');

       // Доходы
$incomesQuery = Income::query();

if ($from) $incomesQuery->whereDate('date', '>=', $from);
if ($to) $incomesQuery->whereDate('date', '<=', $to);

$incomes = $incomesQuery->orderBy('date', 'desc')->get();

// Расходы
$expensesQuery = Expense::query();

if ($from) $expensesQuery->whereDate('date', '>=', $from);
if ($to) $expensesQuery->whereDate('date', '<=', $to);

$expenses = $expensesQuery->orderBy('date', 'desc')->get();

        // -----------------------
        // 🔹 Остатки по складам
        // -----------------------
        $warehouses = Warehouse::with(['supplies' => function ($query) {
            $query->select('id', 'warehouse_id', 'name', 'unit', 'quantity_remaining', 'price_per_unit');
        }])->get();

        // Суммарные остатки по каждому складу
        $warehouseStocks = Supply::selectRaw('warehouse_id, SUM(quantity_remaining) as total_remaining, SUM(quantity_remaining * price_per_unit) as total_value')
            ->groupBy('warehouse_id')
            ->with('warehouse')
            ->get();

        // -----------------------
        // 🔹 Последние поступления
        // -----------------------
        $latestSuppliesQuery = Supply::with('warehouse', 'supplier')->orderBy('date_received', 'desc');

if ($from) {
    $latestSuppliesQuery->whereDate('date_received', '>=', $from);
}
if ($to) {
    $latestSuppliesQuery->whereDate('date_received', '<=', $to);
}

$latestSupplies = $latestSuppliesQuery->limit(50)->get();

        // -----------------------
        // 🔹 Передаем данные в view
        // -----------------------
        return view('reports.index', [
            'incomes' => $incomes,
            'expenses' => $expenses,
            'warehouses' => $warehouses,
            'warehouseStocks' => $warehouseStocks,
            'latestSupplies' => $latestSupplies,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
