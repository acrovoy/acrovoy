@extends('layouts.app')

@section('content')
<div class="container">
    

    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <h1 class="mb-0">🧱 База сырья</h1>

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

             {{-- Отчеты --}}
            <a href="{{ route('reports.index') }}" class="ms-3 fs-4 text-decoration-none" title="Отчеты">📊</a>

            {{-- Настройки --}}
            <a href="{{ route('constants.index') }}" class="ms-3 fs-4 text-decoration-none" title="Настройки">⚙️</a>

           
        </div>
    </div>

    <a href="{{ route('raw-materials.create') }}" class="btn btn-success mb-3">➕ Добавить сырьё</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($materials as $mat)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                @if($mat->photo)
                    <img src="{{ asset('storage/'.$mat->photo) }}" class="card-img-top" alt="{{ $mat->name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $mat->name }}</h5>
                    @if($mat->code)
                        <p class="text-muted small mb-1">Код: {{ $mat->code }}</p>
                    @endif
                    @if($mat->unit)
                        <p class="text-muted small mb-1">Ед. изм: {{ $mat->unit }}</p>
                    @endif
                    @if($mat->description)
                        <p class="small mt-2">{{ $mat->description }}</p>
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('raw-materials.edit', $mat) }}" class="btn btn-primary btn-sm">✏️</a>
                    <form action="{{ route('raw-materials.destroy', $mat) }}" method="POST" onsubmit="return confirm('Удалить?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">🗑️</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection