@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="mb-0">🤝 Поставщики</h1>
        <a href="{{ route('suppliers.create') }}" class="btn btn-success">➕ Добавить поставщика</a>

         <div class="d-flex">

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

        

         {{-- Отчеты --}}
            <a href="{{ route('reports.index') }}" class="ms-3 fs-4 text-decoration-none" title="Отчеты">📊</a>

        {{-- Константы --}}
        <a href="{{ route('constants.index') }}" class="ms-3 fs-4 text-decoration-none" title="Настройки">⚙️</a>
    </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('suppliers.index') }}" class="mb-3 d-flex">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Поиск по имени, email или телефону">
        <button type="submit" class="btn btn-primary">🔍 Поиск</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Название</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th width="150">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
            <tr>
                <td>
                    <a href="{{ route('suppliers.show', $supplier) }}" class="text-decoration-none">
                        {{ $supplier->name }}
                    </a>
                </td>
                <td>{{ $supplier->email }}</td>
                <td>{{ $supplier->phone }}</td>
                <td>{{ $supplier->address }}</td>
                <td>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">✏️</a>
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Удалить поставщика?')">
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
