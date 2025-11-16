@extends('layouts.app')

@section('content')
<div class="container">
    

    <div class="d-flex align-items-center justify-content-between mt-4">
        <h1 class="mb-3">📋 Прайс-лист</h1>

        <div class="d-flex">

       

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

             {{-- Отчеты --}}
            <a href="{{ route('reports.index') }}" class="ms-3 fs-4 text-decoration-none" title="Отчеты">📊</a>

            {{-- Настройки --}}
            <a href="{{ route('constants.index') }}" class="ms-3 fs-4 text-decoration-none" title="Настройки">⚙️</a>

           
        </div>
    </div>

    <a href="{{ route('product_prices.create') }}" class="btn btn-success mb-3">➕ Добавить цену</a>
    <a href="{{ route('prices.list') }}" class="mb-3">📋</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Название</th>
                <th>Тип цены</th>
                <th>Цена</th>
                <th>Себестоимость</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prices as $price)
                <tr>
                    <td>{{ $price->sku }}</td>
                    <td>{{ $price->productModel->name ?? '-' }}</td>
                    <td>{{ $price->type->name ?? '-' }}</td>
                    <td>{{ $price->price }}</td>
                    
                    {{-- Себестоимость --}}
                    <td>{{ number_format($price->calculated_cost, 2) ?? '-' }}</td>
                
                    <td>
                        <a href="{{ route('product_prices.show', $price->id) }}" class="btn btn-sm btn-info">Просмотр</a>
                        <a href="{{ route('product_prices.edit', $price->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                        <form action="{{ route('product_prices.destroy', $price->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
