@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <h1 class="mb-4">🪑 Модели изделий</h1>

        <div class="d-flex">

        {{-- ПРОДАЖИ --}}
            <a href="{{ route('product_sales.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">💰</a>


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
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">{{ $model->name }}</h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $model->id }}" aria-expanded="false" aria-controls="collapse-{{ $model->id }}">
                        ▼
                    </button>
                </div>
                <small class="text-muted">Артикул: {{ $model->sku ?? '—' }}</small>

                {{-- Кол-во можно произвести всегда видно --}}
                @if ($model->can_produce > 0)
                    <p class="text-success fw-bold">Можно произвести {{ $model->can_produce }} шт.</p>
                @else
                    <p class="text-danger fw-bold">Недостаточно сырья</p>
                @endif

                <div class="collapse mt-2" id="collapse-{{ $model->id }}">
                    <hr>
                    <p class="mb-1 fw-semibold">🧩 Состав:</p>
                    <ul>
                        @foreach($model->components as $comp)
                            <li>{{ $comp->rawmaterial?->name ?? '—' }} — {{ $comp->quantity }}</li>
                        @endforeach
                    </ul>

                    @if ($model->description)
                        <p class="mb-1 fw-semibold">📄 Описание:</p>
                        <p>{{ $model->description }}</p>
                    @endif

                    @if (!empty($model->stock_details))
                        <p class="mb-1 fw-semibold">📦 Остатки по компонентам:</p>
                        <table class="table table-sm mt-2">
                            <thead class="table-light">
                                <tr>
                                    <th>Сырьё</th>
                                    <th>Код</th>
                                    <th>Нужно</th>
                                    <th>Есть</th>
                                    <th>Можно сделать</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($model->stock_details as $d)
                                    <tr>
                                        <td>{{ $d['name'] }}</td>
                                        <td>{{ $d['code'] ?? '—' }}</td>
                                        <td>{{ $d['required'] }}</td>
                                        <td>{{ $d['available'] }}</td>
                                        <td>{{ $d['can_make'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('product_models.edit', $model) }}" class="btn btn-warning btn-sm">✏️</a>
                        <form action="{{ route('product_models.destroy', $model) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Удалить модель?')">🗑️</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>
@endsection
