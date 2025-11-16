@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="mb-0">👥 Клиенты</h1>
        <a href="{{ route('clients.create') }}" class="btn btn-success">➕ Добавить клиента</a>

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
            
        {{-- Поставщики --}}
        <a href="{{ route('suppliers.index') }}" class="ms-3 fs-4 text-decoration-none" title="Поставщики">🤝</a>

        

         {{-- Отчеты --}}
            <a href="{{ route('reports.index') }}" class="ms-3 fs-4 text-decoration-none" title="Отчеты">📊</a>

        {{-- Константы --}}
        <a href="{{ route('constants.index') }}" class="ms-3 fs-4 text-decoration-none" title="Настройки">⚙️</a>
    </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('clients.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Поиск по имени, email или телефону">
        <button type="submit" class="btn btn-primary">🔍 Поиск</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th width="150">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>
                    <a href="{{ route('clients.show', $client) }}" class="text-decoration-none">
                        {{ $client->name }}
                    </a>
                </td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->phone }}</td>
                <td>{{ $client->address }}</td>
                <td>
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-warning">✏️</a>
                    <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Удалить клиента?')">
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
