@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <h1 class="mb-4">🪑 Модели изделий</h1>

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

            {{-- Поставщики --}}
            <a href="{{ route('suppliers.index') }}" class="ms-3 fs-4 text-decoration-none" title="Поставщики">🤝</a>

             {{-- Отчеты --}}
            <a href="{{ route('reports.index') }}" class="ms-3 fs-4 text-decoration-none" title="Отчеты">📊</a>

            {{-- Настройки --}}
            <a href="{{ route('constants.index') }}" class="ms-3 fs-4 text-decoration-none" title="Настройки">⚙️</a>
           
        </div>
    </div>
    
    <a href="{{ route('product_models.create') }}" class="btn btn-primary mb-3">➕ Добавить модель</a>

    <div class="row">
        @foreach($models as $model)
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                @if($model->photo)
                    <img src="{{ asset('storage/'.$model->photo) }}" class="card-img-top" style="height:200px;object-fit:cover">
                @endif
                <div class="card-body">
                    <h5 class="fw-bold">{{ $model->name }}</h5>
                    <small class="text-muted">Артикул: {{ $model->sku ?? '—' }}</small>
                    <hr>
                    <p class="mb-1 fw-semibold">Состав:</p>
                    <ul>
                        @foreach($model->components as $comp)
                            <li>{{ $comp->rawmaterial?->name ?? '—' }} — {{ $comp->quantity }}</li>
                        @endforeach
                    </ul>

                    @if ($model->description)
                        <p class="mb-1 fw-semibold">Описание:</p>
                        <p>{{ $model->description }}</p>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('product_models.edit', $model) }}" class="btn btn-warning btn-sm">✏️</a>
                        <form action="{{ route('product_models.destroy', $model) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Удалить модель?')">🗑️</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
