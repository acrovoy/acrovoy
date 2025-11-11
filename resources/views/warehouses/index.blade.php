@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <h1 class="mb-0">🏬 Склады</h1>
        <a href="{{ route('warehouses.create') }}" class="btn btn-success">➕ Добавить склад</a>

        <div class="d-flex">

        {{-- ПРОДАЖИ --}}
            <a href="{{ route('product_sales.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">💰</a>

            
        {{-- Бухгалтерия --}}
        <a href="{{ route('accounting.index') }}" class="ms-3 fs-4 text-decoration-none" title="Бухгалтерия">📘</a>

        {{-- Производство --}}
            <a href="{{ route('manufactured_products.index') }}" class="ms-3 fs-4 text-decoration-none" title="Производство">🏭</a>

             {{-- Модели товаров --}}
            <a href="{{ route('product_models.index') }}" class="ms-3 fs-4 text-decoration-none" title="Модели товаров">🪑</a>
            
        {{-- Клиенты --}}
        <a href="{{ route('clients.index') }}" class="ms-3 fs-4 text-decoration-none" title="Клиенты">👥</a>

        {{-- Поставщики --}}
        <a href="{{ route('suppliers.index') }}" class="ms-3 fs-4 text-decoration-none" title="Поставщики">🤝</a>

         {{-- Отчеты --}}
            <a href="{{ route('reports.index') }}" class="ms-3 fs-4 text-decoration-none" title="Отчеты">📊</a>

        {{-- Настройки --}}
        <a href="{{ route('constants.index') }}" class="ms-3 fs-4 text-decoration-none" title="Настройки">⚙️</a>
    
    </div>


    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('warehouses.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Поиск по названию, локации или менеджеру">
        <button type="submit" class="btn btn-primary">🔍 Поиск</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Название</th>
                <th>Локация</th>
                <th>Менеджер</th>
                <th width="150">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warehouses as $warehouse)
            <tr>
                <td>
                    <a href="{{ route('warehouses.show', $warehouse) }}" class="text-decoration-none">
                        {{ $warehouse->name }}
                    </a>
                    <a href="{{ route('warehouses.manage', $warehouse) }}" class="btn btn-sm btn-secondary ms-2" title="Управлять">
                        ⚙️ Управлять
                    </a>
                </td>
                <td>{{ $warehouse->location }}</td>
                <td>{{ $warehouse->manager }}</td>
                <td>
                    <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-sm btn-warning">✏️</a>
                    <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Удалить склад?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
