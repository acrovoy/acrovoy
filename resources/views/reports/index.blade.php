@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <h1 class="mb-0">📊 Отчёты</h1>

        <div class="d-flex">

        {{-- ПРАЙС --}}
            <a href="{{ route('product_prices.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">📋</a>
            
        {{-- ПРОДАЖИ --}}
            <a href="{{ route('product_sales.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">💰</a>

            {{-- Бухгалтерия --}}
        <a href="{{ route('accounting.index') }}" class="ms-3 fs-4 text-decoration-none" title="Бухгалтерия">📘</a>
        
            {{-- Склад --}}
            <a href="{{ route('warehouses.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">🏬</a>

            {{-- Производство --}}
            <a href="{{ route('manufactured_products.index') }}" class="ms-3 fs-4 text-decoration-none" title="Производство">🏭</a>

             {{-- Модели товаров --}}
            <a href="{{ route('product_models.index') }}" class="ms-3 fs-4 text-decoration-none" title="Модели товаров">🪑</a>
            
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
        $activeTab = request('tab', 'incomes');
    @endphp

    {{-- 🔹 Вкладки --}}
    <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'incomes' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#incomes">Доходы</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'expenses' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#expenses">Расходы</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'warehouses' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#warehouses">Остатки по складам</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'supplies' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#supplies">Поступления</button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- 🔹 Доходы --}}
        <div class="tab-pane fade {{ $activeTab === 'incomes' ? 'show active' : '' }}" id="incomes">
            <h4>Доход <a href="{{ route('income.create') }}" class="btn btn-success">➕</a></h4>
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Документ</th>
                    <th>Клиент</th>
                    <th>Описание</th>
                    <th>Категория</th>
                    <th>Сумма</th>
                    <th>Склад</th>
                </tr>
                </thead>
                <tbody>
                @foreach($incomes as $income)
                    <tr>
                        <td>{{ $income->date->format('d.m.Y') }}</td>
                        <td>{{ $income->document_number }}</td>
                        <td>{{ $income->clientRelation->name ?? '-' }}</td>
                        <td>{{ $income->description }}</td>
                        <td>{{ $income->category }}</td>
                        <td class="text-success">{{ number_format($income->amount,2,',',' ') }}</td>
                        <td>{{ $income->warehouse }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- 🔹 Склады --}}
        <div class="tab-pane fade {{ $activeTab === 'warehouses' ? 'show active' : '' }}" id="warehouses">

            <h4 class="mb-3">Остатки по складам</h4>

            @foreach($warehouses as $warehouse)

                <div class="card mb-3">

                    {{-- 🔽 КЛИКАБЕЛЬНАЯ ШАПКА --}}
                    <div class="card-header d-flex justify-content-between align-items-center bg-light"
                         data-bs-toggle="collapse"
                         data-bs-target="#wh-{{ $warehouse->id }}"
                         style="cursor:pointer;">

                        <div>
                            <strong>{{ $warehouse->name }}</strong>
                            @if($warehouse->location)
                                <span class="text-muted">({{ $warehouse->location }})</span>
                            @endif
                        </div>

                        <span class="arrow">⬇️</span>
                    </div>

                    {{-- 🔽 СКРЫВАЕМЫЙ БЛОК --}}
                    <div id="wh-{{ $warehouse->id }}" class="collapse show">

                        <div class="card-body p-0">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Наименование</th>
                                    <th>Ед.</th>
                                    <th class="text-end">Остаток</th>
                                    <th class="text-end">Цена</th>
                                    <th class="text-end">Стоимость</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($warehouse->supplies as $supply)
                                    <tr>
                                        <td>{{ $supply->name }}</td>
                                        <td>{{ $supply->unit }}</td>
                                        <td class="text-end">{{ $supply->quantity_remaining }}</td>
                                        <td class="text-end">{{ number_format($supply->price_per_unit,2,',',' ') }}</td>
                                        <td class="text-end">
                                            {{ number_format($supply->quantity_remaining * $supply->price_per_unit,2,',',' ') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            @endforeach
        </div>

    </div>
</div>

{{-- 🔥 АНИМАЦИЯ СТРЕЛКИ --}}
<style>
.card-header .arrow {
    transition: transform 0.3s;
}
.card-header[aria-expanded="true"] .arrow {
    transform: rotate(180deg);
}
</style>

@endsection