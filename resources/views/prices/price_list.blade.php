@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📄 Прайс-лист</h1>

    {{-- Панель фильтров --}}
    <form method="GET" action="{{ route('prices.list') }}" class="row g-3 mb-4">

        {{-- Поиск --}}
        <div class="col-md-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Поиск по SKU или названию">
        </div>

       

        {{-- Фильтр наличия цен --}}
        <div class="col-md-3">
            <select name="has_price" class="form-select">
                <option value="">Все товары</option>
                <option value="1" {{ request('has_price') === '1' ? 'selected' : '' }}>Только с ценами</option>
                <option value="0" {{ request('has_price') === '0' ? 'selected' : '' }}>Без цен</option>
            </select>
        </div>

        {{-- Сортировка --}}
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="sku" {{ request('sort') === 'sku' ? 'selected' : '' }}>Сортировать по SKU</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>По названию</option>
                <option value="id" {{ request('sort') === 'id' ? 'selected' : '' }}>По ID</option>
            </select>
        </div>

        {{-- Кнопка применить --}}
        <div class="col-md-1">
            <button class="btn btn-primary w-100">OK</button>
        </div>
    </form>

    {{-- Экспорт --}}
    <div class="mb-3">
        <a href="{{ route('prices.export.excel') }}" class="btn btn-success">
            ⬇ Экспорт в Excel
        </a>
    </div>

    {{-- Таблица прайса --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>SKU</th>
                <th>Название</th>
                <th>Себестоимость</th>

                @foreach ($priceTypes as $type)
                    <th>{{ $type->name }}<br><small class="text-muted">{{ $type->code }}</small></th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($products as $product)
                @php
                    $cost = $costs[$product->sku]->cost ?? null;
                @endphp

                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>

                    <td>
                        @if($cost !== null)
                            {{ number_format($cost, 2) }}
                        @else
                            —
                        @endif
                    </td>

                    @foreach ($priceTypes as $type)
                        @php
                            $priceObj = $product->prices->firstWhere('price_type_id', $type->id);
                            $price = $priceObj->price ?? null;

                            $margin = null;
                            $marginPercent = null;

                            if ($price !== null && $cost !== null) {
                                $margin = $price - $cost;
                                $marginPercent = $cost > 0 ? round($margin / $cost * 100) : null;
                            }
                        @endphp

                        <td>
                            @if($price !== null)
                                <div>{{ number_format($price, 2) }}</div>

                                @if($margin !== null)
                                    <small class="text-muted">
                                        +{{ number_format($margin, 2) }}
                                        @if($marginPercent !== null)
                                            ({{ $marginPercent }}%)
                                        @endif
                                    </small>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection
