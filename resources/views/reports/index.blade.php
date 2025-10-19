@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <h1 class="mb-0">📊 Отчёты</h1>

        <div class="d-flex">
            {{-- Бухгалтерия --}}
        <a href="{{ route('accounting.index') }}" class="ms-3 fs-4 text-decoration-none" title="Бухгалтерия">📘</a>
        
            {{-- Склад --}}
            <a href="{{ route('warehouses.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">🏬</a>

            {{-- Производство --}}
            <a href="{{ route('manufactured_products.index') }}" class="ms-3 fs-4 text-decoration-none" title="Производство">🏭</a>

            {{-- Клиенты --}}
            <a href="{{ route('clients.index') }}" class="ms-3 fs-4 text-decoration-none" title="Клиенты">👥</a>

            {{-- Поставщики --}}
            <a href="{{ route('suppliers.index') }}" class="ms-3 fs-4 text-decoration-none" title="Поставщики">🤝</a>

            {{-- Настройки --}}
            <a href="{{ route('constants.index') }}" class="ms-3 fs-4 text-decoration-none" title="Настройки">⚙️</a>

             

        </div>
    </div>





    {{-- 🔹 Фильтр по дате --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">С даты:</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">По дату:</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Применить</button>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">Сбросить</a>
        </div>
    </form>

    @php
        $activeTab = request('tab', 'incomes'); // по умолчанию вкладка Доходы
    @endphp

    {{-- 🔹 Вкладки --}}
    <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'incomes' ? 'active' : '' }}" 
                    id="incomes-tab" data-bs-toggle="tab" data-bs-target="#incomes" type="button" role="tab">Доходы</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'expenses' ? 'active' : '' }}" 
                    id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab">Расходы</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'warehouses' ? 'active' : '' }}" 
                    id="warehouses-tab" data-bs-toggle="tab" data-bs-target="#warehouses" type="button" role="tab">Остатки по складам</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'supplies' ? 'active' : '' }}" 
                    id="supplies-tab" data-bs-toggle="tab" data-bs-target="#supplies" type="button" role="tab">Поступления</button>
        </li>
    </ul>

    <div class="tab-content" id="reportTabsContent">
        {{-- 🔹 Доходы --}}
        <div class="tab-pane fade {{ $activeTab === 'incomes' ? 'show active' : '' }}" id="incomes" role="tabpanel">
            <h4>Доходы <a href="{{ route('income.create') }}" class="btn btn-success me-2">➕</a></h4>
            <table class="table table-striped table-sm align-middle">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Документ</th>
                        <th>Клиент</th>
                        <th>Описание</th>
                        <th>Категория</th>
                        <th>Сумма (₴)</th>
                        <th>Способ оплаты</th>
                        <th>Статья учёта</th>
                        <th>Склад</th>
                        <th>Комментарий</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomes as $income)
                        <tr>
                            <td>{{ $income->date }}</td>
                            <td>{{ $income->document_number ?? '-' }}</td>
                            <td>{{ $income->clientRelation->name ?? '-' }}</td>
                            <td>{{ $income->description ?? '-' }}</td>
                            <td>{{ $income->category ?? '—' }}</td>
                            <td class="text-success fw-bold">{{ number_format($income->amount, 2, ',', ' ') }}</td>
                            <td>{{ $income->payment_method ?? '-' }}</td>
                            <td>{{ $income->account_article ?? '-' }}</td>
                            <td>{{ $income->warehouse ?? '-' }}</td>
                            <td>{{ $income->comment ?? '-' }}</td>
                            <td>
                                <a href="{{ route('income.edit', $income->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                                <form action="{{ route('income.destroy', $income->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить этот доход?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="text-center text-muted">Нет данных</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 🔹 Расходы --}}
        <div class="tab-pane fade {{ $activeTab === 'expenses' ? 'show active' : '' }}" id="expenses" role="tabpanel">
            <h4>Расходы <a href="{{ route('expense.create') }}" class="btn btn-danger">➕</a></h4>
            <table class="table table-striped table-sm align-middle">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Документ</th>
                        <th>Поставщик</th>
                        <th>Описание</th>
                        <th>Категория</th>
                        <th>Сумма (₴)</th>
                        <th>Способ оплаты</th>
                        <th>Статья учёта</th>
                        <th>Склад</th>
                        <th>Комментарий</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->date }}</td>
                            <td>{{ $expense->document_number ?? '-' }}</td>
                            <td>{{ $expense->supplierRelation->name ?? '-' }}</td>
                            <td>{{ $expense->description ?? '-' }}</td>
                            <td>{{ $expense->category ?? '—' }}</td>
                            <td class="text-danger fw-bold">{{ number_format($expense->amount, 2, ',', ' ') }}</td>
                            <td>{{ $expense->payment_method ?? '-' }}</td>
                            <td>{{ $expense->account_article ?? '-' }}</td>
                            <td>{{ $expense->warehouse ?? '-' }}</td>
                            <td>{{ $expense->comment ?? '-' }}</td>
                            <td>
                                <a href="{{ route('expense.edit', $expense->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                                <form action="{{ route('expense.destroy', $expense->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить этот расход?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="text-center text-muted">Нет данных</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 🔹 Остатки по складам --}}
        <div class="tab-pane fade {{ $activeTab === 'warehouses' ? 'show active' : '' }}" id="warehouses" role="tabpanel">
            <h4 class="mb-3">Остатки по складам</h4>
            @foreach($warehouses as $warehouse)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <strong>{{ $warehouse->name }}</strong>
                        @if($warehouse->location)
                            <span class="text-muted">({{ $warehouse->location }})</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Наименование</th>
                                    <th>Ед.</th>
                                    <th class="text-end">Остаток</th>
                                    <th class="text-end">Цена за ед.</th>
                                    <th class="text-end">Стоимость</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warehouse->supplies as $supply)
                                    <tr>
                                        <td>{{ $supply->name }}</td>
                                        <td>{{ $supply->unit }}</td>
                                        <td class="text-end">{{ $supply->quantity_remaining }}</td>
                                        <td class="text-end">{{ number_format($supply->price_per_unit, 2, ',', ' ') }}</td>
                                        <td class="text-end fw-semibold">{{ number_format($supply->quantity_remaining * $supply->price_per_unit, 2, ',', ' ') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Нет товаров на складе</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 🔹 Последние поступления --}}
        <div class="tab-pane fade {{ $activeTab === 'supplies' ? 'show active' : '' }}" id="supplies" role="tabpanel">
            <h4 class="mb-3">Ппоступления товаров на склад</h4>
            <table class="table table-striped table-sm align-middle">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Склад</th>
                        <th>Номер документа</th>
                        <th>SKU</th>
                        <th>Наименование</th>
                        <th>Категория</th>
                        <th>Поставщик</th>
                        <th>Кол-во</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestSupplies as $supply)
                        <tr>
                            <td>{{ $supply->date_received->format('d.m.Y.') }}</td>
                            <td>{{ $supply->warehouse->name ?? '-' }}</td>
                            <td>{{ $supply->document_number }}</td>
                            <td>{{ $supply->sku }}</td>
                            <td>{{ $supply->name }}</td>
                            <td>{{ $supply->category->name }}</td>
                            <td>{{ $supply->supplier->name ?? $supply->supplier_name ?? '—' }}</td>
                            <td class="text-end">{{ $supply->quantity }}</td>
                            <td class="text-end">{{ number_format($supply->price_per_unit, 2, ',', ' ') }}</td>
                            <td class="text-end fw-semibold">{{ number_format($supply->quantity * $supply->price_per_unit, 2, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Нет поступлений</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
